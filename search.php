<?php
require_once "./core/init.php";
$result = array();
if(isset($_GET['userId']) && isset($_GET['name'])){
	$userId = e($_GET['userId']);
	$name = e($_GET['name']);
    //check size is set
    if(isset($_GET["size"])){
        $size = e($_GET['size']);
    }
    if(isset($_GET['categoryId'])){
        $categoryId = e($_GET['categoryId']);
    }
	//check $userId and product Name is not empty
	if (empty($userId) || empty($name)) {
		$result["message"] = "Fill in all the fields";
		$result["return"] = false;
		json($result); //echo json
	}

	//check userId is valid
    if(!check_user($userId)){
        $result["return"] = false;
        $result["message"] = "Invalid user.";
        json($result);
    }

    //make search term of product name & code
    $searchTerm = $name;

    //make search term clause of size
    $sizeSearchTerm = "";
    if(isset($size)){
        $sizeSearchTerm = "AND `size_keywords` LIKE '%$size%' ";
    }

    $searchs = preg_split("/[\s,]+/", $searchTerm);

    $term_count = 0;
    $q = "SELECT * FROM `product` WHERE ";
    $i = 0;

    //generate query
    foreach ($searchs as $search) {
    	$term_count++;
    	if ($term_count == 1) {
    		$q .= "`keywords` LIKE '%$search%' ";
    	}else{
    		$q .= "AND `keywords` LIKE '%$search%' ";
    	}
    }
    //append sizeSearchTerm to the query
    $q .= $sizeSearchTerm;
    //add categoryId clause if categoryId is set
    if(isset($categoryId)){
        $q .= " AND `category_id`=$categoryId ";
    }

    /*
        add Active product clause
        Because their is not need to fetch that product which is deleted
    */
    $q .= "AND `active`='y'";

    //execute this query and get the results from the database
    $stmt = Db::query($q,array());
    //check we have no error
    if(Db::getError()){
        $result["message"] = "Failed to fetch data";
        $result["return"] = false;
        json($result);
    }

    //SUCCESS
    $count = $stmt->rowCount();
    if($count > 0){ //fetch data because we have some results
        $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result["message"] = "success";
        $result["return"] = true;
        $result["count"] = $count;
        $result["product"] = $fetch;
    }else{ //display no results found message
        $result["message"] = "No Results found";
        $result["return"] = false;
    }

    json($result);
}else{
	$result["message"] = "Access denied";
	$result["return"] = false;
	json($result); //echo json
}