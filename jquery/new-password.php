<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['password'])){
    $pass = md5( $_POST['password']);
    $cpass = $_POST['cpassword'];
    $code = $_POST['code'];

    

    $stmt = "UPDATE users SET password = '$pass' WHERE resetPasswordCode = '$code'";
    $result = mysqli_query($con, $stmt);
    if($result){
        
        $stmt = "SELECT * FROM users WHERE resetPasswordCode = '$code'";
        $res= mysqli_query($con, $stmt);
        $row = mysqli_fetch_assoc($res);
        $uid = $row['userid'];

        $sql = "UPDATE users SET resetPasswordCode = '' WHERE userid = $uid ";
        $re = mysqli_query($con, $sql);
        if($re){
            echo 'success';
        }
        
     
    }

}

?>