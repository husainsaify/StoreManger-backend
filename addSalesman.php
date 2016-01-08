<?php
    require_once "./core/init.php";
    $result = array();

    if(isset($_POST['salesman']) && isset($_POST['userid'])){
        $salesman = e($_POST['salesman']);
        $userId = e($_POST['userid']);

        //check fields are not empty
        if(empty($salesman) || empty($userId)){
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

        //check length of the category name
        if(strlen($salesman) <= 3){
            $result["return"] = false;
            $result["message"] = "Name should be more then 3 characters.";
            echo json_encode($result);
            exit;   
        }

        //category name should not be more then 10 char
        if(strlen($salesman) > 20){
            $result["return"] = false;
            $result["message"] = "Name should not be more then 20 characters.";
            echo json_encode($result);
            exit;
        }

        //check category already extists or not
        if(!check_salesman_exits($salesman,$userId)){
            $result["return"] = false;
            $result["message"] = "Name already exists.";
            echo json_encode($result);
            exit;
        }

        $time =  time();
        //insert category
        $insert = Db::insert("salesman",array(
            "name" => $salesman,
            "user_id" => $userId,
            "time" => $time
        ));

        //succes
        if(!Db::getError()){
            $result["return"] = true;
            $result["message"] = "Salesman added successfully.";
        }else{ //erro
            $result["return"] = false;
            $result["message"] = "Failed to add Salesman.";
        }
        echo json_encode($result);
        exit;

    }else{
        $result["return"] = false;
        $result["message"] = "Access denied.";
        echo json_encode($result);
        exit;
    }
?>