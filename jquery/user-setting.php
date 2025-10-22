<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Mogadishu');


if(isset($_POST['fname'])){
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $id = $_SESSION['uid'];

    $temp = explode(".", $_FILES["image"]["name"]);
    $newfilename = rand() . date('m-d-y-h-i-s') . '.' . end($temp);

    if( move_uploaded_file($_FILES["image"]["tmp_name"], "../assets/images/" . $newfilename)){
        $stmt = "UPDATE users SET fullname = '$fname', profile = '$newfilename' WHERE userid = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo "success";
        }

    }else{
        $stmt = "UPDATE users SET fullname = '$fname' WHERE userid = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo "success";
        }
    }
}


if(isset($_POST['security'])){
    $cpass = mysqli_real_escape_string($con, md5( $_POST['cpassword']));
    $npass = mysqli_real_escape_string($con, md5($_POST['npassword']));
    $confirm = mysqli_real_escape_string($con, $_POST['conpass']);
    $twostep = mysqli_real_escape_string($con, $_POST['2step']);
    $id = $_SESSION['uid'];

    if(!empty($cpass)){
        $stmt = "SELECT * FROM users WHERE userid = $id";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_assoc($result);
        $pwd = $row['password'];

        if($cpass != $pwd){
            echo "invalid-password";
        }else{
            if($npass != $confirm){
                echo "mismatch";
            }else{
                if(empty($npass)){
                    $stmt = "UPDATE users SET auth = '$twostep' WHERE userid = $id";
                    $result = mysqli_query($con, $stmt);
                    if($result){
                        echo "success";
                    }                    
                }else{
                                    $stmt = "UPDATE users SET password = '$npass', auth = '$twostep' WHERE userid = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo "success";
                }
                }

            }
        }
    }else{
        $stmt = "UPDATE users SET  auth = '$twostep' WHERE userid = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo "success";
        }
    }
    
}

?>