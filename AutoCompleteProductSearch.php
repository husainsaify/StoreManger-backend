<?php
	header('Content-Type: application/json');
	require_once "./core/init.php";
	$result = array();

	if(isset($_POST['productName']) && isset($_POST['userId'])){
		$s = e($_POST["productName"]);
		$user_id = e($_POST['userId']);

		$q = "SELECT id,name,code,CP FROM `product` WHERE ";
		$term_count=0;
		$searchs = explode(" ", $s);

		foreach ($searchs as $search) {
			$term_count++;
			if($term_count == 1){
				$q .= "keywords LIKE '%$search%' ";
			}else{
				$q .= "AND keywords LIKE '%$search%' ";
			}
		}

		$q .= "AND user_id='$user_id' AND active='y'";
		//execute query
		$stmt = Db::query($q,array());
		//get the result count
		$count = $stmt->rowCount();

		//if count is > 0
		$fetch = array();
		if($count > 0){
			//fetch result from the database
			$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

			/****** FETCH SIZE OF PRODUCT  **********/
			foreach($fetch as $key => $f){

				//store product id
				$productId = $f["id"];

				//fetch sizes from the database
				$q = "SELECT `size` FROM `sq` WHERE product_id=? AND active='y'";

				$sizeStmt = Db::query($q,array($productId));

				$sizeFetch = $sizeStmt->fetchAll(PDO::FETCH_COLUMN);

				$sizeStack = "";
				//CREATE A SIZE STACK
				$i = 1;
				foreach($sizeFetch as $size){
					$sizeStack .= $size;

					if($i < count($sizeFetch)){
						$sizeStack .= ",";
					}
					$i++;
				}

				//Append to $fetch
				$fetch[$key]["size"] = $sizeStack;
			}
		}

		$result["message"] = "success";
		$result["return"] = true;
		$result["count"] = $count;
		$result["data"] = $fetch;

		json($result);
	}else{
		$result["message"] = "Access denied";
		$result["return"] = false;
		$result["count"] = 0;
		json($result);
	}
?>