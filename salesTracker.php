<?php
	require_once "./core/init.php";
	$result = array();
	if($_GET["user_id"] && $_GET["date_id"]){
		$user_id = e($_GET["user_id"]);
		$date_id = e($_GET["date_id"]);
	}else{
		$result["message"] = "Access denied";
		$result["return"] = false;
		json($result);
	}
?>