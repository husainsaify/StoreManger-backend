<?php
	require_once "./core/init.php";
	$result = array();
	if(isset($_POST["userId"]) && isset($_POST["date_id"])){
		$user_id = e($_POST["userId"]);
		$date_id = e($_POST["date_id"]);


		//Store salesmanId if its present
		if(isset($_POST["salesmanId"]) && !empty($_POST["salesmanId"])){
			$salesman_id = e($_POST["salesmanId"]);
		}

		//check 
		if(empty($user_id) && empty($date_id)){
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

		/*
			IF SALESMAN ID IS PRESENT
		*/
		if(!empty($salesman_id)){
			//check salesMan belongs to the user
			if(!check_salesman_id_is_valid($salesman_id,$user_id)){
				$result["message"] = "Invalid salesman, This salesman does not belongs to you";
				$result["return"] = false;
				json($result);
			}

			/*
				Check date_id
				if date_id is ALL (fetch all the sales of every day)
				else date_id is specific fetch data of that day specific
			*/
			if($date_id == "all"){
				//fetch all the sales of this salesman 
				$q = Db::query("SELECT `id` as sales_id,`customer_name`,`salesman_id`,`salesman_name`,`sales_type`,`time` FROM `sales` WHERE `user_id`=? AND salesman_id=? AND `active`='y' ORDER BY `id` DESC",array($user_id,$salesman_id));
			}else{
				//fetch sales from specific date_id 
				$q = Db::query("SELECT `id` as sales_id,`customer_name`,`salesman_id`,`salesman_name`,`sales_type`,`time` FROM `sales` WHERE `user_id`=? AND `date_id`=? AND salesman_id=? AND `active`='y' ORDER BY `id` DESC",array($user_id,$date_id,$salesman_id));
			}
		}
		/*
			SALESMAN ID IS NOT PRESENT
		*/
		else{
			//calculate total sales 
			$q = Db::query("SELECT `id` as sales_id,`customer_name`,`salesman_id`,`salesman_name`,`sales_type`,`time` FROM `sales` WHERE `user_id`=? AND `date_id`=? AND `active`='y' ORDER BY `id` DESC",array($user_id,$date_id));
		}

		$sales_row_count = $q->rowCount();
		if($sales_row_count > 0){
			//fetch all the stuff
			$sales_fetch = $q->fetchAll(PDO::FETCH_ASSOC);

			//array to store all the sales information
			$sales_array = array();
			//loop through the $sales_fetch
			$total_costprice = 0;
			$total_sellingprice = 0;
			foreach($sales_fetch as $key => $sales){
				//get the sales_id
				$sales_id = $sales["sales_id"];

				//generate customer name if not entered put N/A
				$customer_name = $sales["customer_name"];
				if(empty($customer_name)){
					$customer_name = "N/A";
				}

				//Fetch sale product info From sales_id
				$info_q = Db::query("SELECT `product_id`,`name` as product_name,`product_code`,`size`,`quantity`,`costprice`,`sellingprice` FROM `sales_product_info` WHERE `sales_id`=? AND `active`='y'",array($sales_id));
				//get the row count for the Sales_product_info
				$sales_product_count = $info_q->rowCount();

				$sales_info_fetch = array();
				//Fetch sales product info only when Sales_product_info count is more then zero
				if($sales_product_count > 0){
					//Fetch sales_product_info
					$sales_info_fetch = $info_q->fetchAll(PDO::FETCH_ASSOC);

					//loop through $sales_info_fetch & cal Total costprice & sellingprice
					foreach($sales_info_fetch as $info){
						$current_costprice = intval($info["costprice"]);
						$current_sellingprice = intval($info["sellingprice"]);

						//get quanity
						$q = intval($info["quantity"]);

						//multiple price with quanity
						$total_costprice += ($q * $current_costprice);
						$total_sellingprice += ($q * $current_sellingprice);
					}
				}
			

				//store info in array
				$sales_array[$key]["sales_id"] = $sales_id;
				$sales_array[$key]["customer_name"] = $customer_name;
				$sales_array[$key]["salesman_id"] = $sales["salesman_id"];
				$sales_array[$key]["salesman_name"] = $sales["salesman_name"];
				$sales_array[$key]["time"] = $sales["time"];
				$sales_array[$key]["data"] = $sales_info_fetch;
			}

			//display info only when sales_product_info has product
			if (!empty($sales_info_fetch) ) {
				$result["message"] = "Success";
				$result["return"] = true;
				$result["total_costprice"] = $total_costprice;
				$result["total_sellingprice"] = $total_sellingprice;
				$result["sales"] = $sales_array;
			}else{
				$result["message"] = "No sales found for this date";
				$result["return"] = false;
			}
			
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