<?php
require_once "./core/init.php";
$result = array();
if(isset($_POST["userId"]) && isset($_POST["salesId"])){

    //escape
    $userId = e($_POST["userId"]);
    $salesId = e($_POST["salesId"]);

    //check no empty
    if(empty($userId) || empty($salesId)){
        $result["message"] = "Invalid input";
        $result["return"] = false;
        json($result);
    }

    //check user account exits
    if(!check_user($userId)){
        $result["return"] = false;
        $result["message"] = "Invalid user.";
        json($result);
    }

    //check Product is valid and belong to the user
    if(!check_sales_id_valid($salesId,$userId)){
        $result["return"] = false;
        $result["message"] = "Invalid Sales. This sales does not belongs to you";
        json($result);
    }


    //Delete (Update) sales
    Db::update("sales",array(
        "active" => "n"
    ),array("id","=",$salesId," AND ","user_id","=",$userId));   

    //delete update sales_product_info
    Db::update("sales_product_info",array(
        "active" => "n"
    ),array("sales_id","=",$salesId," AND ","user_id","=",$userId)); 

    if(!Db::getError()){
        //success
        $result["message"] = "Success";
        $result["return"] = true;
    }else{
        //error
        $result["message"] = "Failed to delete sales";
        $result["return"]= false;
    }
    
    json($result);

}else{
    $result["message"] = "Access denied";
    $result["return"] = false;
    json($result);
}