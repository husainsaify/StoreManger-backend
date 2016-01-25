<?php
require_once "./core/init.php";
$result = array();
	if (isset($_POST["productId"]) && isset($_POST["categoryId"]) && isset($_POST["userId"]) && isset($_POST["pImage"]) && isset($_POST["pName"]) && isset($_POST["pCode"]) && isset($_POST["pCode"]) && isset($_POST["pCP"]) && isset($_POST['pSP']) && isset($_POST["pSize"]) && isset($_POST["pQuantity"])) {
		//Escape variables
		$pId = e($_POST["productId"]);
		$cId = e($_POST["categoryId"]);
		$userId = e($_POST["userId"]);
		$image = e($_POST["pImage"]);
		$name = e($_POST["pName"]);
		$code = e($_POST["pCode"]);
		$cp = e($_POST["pCP"]);
		$sp = e($_POST['pSP']);
		$sizeStack = e($_POST["pSize"]);
		$quantityStack = e($_POST["pQuantity"]);

		//empty
		if (empty($pId) || empty($cId) || empty($userId) || empty($name) || empty($code) || empty($cp) || empty($sp) || empty($sizeStack) || empty($quantityStack)) {
			$result["message"] = "Fill in all the fields";
			$result["return"] = false;
		}

		//check user account exits
		if(!check_user($userId)){
			$result["return"] = false;
			$result["message"] = "Invalid user.";
			echo json_encode($result);
			exit;
		}

		//check category name exits
		if(!check_category_is_valid($cId,$userId)){
			$result["message"] = "Invalid Category: This category does not belongs to you";
			$result["return"] = false;
			echo json_encode($result);
			exit;
		}

		//check name length
		if(strlen($name) < 2){
			$result["message"] = "Product name should be more then 2 characters";
			$result["return"] = false;
			echo json_encode($result);
			exit;
		}

		//check image
		if(!empty($image)){
			//decode the image and upload it
			$decodemage = base64_decode($image);

			$filename = "IMG_".time().".jpg";

			//create a directory name for the edited products images
			$dirCname = "edit";

			//make a dir if not exits
			if(!is_dir("pic/{$userId}/{$dirCname}")){

				//check directory not exits create
				if(!is_dir("pic/{$userId}/")){
					mkdir("pic/{$userId}/"); 
				}
				mkdir("pic/{$userId}/{$dirCname}");
			}
			//upload image
			file_put_contents("pic/{$userId}/{$dirCname}/{$filename}",$decodemage);
			$imagePath = "pic/{$userId}/{$dirCname}/{$filename}";

			//Create a thumbnail of the image
			$image_size = getimagesize($imagePath);
			$image_width = $image_size[0];
			$image_height = $image_size[1];

			$new_size = ($image_width + $image_height) / ($image_width * ($image_height / 45));
			$new_width = $image_width * $new_size;
			$new_height = $image_height * $new_size;

			$new_image = imagecreatetruecolor($new_width, $new_height);
			$old_image = imagecreatefromjpeg($imagePath);
			imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			//create name for image thumbnail
			$imageThumb = "pic/{$userId}/{$dirCname}/THUMB_".time().".jpg";
			imagejpeg($new_image,"./".$imageThumb);

		}else{
			$imagePath = "";
			$imageThumb = "";
		}

		//generate keywords
		$keywords = $name.' '.$code;

		//generate size Keywords
		$size_keywords = "";
		$sizeArray = explode(",", $sizeStack);
		$size = remove_last_empty_item($sizeArray);
		foreach ($size as $key => $s) {
			$size_keywords .= $s;
			//add space
			if($key < count($size)-1){
				$size_keywords .= " ";
			}
		}
		

		/*
			If image is empty
			Because if the person does not select an image he so ok with old image 
			so their is no point delete the path of the old image stored in the database
		*/
		if(empty($imagePath) && empty($imageThumb)){
			//Update product
			Db::update("product",array(
				"name" => $name,
				"CP" => $cp,
				"SP" => $sp,
				"user_id" => $userId,
				"category_id" => $cId,
				"keywords" => $keywords,
				"size_keywords" => $size_keywords,
				"last_edited" => time()),array("id","=",$pId));
		}else{
			//Update product
			Db::update("product",array(
				"name" => $name,
				"image" => $imagePath,
				"image_thumb" => $imageThumb,
				"CP" => $cp,
				"SP" => $sp,
				"user_id" => $userId,
				"category_id" => $cId,
				"keywords" => $keywords,
				"size_keywords" => $size_keywords,
				"last_edited" => time()),array("id","=",$pId));
		}

		if(!Db::getError()){

			//delete old size & quanity
			Db::delete("sq",array("product_id","=",$pId," AND ","user_id","=",$userId));

			/*
				Insert new Size & Quantity into SQ table
			*/
			//create a size array from Size stack
			//(Creatd on line 97)

			//create a quantity arra from quantity stack
			$quantityArray = explode(",", $quantityStack);
			$quantity = remove_last_empty_item($quantityArray);

			//insert
			foreach ($size as $key => $s) {

				/*
					check product with that is size already in the db or not
					if yes fetch old size quantity from the db
					and add new size quantity to it and update into db
				*/
				if(!check_size_is_unique($s,$userId,$pId)){
					//fetch the quantity from db
					$stmt = Db::query("SELECT quantity FROM `sq` WHERE size = ? AND user_id = ? AND product_id = ?",array($s,$userId,$pId));
					$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$oldQuantity = $r[0]["quantity"];

					//create new quantity
					$newQuantity = $oldQuantity + $quantity[$key];

					//update new quantity into database
					Db::update("sq",array(
    					"quantity" => $newQuantity),
    					array("size","=",$s," AND ","user_id","=",$userId," AND ","product_id","=",$pId));
				}else{
					Db::insert("sq",array(
							"size" => $s,
							"quantity" => $quantity[$key],
							"user_id" => $userId,
							"product_id" => $pId
					));
				}
			}
			//show success message
			$result["message"] = "Product updated succesfully";
			$result["return"] = true;

		}else{
			$result["message"] = "Failed to insert into Database";
			$result["return"] = false;
		}
		echo json_encode($result);
		exit;


	}else{
		$result["message"] = "Access Denied";
		$result["return"] = false;
		echo json_encode($result);
		exit;
	}
?>