<?php
require_once "./core/init.php";
$result = array();
if (isset($_POST["categoryId"]) && isset($_POST["userId"]) && isset($_POST["categoryName"])) {
    //Escape variables
    $categoryId = e($_POST["categoryId"]);
    $userId = e($_POST["userId"]);
    $newCategoryName = e($_POST["categoryName"]);

    //empty
    if (empty($categoryId) || empty($userId) || empty($newCategoryName)) {
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

    //check category name is not too small
    if(strlen($newCategoryName) <= 3){
        $result["message"] = "Category name should be more then 3 characters.";
        $result["return"] = false;
        echo json_encode($result);
        exit;
    }

    //category name should not be to long
    if(strlen($newCategoryName) > 20){
        $result["return"] = false;
        $result["message"] = "Category name should not be more then 20 characters.";
        echo json_encode($result);
        exit;
    }

    //Update category name
    Db::update("category",array("name" => $newCategoryName),array("id","=",$categoryId," AND ","user_id","=",$userId," AND ","active","=","'y'"));

    //check the query was successful or their was some error
    if(!Db::getError()){ //success
        $result["message"] = "Category name updated successfully";
        $result["return"] = true;
    }else{ //error
        $result["message"] = "Failed to updated category name";
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