<?php
require_once "./core/init.php";
$result = array();
if(isset($_POST["userId"]) && isset($_POST["productId"])){

    //escape
    $userId = e($_POST["userId"]);
    $productId = e($_POST["productId"]);

    //check no empty
    if(empty($userId) || empty($productId)){
        $result["message"] = "Invalid input";
        $result["return"] = false;
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

    //check Product is valid and belong to the user
    if(!check_productId_is_valid($productId,$userId)){
        $result["return"] = false;
        $result["message"] = "Invalid Product Id";
        echo json_encode($result);
        exit;
    }


    //Delete (Update) size and Quanity
    Db::update("sq",array(
        "active" => "n"
    ),array("product_id","=",$productId," AND ","user_id","=",$userId));    

    //delete the product (update)
    Db::update("product",array(
        "active" => "n"
    ),array("id","=",$productId));

    if(!Db::getError()){
        //success
        $result["message"] = "Success";
        $result["return"] = true;
    }else{
        //error
        $result["message"] = "Failed to delete product";
        $result["return"]= false;
    }
    
    echo json_encode($result);
    exit;

}else{
    $result["message"] = "Access denied";
    $result["return"] = false;
    echo json_encode($result);
    exit;
}