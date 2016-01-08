<?php
require_once "./core/init.php";
$result = array();
if(isset($_GET['userId'])){
    $userId = e($_GET['userId']);

    if(empty($userId)){
        $result["return"] = false;
        $result["message"] = "Fill in all the fields.";
        echo json_encode($result);
        exit;
    }

    //check user account exits
    if(!check_user($userId)){
        $result["return"] = false;
        $result["message"] = "Invalid user.";
        echo json_encode($result);
        exit;
    }

    //count category
    $salesmanCount = Db::rowCount("salesman",array(
            "user_id" => $userId,
            "active" => "y"),array("=","="));

    if($salesmanCount <= 0){
        $result["return"] = true;
        $result["count"] = $salesmanCount;
        $result["message"] = "No salesman added. ";
        echo json_encode($result);
        exit;
    }

    //fetch category list
    $list = Db::fetch("salesman",array(
        "user_id" => $userId,
        "active" => "y"),array("=","="));

    //create a new list
    $newList = array();
    foreach ($list as $key => $value) {
        $newList[$key]["id"] = $value["id"];
        $newList[$key]["name"] = $value["name"];
        $newList[$key]["user_id"] = $value["user_id"];
        $newList[$key]["time"] = $value["time"];
    }

    if(Db::getError()){
        $result["return"] = false;
        $result["message"] = "Failed to load salesmanCount";
    }else{
        $result["return"] = true;
        $result["message"] = "Success";
        $result["category"] = $newList;
    }
    echo json_encode($result);
    exit;
}else{
    $result["return"] = false;
    $result["message"] = "Access denied.";
    echo json_encode($result);
    exit;
}