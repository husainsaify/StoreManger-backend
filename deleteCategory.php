<?php
require_once "./core/init.php";
$result = array();
if (isset($_POST["categoryId"]) && isset($_POST["userId"])) {
    //Escape variables
    $categoryId = e($_POST["categoryId"]);
    $userId = e($_POST["userId"]);

    //empty
    if (empty($categoryId) || empty($userId)) {
        $result["message"] = "Fill in all the fields";
        $result["return"] = false;
    }

    //check user account exits
    if(!check_user($userId)){
        $result["message"] = "Invalid user.";
        $result["return"] = false;
        echo json_encode($result);
        exit;
    }

    //check category name exits
    if(!check_category_is_valid($categoryId,$userId)){
        $result["message"] = "Invalid Category: This category does not belongs to you";
        $result["return"] = false;
        echo json_encode($result);
        exit;
    }

    //Update category name
    Db::update("category",array("active" => "n"),array("id","=",$categoryId," AND ","user_id","=",$userId));

    //check the query was successful or their was some error
    if(!Db::getError()){ //success

        // Delete all the product which are child of this category
        Db::update("product",array("active" => "n"),array("category_id","=",$categoryId," AND ","user_id","=",$userId));

        $result["message"] = "Category deleted successfully";
        $result["return"] = true;
    }else{ //error
        $result["message"] = "Failed to deleted category";
        $result["return"] = false;
    }
    echo json_encode($result);
    exit;


}else{
    $result["message"] = "Access Denied";
    $result["return"] = false;
    echo json_encode($result);
    exit;
}