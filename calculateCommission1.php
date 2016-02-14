<?php
require_once "./core/init.php";
$result = array();
if (isset($_POST["userId"]) && isset($_POST["fromDate"]) && isset($_POST["toDate"]) && isset($_POST["salesmanId"])) {
    //escape variables
    $user_id = e($_POST["userId"]);
    $from_date = e($_POST["fromDate"]);
    $to_date = e($_POST["toDate"]);
    $salesman_id = e($_POST["salesmanId"]);

    //check fields are not empty
    if(empty($user_id) || empty($from_date) || empty($to_date) || empty($salesman_id)){
        $result["message"] = "Fill in all the fields";
        $result["return"] = false;
        json($result);
    }

    //check user account exits
    if(!check_user($user_id)){
        $result["message"] = "Invalid user.";
        $result["return"] = false;
        json($result);
    }

    //check that this salesman id belongs to the particular user
    if(!check_salesman_id_is_valid($salesman_id,$user_id)){
        $result["message"] = "Invalid salesman. Salesman does belongs to your account";
        $result["return"] = false;
        json($result);
    }

    //check salesman has sold something or not
    if(!check_salesman_has_done_any_sales($salesman_id)){
        $result["message"] = "No sales found for this Salesman";
        $result["return"] = false;
        json($result);
    }

    //fetch all the sales
    $all_sales_fetch = Db::fetch("sales",array(
        "user_id" => $user_id,
        "salesman_id" => $salesman_id
    ),array("=","="),"DESC");

    //explode from & to date and get Day month and week
    $from_date_array = explode("/",$from_date);
    $to_date_array = explode("/",$to_date);
    //from
    $from_date_day = $from_date_array[0];
    $from_date_month = $from_date_array[1];
    $from_date_year = $from_date_array[2];
    //to
    $to_date_day = $to_date_array[0];
    $to_date_month = $to_date_array[1];
    $to_date_year = $to_date_array[2];


    //array to store all the sales id which are between FROM & To date
    $sales_id_array = array();
    $total_item_sold_to_customers = 0;

    //Fetch thought all the sales and get the sales which is between from and to date
    //and store its sales id in $sales_id_array
    foreach($all_sales_fetch as $sales_fetch){

        $date_array = explode("/",$sales_fetch["date"]);
        $current_date_day = $date_array[0];
        $current_date_month = $date_array[1];
        $current_date_year = $date_array[2];

        echo "current ".$current_date_day."/".$current_date_month."/".$current_date_year."<br>";
        echo "from ".$from_date_day."/".$from_date_month."/".$from_date_year."<br>";
        echo "to ".$to_date_day."/".$to_date_month."/".$to_date_year."<br>";

        $current_sales_id = $sales_fetch["id"];

        //check Current year is Greater then Equal FROM year AND smaller then equal To year
        /*filter_var($current_date_year,FILTER_VALIDATE_INT,array(
                    'options' => array(
                        'min_range' => $from_date_year, 
                        'max_range' => $to_date_year
                    )
            ))*/
    //($current_date_year >= $from_date_year) && ($current_date_year <= $to_date_year)
    //($current_date_month >= $from_date_month) && ($current_date_month <= $to_date_month)
    //($current_date_day >= $from_date_day) && ($current_date_day <= $to_date_day)
        if(($from_date_year <= $current_date_year) && ($current_date_year <= $to_date_year)){
            echo "yes year<br>";
            //check Current month is Greater then Equal From Month & smaller then Equal TO month
            if(($from_date_month <= $current_date_month) && ($current_date_month <= $to_date_month)){
                echo "yes month<br>";
                //Check Current_day is From date & smaller then To date
                if(($from_date_day <= $current_date_day) && ($current_date_day <= $to_date_day)){
                    echo "yes day<br>";
                    //Store SALES_ID in Sales_id_array
                    $sales_id_array[] = $current_sales_id;

                    //increment $total_item_sold_to_customers
                    $total_item_sold_to_customers++;
                }else{
                    echo "no day<br>";
                }
            }else{
                echo "no month<br>";
            }
        }else{
            echo "no year<br>";
        }

        echo "<hr>";
    }
    exit();

    //FETCH sales info with the help of sales_id which is stored in sales_id_array

    //check Sales_id_array is not empty
    if(count($sales_id_array) >= 1){
        //Varaiable to store Total costprice , Total selling price, total quantity
        $total_costprice = 0;
        $total_sellingprice = 0;
        $total_quanity = 0;
        //Get all the sales
        foreach($sales_id_array as $sales_id){
            $sql = "SELECT `quantity`,`costprice`,`sellingprice` FROM `sales_product_info` WHERE `sales_id`=? AND `user_id`=? AND `active`='y'";
            $stmt = Db::query($sql,array($sales_id,$user_id));
            $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Get costprice , selling price & quantity
            foreach($fetch as $r){
                $total_costprice += intval($r["costprice"] * $r["quantity"]);
                $total_sellingprice += intval($r["sellingprice"] * $r["quantity"]);
                $total_quanity += $r["quantity"];
            }
        }

        //show response
        if(!Db::getError()){
            $result["message"] = "success";
            $result["return"] = true;
            $result["total_costprice"] = $total_costprice;
            $result["total_sellingprice"] = $total_sellingprice;
            $result["total_item_sold"] = $total_quanity;
            $result["total_item_sold_to_customers"] = $total_item_sold_to_customers;
        }else{
            $result["message"] = "Failed to calculate commission. Try again later";
            $result["return"] = true;
        }
        json($result);
    }else{
        $result["message"] = "No Sales done by this salesman between {$from_date} to {$to_date}";
        $result["return"] = false;
        json($result);
    }

} else {
    $result["message"] = "Access Denied";
    $result["return"] = false;
    json($result);
}