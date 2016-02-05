<?php
	require_once "./core/init.php";
	$result = array();

	if(isset($_POST["userId"]) && isset($_POST['customerName']) && isset($_POST['name']) && isset($_POST["size"]) && isset($_POST["quantity"]) && isset($_POST["costprice"]) && isset($_POST["sellingprice"]) && isset($_POST["salesmanId"]) && isset($_POST["salesmanName"])) {
		
		$user_id = e($_POST["userId"]);
		$customer_name = e($_POST["customerName"]);
		$name_stack = e($_POST["name"]);
		$size_stack = e($_POST["size"]);
		$quantity_stack = e($_POST["quantity"]);
		$costprice_stack = e($_POST["costprice"]);
		$sellingprice_stack = e($_POST["sellingprice"]);
		$salesman_id = e($_POST["salesmanId"]);
		$salesman_name = e($_POST["salesmanName"]);
		$date = date("d/m/Y");
		$date_id = date("dmY");

		//check any stuff is not empty
		if (empty($user_id) || empty($name_stack) || empty($size_stack) || empty($quantity_stack) || empty($costprice_stack) || empty($sellingprice_stack) || empty($salesman_id) || empty($salesman_name)) {
			$result["message"] = "Fill in all the fields";
			$result["return"] = false;
			json($result);
		}

		//check user id is valid
		if (!check_user($user_id)) {
			$result["message"] = "Invalid user";
			$result["return"] = false;
			json($result);
		}

		/******************************
		Check if sales is from ListedProduct or NonListedProduct
		 ************************************/

		if(isset($_POST["salesType"]) && isset($_POST["productId"])){

			/*********************** Listed Product *****************/

			$sales_type = e($_POST["salesType"]);
			$product_id = e($_POST["productId"]);

			//check product id is valid or not
			if(!check_productId_is_valid($product_id,$user_id)){
				$result["message"] = "Invalid Product id";
				$result["return"] = false;
				json($result);
			}

			//check size is valid
			$sizeCount = Db::rowCount("sq", array(
					"user_id" => $user_id,
					"product_id" => $product_id,
					"size" => $size_stack
			), array("=", "=", "="));

			if ($sizeCount <= 0) {
				$result["message"] = "Invalid size `{$size_stack}` of product";
				$result["return"] = false;
				json($result);
			}

			//check quantity is not zero
			if($quantity_stack <= 0){
				$result["message"] = "Invalid quantity. Quantity cannot be zero";
				$result["return"] = false;
				json($result);
			}

			//check quantity is not zero
			$quantityQuery = Db::query("SELECT quantity FROM `sq` WHERE user_id=? AND product_id=? AND size=?",array(
					$user_id,
					$product_id,
					$size_stack
			));

			//fetch quantity from the database
			$quantityFetch = $quantityQuery->fetchAll(PDO::FETCH_ASSOC);
			$fetchQuantity = $quantityFetch[0]["quantity"];

			//check quantity is not zero
			if($fetchQuantity <= 0){
				$result["message"] = "Quantity of product `{$name_stack}` is zero";
				$result["return"] = false;
				json($result);
			}

			//check quantity from app is not more then quantity from database
			if($quantity_stack > $fetchQuantity){
				$result["message"] = "You have only {$fetchQuantity} piece of product `{$name_stack}`, Can't reduce {$quantity_stack} piece";
				$result["return"] = false;
				json($result);
			}

			//generate new quantity
			$newQuantity = $fetchQuantity - $quantity_stack;
			//update quantity in `sq`
			$update = Db::query("UPDATE sq SET quantity=? WHERE user_id=? AND product_id=? AND size=?",array(
					$newQuantity,
					$user_id,
					$product_id,
					$size_stack
			));


			//Check Quantity update query executed successfully or not
			if(!Db::getError()){
				//success


				//Insert into Sales
				Db::insert("sales",array(
						"user_id" => $user_id,
						"customer_name" => $customer_name,
						"salesman_id" => $salesman_id,
						"salesman_name" => $salesman_name,
						"date" => $date,
						"date_id" => $date_id,
						"sales_type" => $sales_type,
						"time" => time()));

				$sales_id = 0;
				//check For error
				if(Db::getError()){ //true means error
					$result["message"] = "Failed to insert sales. Try again later";
					$result["return"] = false;
					json($result);
				}else{
					//Get the salesId which is inserted
					$sales_id = Db::lastInsertedId();
				}

				//Store in sales_product_info
				Db::insert("sales_product_info",  array(
						"product_id" => $product_id,
						"sales_id" => $sales_id,
						"user_id" => $user_id,
						"name" => $name_stack,
						"size" => $size_stack,
						"quantity" => $quantity_stack,
						"costprice"=> $costprice_stack,
						"sellingprice" => $sellingprice_stack
				));

				//Check sales Product info Query failed
				if(Db::getError()){
					$result["message"] = "Failed to insert sales product info. Try again later";
					$result["return"] = false;
					json($result);
				}

				/*
					Fetch new size from the database and store it in size_keyword
					in the product table
                */
				$stnt = Db::query("SELECT `size` FROM `sq` WHERE quantity!=? AND user_id=? AND product_id=?",array(
						0,
						$user_id,
						$product_id
				));

				$size_keyword_fetch = $stnt->fetchAll(PDO::FETCH_ASSOC);
				$size_keyword = "";
				//generate new size_keywords
				foreach ($size_keyword_fetch as $key => $size) {
					$size_keyword .= $size["size"];
					if($key < count($size_keyword_fetch)-1){
						$size_keyword .= " ";
					}
				}

				//update size_keyword into product database
				Db::update("product",array(
						"size_keywords" => $size_keyword
				),array("id","=",$product_id));

				//if their is an error and we are unable to insert into database
				if(Db::getError()){
					$result["message"] = "Failed to insert into sales. Try again later";
					$result["return"] = false;
					json($result);
				}else{
					//display success
					$result["message"] = "Sales added successfully";
					$result["return"] = true;
					json($result);
				}


			}else{
				//error
				$result["message"] = "Failed to update quantity. Try again";
				$result["return"] = false;
				json($result);
			}

		}else{
			/***************** Non Listed Product *****************/

			//convert stack into array
			$name_array = explode(",", $name_stack);
			$size_array = explode(",", $size_stack);
			$quantity_array = explode(",", $quantity_stack);
			$costprice_array = explode(",", $costprice_stack);
			$sellingprice_array = explode(",", $sellingprice_stack);


			//add to sales


			//Insert into Sales
			Db::insert("sales",array(
					"user_id" => $user_id,
					"customer_name" => $customer_name,
					"salesman_id" => $salesman_id,
					"salesman_name" => $salesman_name,
					"date" => $date,
					"date_id" => $date_id,
					"time" => time()));

			//check For error
			if(Db::getError()){ //true means error
				$result["message"] = "Failed to insert sales. Try again later";
				$result["return"] = false;
				json($result);
			}else{
				//Get the salesId which is inserted
				$sales_id = Db::lastInsertedId();
			}

			//loop through all the element of the array
			foreach ($name_array as $key => $value) {
				//get item from the array and store them in varaibles
				$name = $name_array[$key];
				$size = $size_array[$key];
				$quantity = $quantity_array[$key];
				$costprice = $costprice_array[$key];
				$sellingprice = $sellingprice_array[$key];

				//Store in sales_product_info
				Db::insert("sales_product_info",  array(
						"sales_id" => $sales_id,
						"user_id" => $user_id,
						"name" => $name,
						"size" => $size,
						"quantity" => $quantity,
						"costprice"=> $costprice,
						"sellingprice" => $sellingprice
				));
			}

			//display success
			$result["message"] = "Sales added successfully";
			$result["return"] = true;
			json($result);
		}
	}else{
		$result["message"] = "Access denied";
		$result["return"] = false;
		json($result);
	}
?>