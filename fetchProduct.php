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

    //check user is active or not
    if(!check_user_active($userId)){
        $result["return"] = false;
        $result["message"] = "Dear user! Please pay your bills to reactivate your account.";
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


    //fetch all the product
    $product = Db::fetch("product",array(
        "user_id" => $userId,
        "id" => $productId
    ),array("=","="));


    if(!Db::getError()){
        //create dateTime from time stamp
        $dateTime = date("d/m/y", $product[0]["time"]);

        //fetch Size & quantity
        $q = Db::query("SELECT size,quantity FROM `sq` WHERE user_id=? AND product_id=?",array($userId,$productId));
        $sqResult = $q->fetchAll(PDO::FETCH_ASSOC);

        $sizeArray = array();
        $quantityArray = array();
        //create size and quantity array
        foreach($sqResult as $key => $r){
            $sizeArray[$key] = $r["size"];
            $quantityArray[$key] = $r["quantity"];
        }

        //output the result json
        $result["return"] = true;
        $result["message"] = "Success";
        $result["userId"] = $userId;
        $result["id"] = $product[0]["id"];
        $result["categoryId"] = $product[0]["category_id"];
        $result["name"] = $product[0]["name"];
        $result["image"] = $product[0]["image"];
        $result["code"] = $product[0]["code"];
        $result["size"] = $sizeArray;
        $result["quantity"] = $quantityArray;
        $result["time"] = $dateTime;
        $result["cp"] = $product[0]["CP"];
        $result["sp"] = $product[0]["SP"];
    }else{
        $result["return"] = false;
        $result["message"] = "Failed to fetch products";
    }

    echo json_encode($result);
    exit;

}else{
    $result["message"] = "Access denied";
    $result["return"] = false;
    echo json_encode($result);
    exit;
}