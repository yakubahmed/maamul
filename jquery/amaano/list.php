<?php 
session_start();

include('../../inc/config.php');

date_default_timezone_set('Africa/Nairobi');


// Getting amano edit information
if(isset($_POST['editAmaanoInfo'])){
    $id = mysqli_real_escape_string($con, $_POST['editAmaanoInfo']); 
    
    $stmt = "SELECT * FROM amaano WHERE amano_id = $id";
    $result = mysqli_query($con, $stmt); 
    $row = mysqli_fetch_assoc($result);
    
    $cust =  $row['customer_id'];
    $atype = $row['type'];

    $date = date("Y-m-d", strtotime($row['tran_date']));

    $aname = $row['name'];
    $qty = $row ['total'];
    $desc = $row['description'];

// ---------  Customer dropdown ----------------- //
    echo "<div class='form-group col-md-4'>
            <label for=''>Macaamiilka</label>
            <input type='hidden' name='editAmano' id='editAmano' value='$id'>
            <select name='customer' id='customer' class='form-control customer' data-toggle='selectpicker' required data-live-search='true' >";
    $query = mysqli_query($con, "SELECT * FROM customer WHERE customer_id = $cust");
    $customer = mysqli_fetch_assoc($query);
    echo "
                <option data-tokens='' value='" . $customer['customer_id'] ."'> " . $customer['cust_name'] . " </option>

    ";
    $query = mysqli_query($con, "SELECT * FROM customer WHERE customer_id != $cust AND customer_id != 29");
    while($customer = mysqli_fetch_assoc($query)){
        echo "
                    <option data-tokens='' value='" . $customer['customer_id'] ."'> " . $customer['cust_name'] . " </option>
        ";
    }
    echo "      </select>
            </div>
        </div>";

// ---------  Amaano type dropdown ----------------- //
    echo "<div class='form-group col-md-4'>
            <label for=''>Nooca amaanada</label>
            <select name='amaanot' id='amaanot' class='form-control customer' data-toggle='selectpicker' required data-live-search='true' >";
    $query = mysqli_query($con, "SELECT * FROM amaano_type WHERE amanotid = $atype");
    $amaanot = mysqli_fetch_assoc($query);
    echo "
                <option data-tokens='' value='" . $amaanot['amanotid'] ."'> " . $amaanot['name'] . " </option>

    ";
    $query = mysqli_query($con, "SELECT * FROM amaano_type WHERE amanotid != $atype");
    while($amaanot = mysqli_fetch_assoc($query)){
        echo "
                    <option data-tokens='' value='" . $amaanot['amanotid'] ."'> " . $amaanot['name'] . " </option>
        ";
    }
    echo "      </select>
            </div>
        </div>";




    echo "
        <div class='form-group col-md-4'
        <label for=''>Taarikh</label>
            <input type='date' name='adate' value='$date' id='adate'class='form-control' >
        </div>

        <div class='form-group col-md-4'>
            <label for=''>Sheyga amaanada</label>
            <input type='text' name='aname' id='aname' value='$aname' class='form-control' required>
        </div>

        <div class='form-group col-md-3'>
            <label for=''>Tirada</label>
            <input type='text' name='qty' id='qty' value='$qty' class='form-control' required>
        </div>

        <div class='form-group col-md-5'>
            <label for=''>Faahfaahin</label>
            <input type='text' name='desc' id='desc' value='$desc' class='form-control' required>
        </div>

        <div class='form-group col-md-12'>
            <center>
                <button type='submit' class='btn btn-info rounded-6'>Ii Keydi xogta</button>
            </center>
        </div>

    
    ";


}


if(isset($_POST['editAmano'])){
    $id = mysqli_real_escape_string($con, $_POST['editAmano']); 
    $cust =  mysqli_real_escape_string($con, $_POST['customer']);
    $atype = mysqli_real_escape_string($con, $_POST['amaanot']);
    $date = mysqli_real_escape_string($con, $_POST['adate']);
    $aname = mysqli_real_escape_string($con, $_POST['aname']);
    $qty = mysqli_real_escape_string($con, $_POST['qty']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);

    $stmt = "UPDATE amaano SET customer_id = $cust, name = '$aname', tran_date = '$date', type = '$atype', total = '$qty', description = '$desc' WHERE amano_id = $id";
    $result = mysqli_query($con, $stmt); 
    if($result){
        echo "success";
    }
}


if(isset($_POST['delAmaano'])){
    $id = mysqli_real_escape_string($con, $_POST['delAmaano']);

    $stmt = "DELETE FROM amaano WHERE amano_id = $id";
    $result = mysqli_query($con, $stmt); 
    if($result){
        echo "deleted";
    }
}


?>