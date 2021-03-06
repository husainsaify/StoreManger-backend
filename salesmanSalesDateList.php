<?php
	require_once "./core/init.php";
	$result = array();
	if(isset($_POST['userId']) && isset($_POST["salesmanId"])){
		$user_id = e($_POST['userId']);
		$salesman_id = e($_POST["salesmanId"]);

		//check user id is valid
		if(empty($user_id) && isset($salesman_id)){
			$result["message"] = "Fill in all the fields";
			$result["return"] = false;
			$result["count"] = -1;
			json($result);
		}

		//check user id is valid
		if (!check_user($user_id)) {
			$result["message"] = "Invalid user";
			$result["return"] = false;
			$result["count"] = -1;
			json($result);
		}

		//If salesman id does not belongs to the user
		if(!check_salesman_id_is_valid($salesman_id,$user_id)){
			$result["message"] = "Invalid salesman, This salesman does not belongs to you";
			$result["return"] = false;
			$result["count"] = -1;
			json($result);
		}

		//get date list
		$q = Db::query("SELECT `date`,`date_id` FROM `sales` WHERE user_id=? AND salesman_id=? AND active='y' ORDER BY `id` DESC",array($user_id,$salesman_id));
		
		//Count and check if user has added anysales or not
		$count = $q->rowCount();

		//check if is their any date to fetch or not
		$dateArray = array(); //varaible to store data & date_id
		if($count > 0){
			$rawdata = $q->fetchAll(PDO::FETCH_ASSOC);

			//filter $rawdata and display date only one time
			$dateArrayCheck = array();
			$dateArray = array();
			$key = 0;
			foreach($rawdata as $data){
				$date = $data['date'];
				$date_id = $data['date_id'];

				//if date not exits in $dateArrayCheck
				if(!in_array($date,$dateArrayCheck)){
					//add to $dateArrayCheck
					$dateArrayCheck["date"] = $date;
					//add Date & date_id into $dateArray
					$dateArray[$key]["date"] = $date;
					$dateArray[$key]["date_id"] = $date_id;
					$key++;
				}
			}
		}

		if(!Db::getError()){
			$result["return"] = true;
			$result["message"] = "Success";
			$result["count"] = $count;
			$result["data"] = $dateArray;
		}else{
			$result["return"] = false;
			$result["message"] = "Failed to get date. Try again later";
			$result["count"] = -1;
		}
		json($result);
	}else{
		$result["message"] = "Access denied";
		$result["return"] = false;
		$result["count"] = -1;
		json($result);
	}
?>