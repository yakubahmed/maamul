<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

// Set proper headers for AJAX requests
header('Content-Type: application/json');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['accname'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $name = mysqli_real_escape_string($con, $_POST['accname']);
    $num  = mysqli_real_escape_string($con, $_POST['accnum'] ) ;
    $desc  = mysqli_real_escape_string($con, $_POST['accdes'] ) ;

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');


    // Validate input
    if(empty(trim($name)) || empty(trim($num))){
        echo json_encode(array('error' => 'empty_fields'));
        exit;
    }

    $stmt = "SELECT * FROM account WHERE account_number = '$num' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        echo json_encode(array('error' => 'found_accnum'));
    }else{
        $stmt = "SELECT COUNT(*) FROM account "; 
        $result = mysqli_query($con, $stmt); 
        $row = mysqli_fetch_array($result);
        $serial  = "ACC-" . 0000 + $row[0]  + 1 ;

        $stmt = "INSERT INTO account(acc_ser, account_name, account_number, description, reg_date, reg_by, warehouse )";
        $stmt .= " VALUES ('$serial', '$name', '$num', '$desc', '$date', '$userid', 1)";
        $result = mysqli_query($con, $stmt); 
        if($result){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
        }
    }


}

//Delete customer
if(isset($_POST['del_acc'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['del_acc'];
    $stmt = "DELETE FROM account WHERE account_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_error', 'message' => mysqli_error($con)));
    }
}


if(isset($_POST['editSingleAcc'])){
    $id = $_POST['editSingleAcc'];
    $stmt = "SELECT * FROM account WHERE account_id = $id";
    $result = mysqli_query($con, $stmt); 
    while($row = mysqli_fetch_assoc($result)){
        //$cust_id = $row['account_id'];
        $name = $row['account_name'];
        $number = $row['account_number'];
        $desc = $row['description'];
        $date = date("Y-m-d", strtotime($row['reg_date']));

        

        echo "
        <input type='hidden' name='account_id' id='account_id' value='$id'>
        <div class='form-group col-md-6'>
            <label for=''> Account name *</label>
            <input type='text' name='accname1' id='accname1' value='$name' maxlength='50' class='form-control rounded-0' autocomplete='off' required>
        </div>

        <div class='form-group col-md-6'>
            <label for=''> Account number *</label>
            <input type='number' name='accnum1' id='accnum1' value='$number' maxlength='30' class='form-control  rounded-0'  autocomplete='off' required>
        </div>


        <div class='form-group col-md-12'>
            <label for=''> Description</label>
            <textarea name='accdes1' id='accdes1' class='form-control rounded-0'>$desc</textarea>
        </div>

        <div class='form-group col-md-6'>
            <button type='submit' class='btn btn-info rounded-0'>Save account</button>
        </div>

       
        ";
    }

}

if(isset($_POST['account_id'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $id = $_POST['account_id'];
    $name = mysqli_real_escape_string($con, $_POST['accname1']);
    $num  = mysqli_real_escape_string($con, $_POST['accnum1'] ) ;
    $desc  = mysqli_real_escape_string($con, $_POST['accdes1'] ) ;

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    $sql = "SELECT * FROM account WHERE account_id = $id";
    $re = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($re);
    $curr_accnum = $ro['account_number'];


    // Validate input
    if(empty(trim($name)) || empty(trim($num))){
        echo json_encode(array('error' => 'empty_fields'));
        exit;
    }

    if($curr_accnum != $num){
        $stmt = "SELECT * FROM account WHERE account_number = '$num'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_accnum'));
        }else{
            $stmt = "UPDATE account SET account_name = '$name', account_number = '$num', description = '$desc'
            WHERE account_id = $id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }else{
                echo json_encode(array('error' => 'update_error', 'message' => mysqli_error($con)));
            }

        }
    }else{
        $stmt = "UPDATE account SET account_name = '$name', account_number = '$num', description = '$desc'
        WHERE account_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('error' => 'update_error', 'message' => mysqli_error($con)));
        }
    }




}

?>