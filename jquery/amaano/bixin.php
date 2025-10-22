<?php 
session_start();

include('../../inc/config.php');

date_default_timezone_set('Africa/Nairobi'); 

if(isset($_POST['amaano'])){
    $id = mysqli_real_escape_string($con, $_POST['amaano']);

    

    $stmt = "SELECT * FROM amaano WHERE customer_id = $id AND remain != 0 ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){

        $data[] = array("id" => "0", "name" => "-- Dooro sheyga --");
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = array("id" => $row["amano_id"], "name" => $row["name"] );
        }
    
        echo json_encode(["data" => $data]);
    }
}


if(isset($_POST['amaanoi_change'])){
    $id = mysqli_real_escape_string($con, $_POST['amaanoi_change']);

    $stmt = "SELECT * FROM amaano WHERE amano_id = $id";
    $result = mysqli_query($con, $stmt);
    if($row = mysqli_fetch_assoc($result)){
        echo $row['remain'];
    }
}

if(isset($_POST['customer'])){
    $cust = mysqli_real_escape_string($con, $_POST['customer']);
    $item = mysqli_real_escape_string($con, $_POST['amanoitem']);
    $given = mysqli_real_escape_string($con, $_POST['given']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);
    $date = mysqli_real_escape_string($con, $_POST['adate']);

    $userid = $_SESSION['uid'];
    $tdate = date('Y-m-d h:i:s a');

    $stmt = "INSERT INTO amaano_bixin (amaano_id, amount, description, date, trandate, warehouse, createdby) 
    VALUES ($item, $given, '$desc', '$date', '$tdate', 1, $userid )";
    $result = mysqli_query($con, $stmt); 
    if($result){
        $stmt = "UPDATE amaano SET given = $given, remain = total-$given WHERE amano_id = $item";
        $result = mysqli_query($con, $stmt);
        echo "success";
    }
    

}


// Getting edit information
if(isset($_POST['editAmaanoInfo'])){
    $id = mysqli_real_escape_string($con, $_POST['editAmaanoInfo']);

    $stmt = "SELECT * FROM amaano_bixin WHERE bixin_id = $id";
    $result = mysqli_query($con, $stmt); 
    $row = mysqli_fetch_assoc($result);
    $amaano = $row['amaano_id'];
    $date = $row['date'];
    $desc = $row['description'];
    $given = $row['amount'];

    echo "
        <div class='form-group col-md-6'>
            <label for=''> Macaamiil</label>
            <div class='input-group input-group-alt'> 
            <select name='customer' id='customer' class='form-control customer' data-toggle='selectpicker' required data-live-search='true' >
                <option data-tokens='' value=''> -- Dooro macaamiil -- </option>
            </select>
            </div>
            <p class='text-danger d-none' id='custMsg'></p>
        </div>

        <div class='form-group col-md-6'>
            <label for=''>Sheyga amaanada </label>
            <div class='input-group input-group-alt'> 
            <select name='amanoitem' id='amanoitem' class='form-control amanotype' data-toggle='selectpicker' required data-live-search='true' >
                <option data-tokens='' value=''>-- Dooro sheyga --  </option>
            </select>
            </div>
        </div>

        <div class='form-group col-md-4'>
            <label for=''>Tirada la bixiyay</label>
            <input type='number' name='given' id='given' step='0.02' class='form-control'>
        </div>

        <div class='form-group col-md-4'>
            <label for=''>Taarikhda la bixiyay</label>
            <input type='date' name='' id='' class='form-control'>
        </div>

        <div class='form-group col-md-4'>
            <label for=''>Tirada hartay</label>
            <input type='hidden' name='bal' id='bal'>
            <input type='text' name='balance' id='balance' class='form-control' readonly>
        </div>

        <div class='form-group col-md-12'>
            <label for=''>Faah faahin</label>
            <textarea name='desc' id='desc' class='form-control' value='$desc'></textarea>
        </div>

        <div class='form-group col-md-12'>
            <center>
                <button type='submit' class='btn btn-info rounded-6'>Bixi amaanada</button>
            </center>
        </div>

    ";


}



//Deleting Amaano bixin 
if(isset($_POST['delABixin'])){
    $id = mysqli_real_escape_string($con, $_POST['delABixin']);

    $stmt = "SELECT * FROM amaano_bixin WHERE bixin_id = $id";
    $result = mysqli_query($con, $stmt); 
    $row = mysqli_fetch_assoc($result);

    $amanoid = $row['amaano_id'];
    $amount = $row['amount'];

    $sql = "UPDATE amaano SET remain = remain + $amount, given = given - $amount WHERE amano_id = $amanoid";
    $res = mysqli_query($con, $sql);

    $stm = "DELETE FROM amaano_bixin WHERE bixin_id = $id";
    $re = mysqli_query($con, $stm);
    if($re){
        echo "deleted";
    }

}

?>