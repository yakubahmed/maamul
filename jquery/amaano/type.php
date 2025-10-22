<?php 
session_start();

include('../../inc/config.php');

date_default_timezone_set('Africa/Nairobi');


if(isset($_POST['atname'])){
    $name = $_POST['atname'];
    $dec = $_POST['cesc'];
    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    $stmt = "INSERT INTO amaano_type (name, description, created_date, warehouse, createdby) VALUES
    ('$name', '$dec', '$date', 1, '$userid')";
    $result = mysqli_query($con,$stmt); 
    if($result){
        echo "success";
    }else{
        echo "failed";
    }


}


if(isset($_POST['del_atype'])){
    $id = $_POST['del_atype'];

    $stmt = "DELETE FROM amaano_type WHERE amanotid = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo "deleted";
    }
}

if(isset($_POST['get_amanot'])){
    $id = $_POST['get_amanot'];

    $stmt = "SELECT * FROM amaano_type WHERE amanotid = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $name = $row['name'];
        $desc = $row['description'];

        echo "
            <div class='form-group col-md-12'>
                <label for=''>Magaca </label>
                <input type='hidden' name='amanotid' value='$id' id='amanotid'>
                <input type='text' name='atname1' value='$name' id='atname1' class='form-control'>
            </div>

            <div class='form-group col-md-12'>
                <label for=''>Faah faahin</label>
                <textarea name='cesc1' class='form-control' id='cesc1' >$desc</textarea>
            </div>

            <div class='form-group col-md-12'>
            <center>
              <button type='submit' class='btn btn-info rounded-6'>Ii keydi xogta</button>
            </center>
            </div>
        ";
    }
}

if(isset($_POST['amanotid'])){
    $id = $_POST['amanotid']; 
    $name = $_POST['atname1'];
    $desc = $_POST['cesc1'];

    $stmt = "UPDATE amaano_type SET name = '$name', description = '$desc' WHERE amanotid = $id";
    $result = mysqli_query($con, $stmt); 
    if($result){
        echo "success";
    }
}


if(isset($_POST['get_amanot'])){
    $stmt = "SELECT * FROM amaano_type "; 
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = array("id" => $row["amanotid"], "name" => $row["name"]);
    }

    echo json_encode(["data" => $data]);
}
?>