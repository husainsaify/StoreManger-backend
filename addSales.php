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

		//check any stuff is not empty
		if (empty($user_id) || empty($customer_name) || empty($name_stack) || empty($size_stack) || empty($quantity_stack) || empty($costprice_stack) || empty($sellingprice_stack) || empty($salesman_id) || empty($salesman_name)) {
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

		//convert stack into array
		$name_array = explode(",", $name_stack);
		$size_array = explode(",", $size_stack);
		$quantity_array = explode(",", $quantity_stack);
		$costprice_array = explode(",", $costprice_stack);
		$sellingprice_array = explode(",", $sellingprice_stack);


		//add to sales
		$date = date("d:m:Y");
		$date_id = date("dmY");

		//loop through all the element of the array
		foreach ($name_array as $key => $value) {
			//get item from the array and store them in varaibles
			$name = $name_array[$key];
			$size = $size_array[$key];
			$quantity = $quantity_array[$key];
			$costprice = $costprice_array[$key];
			$sellingprice = $sellingprice_array[$key];

			echo "name ".$name."<br>";
			echo "name ".$size."<br>";
			echo "name ".$quantity."<br>";
			echo "name ".$costprice."<br>";
			echo "name ".$sellingprice."<br>";
		}

		/*//display success
		$result["message"] = "success";
		$result["return"] = true;
		json($result);*/
	}else{
		$result["message"] = "Access denied";
		$result["return"] = false;
		json($result);
	}
?>