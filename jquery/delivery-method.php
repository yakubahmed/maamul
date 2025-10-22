<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['mname'])){

    $mname = mysqli_real_escape_string($con, $_POST['mname']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);
    $date = date('Y-m-d');

    $stmt = "SELECT * FROM delivery_method WHERE meth_name = '$mname'";
    $result = mysqli_query($con, $stmt);
    if(mysqli_num_rows($result) > 0){
        echo 'found_meth';
    }else{
        $stmt = "INSERT INTO delivery_method (meth_name, description, date, warehouse ) VALUES ('$mname', '$desc', '$date', 1)";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        }
    }


    
}

if(isset($_POST['del_cat'])){
    $id = $_POST['del_cat'];
    $stmt = "DELETE FROM delivery_method WHERE del_meth_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo 'deleted';
    }
}

if(isset($_POST['edit_cat'])){
    $id = $_POST['edit_cat'];
    $stmt = "SELECT * FROM delivery_method WHERE del_meth_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $name = $row['meth_name'];
        $desc = $row['description'];

        echo "
        <div class='form-group col-md-12'>
        <label for=''>Method name *</label>
        <input type='hidden' name='update_del' id='update_del' value='$id'>
        <input type='text' name='mname1' value='$name' id='mname1' required autocomplete='off' class='form-control'>
       </div>

       <div class='form-group col-md-12'>
        <label for=''>Description</label>
        <textarea name='desc1' id='desc1' class='form-control'>$desc</textarea>
       </div>
        ";
    }
}

if(isset($_POST['mname1'])){
    $mname = mysqli_real_escape_string($con, $_POST['mname1']);
    $desc = mysqli_real_escape_string($con, $_POST['desc1']);
    $id = mysqli_real_escape_string($con, $_POST['update_del']);

    $stmt = "SELECT * FROM delivery_method WHERE del_meth_id = $id ";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_assoc($result);
    $curr_mname = $row['meth_name'];

    if($curr_mname == $mname){
        $stmt = "UPDATE delivery_method SET meth_name ='$mname' , description = '$desc' WHERE  del_meth_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'updated';
        }
    }else{
        $stmt = "SELECT * FROM delivery_method WHERE meth_name = '$mname'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo 'found';
        }else{
            $stmt = "UPDATE delivery_method SET meth_name ='$mname' , description = '$desc' WHERE  del_meth_id = $id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo 'updated';
            }
        }
    }
}

?>