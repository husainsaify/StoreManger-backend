<?php
	require_once "./core/init.php";
	$result = array();
	if(isset($_POST["user_id"]) && isset($_POST["date_id"])){
		$user_id = e($_POST["user_id"]);
		$date_id = e($_POST["date_id"]);

		//check 
		if(empty($user_id) && empty($date_id)){
			$result["message"] = "Fill in all the fields";
			$result["return"] = true;
			json($result);
		}

		//check user id is valid
		if (!check_user($user_id)) {
			$result["message"] = "Invalid user";
			$result["return"] = false;
			json($result);
		}

		//calculate total sales of today
		$q = Db::query("SELECT 
						sell.id AS sell_id,sell.quantity,sell.price_per_q,product.id AS product_id,product.image,product.name,product.code,product.CP AS cp,product.SP AS sp
						FROM sell 
						INNER JOIN product 
						ON sell.product_id = product.id 
						WHERE sell.user_id = ? AND sell.date_id = ?",array($user_id,$date_id));
		$rowCount = $q->rowCount();
		if($rowCount > 0){
			//fetch all the stuff
			$fetch = $q->fetchAll(PDO::FETCH_ASSOC);
			$total_sale = 0;
			$total_CP  = 0;
			$product = array();
			foreach ($fetch as $key => $value) {
				//cal total sales
				$total_sale += $value["quantity"] * $value["price_per_q"];
				//cal cost price
				$total_CP += $value["quantity"] * $value["cp"];
				//cal sales of current product
				$current_sale = $value["quantity"] * $value["price_per_q"];
				//cal cp of current product
				$current_cp = $value["quantity"] * $value["cp"];
				//add into Product
				$product[$key]["sell_id"] = $value["sell_id"];
				$product[$key]["quantity"] = $value["quantity"];
				$product[$key]["price_per"] = $value["price_per_q"];
				$product[$key]["product_id"] = $value["product_id"];
				$product[$key]["product_image"] = $value["image"];
				$product[$key]["name"] = $value["name"];
				$product[$key]["code"] = $value["code"];
				$product[$key]["cp"] = $value["cp"];
				$product[$key]["sp"] = $value["sp"];
				$product[$key]["current_sales"] = $current_sale."";
				$product[$key]["current_cp"] = $current_cp."";
			}
			$result["message"] = "Success";
			$result["return"] = true;
			$result["data"] = $product;
			$result["total_sales"] = $total_sale."";
			$result["total_cp"] = $total_CP."";
		}else{
			$result["message"] = "No Result found";
			$result["return"] = false;
		}
		json($result);
		
	}else{
		$result["message"] = "Access denied";
		$result["return"] = false;
		json($result);
	}
?>