<?php
    /*
     * Error code
     * 1 = access deneid
     * 2 = fill in all the fields
     * 3 =
     * */
    require_once "./core/init.php";
    $result = array();
    if(isset($_POST['email']) && isset($_POST['password'])){
        //escape value
        $email = e($_POST["email"]);
        $password = e($_POST['password']);

        if(empty($email) || empty($password)){
            $result["message"] = "Fill in all the fields";
            $result["return"] = false;
            echo json_encode($result);
            exit;
        }
        
        //check email is valid
        $count = Db::rowCount("user",array(
            "email" => $email
        ),array("="));

        //if one user exits
        if($count == 1){
            //fetch results and display
            $detail = Db::fetch("user",array(
                "email" => $email
            ),array("="));

            //store the hash password
            $hash = $detail[0]["password"];

            //check the hash match the password
            if (password_verify($password, $hash)) {
                if(db::getError() == true){
                    $result["message"] = "Query failed";
                    $result["return"] = false;
                }else{
                    $result["message"] = "success";
                    $result["return"] = true;
                    $result["user"] = $detail;
                } 
                json($result);
            } else {
                $result["message"] = "Invalid password";
                $result["return"] = false;
                json($result);
            }
        }else{
            $result["message"] = "Invalid email address";
            $result["return"] = false;
            echo json_encode($result);
            exit;
        }

    }else{
        $result["message"] = "Access Denied";
        $result["return"] = false;
        echo json_encode($result);
        exit;
    }