<?php
require_once "core/init.php";
$result = array();

if(isset($_POST["fullname"]) && isset($_POST["storename"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["password"])){
	$name = e($_POST["fullname"]);
	$storename = e($_POST["storename"]);
	$email = e($_POST["email"]);
	$phone = e($_POST["phone"]);
	$password = e($_POST["password"]);
	$time = time();

	if(empty($name) && empty($storename) && empty($phone) && empty($email) && empty($phone) && empty($password)){
		$result["message"] = "Fill in all the field";
		$result["return"] = false;
		json($result);
	}

	//check email is 
	if(!check_email_is_unique($email)){
		$result["message"] = "Email already register! Try login";
		$result["return"] = false;
		json($result);	
	}

	$hash_password = password_hash($password,PASSWORD_DEFAULT);

	//insert into Db
	Db::insert("user",array(
		"name" => $name,
		"storename" => $storename,
		"email" => $email,
		"phone" => $phone,
		"password" => $hash_password,
		"register_at" => $time,
		"active" => "y"
	));

	if(!Db::getError()){
		$result["message"] = "Success";
		$result["return"] = true;
	}else{
		$result["message"] = "Failed to register user";
		$result["return"] = false;
	}

	json($result);
}else{
	$result["message"] = "Access denied";
	$result["return"] = false;
	json($result);
}