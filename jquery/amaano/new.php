<?php 
session_start();

include('../../inc/config.php');

date_default_timezone_set('Africa/Nairobi');



//Adding new customer
if(isset($_POST['new-customer'])){

    $stmt = "SELECT * FROM customer where customer_id != 29 ORDER BY customer_id DESC";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = array("id" => $row["customer_id"], "name" => $row["cust_name"]);
    }
    
    echo json_encode(["data" => $data]);

}

// Adding amaano type 
if(isset($_POST['new-amaanot'])){

    $stmt = "SELECT * FROM amaano_type  ORDER BY amanotid DESC";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = array("id" => $row["amanotid"], "name" => $row["name"]);
    }
    
    echo json_encode(["data" => $data]);

}


//Adding new amaanno 

if(isset($_POST['customer'])){

    $cus = mysqli_real_escape_string($con, $_POST['customer']);
    $amaanot = mysqli_real_escape_string($con, $_POST['amanotype']);
    $adate = mysqli_real_escape_string($con, $_POST['adate']);
    $name = mysqli_real_escape_string($con, $_POST['aname']);
    $qty = mysqli_real_escape_string($con, $_POST['qty']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    $stmt = "INSERT INTO amaano (customer_id, name, amano_date, tran_date, type, total, given, remain, description, warehouse, createdby ) 
    VALUES ($cus, '$name', '$adate', '$date', '$amaanot', $qty, 0, $qty, '$desc', 1, $userid)";
    $result = mysqli_query($con,$stmt);
    if($result){
        echo "success";
    }



}





?>