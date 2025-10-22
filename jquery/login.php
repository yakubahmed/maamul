<?php 
include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Mogadishu');

// Set content type to prevent any extra output
header('Content-Type: text/plain');

if(isset($_POST['username'])){
    $uname = mysqli_real_escape_string($con, $_POST['username']);
    $pwd  = mysqli_real_escape_string($con, md5($_POST['password']) ) ;

    $time = date('d-m-y h:i:s a');

    $stmt = "SELECT * FROM users WHERE email_addr = '$uname' AND password = '$pwd'";
    $result = mysqli_query($con, $stmt);
    
    // Check for database errors
    if (!$result) {
        echo 'database_error';
        exit();
    } 
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $fname = $row['fullname'];
        $role = $row['usergroup'];
        $uid = $row['userid'];
        $auth = $row['2fa_enabled'];
        $profile = $row['profile'];
        //Updating last login time
        $status = $row['status'];

        if($status == "Disabled"){
            echo "disabled";
        }else if($auth == 1){

            $_SESSION['auth'] = $uid;

            $code = rand(100000,999999);
            $sql = "UPDATE users SET aut_code = $code WHERE userid = $uid";
            $result = mysqli_query($con, $sql);
          //  require  '../PHPMailer/PHPMailerAutoload.php';

       
            // Try to send email, but don't fail login if email fails
            $email_sent = false;
            
            if(file_exists("../PHPMailer/PHPMailerAutoload.php")) {
                try {
                    require_once "../PHPMailer/PHPMailerAutoload.php"; //PHPMailer Object 
                    $mail = new PHPMailer; //From email address and name 
                    $mail->From = "no-replay@maamul.so"; 
                    $mail->FromName = "Maamul"; //To address and name 
                    $mail->addAddress($uname, $fname);//Recipient name is optional

                    $mail->isHTML(true);                                  // Set email format to HTML

                    if(file_exists('../assets/images/logo/easposblue.png')) {
                        $mail->AddEmbeddedImage('../assets/images/logo/easposblue.png', 'logo');
                    }

                    $mail->Subject = 'Login Verification code';
                    $mail->Body    = "
                        <html>
                            <head>
                                <style>
                                    
                                </style>
                            </head>
                            <body>
                                <div class=''  style='width:400px; margin: 0 auto; background-color:#efefef; padding: 3% 6%;'>
                                    <center><a href='#'><img style='border-radius:10px' src='cid:logo' height='50'></a></center>
                                    <h2>Salam, <span style='color:#346cb0;'>$fname </span></h2>
                                    <p>Your verification code: </p>
                                    <h2>$code</h2>
                                    <p>Please do not share this code with anyone.</p>
                                    </center>

                                    
                                    </div>
                                    <div style='width:400px;margin:0 auto; padding: 1% 6%; background:#346cb0; color:white;'>
                                        <center>
                                            <p>© 2022 All Rights Reserved. Developed by <strong>Yakub Ahmed</strong></p>
                                        </center>
                                    </div>
                                
                                    <div style='width:400px;margin:0 auto; padding: 0.1% 6%; background:#346cb0; color:white;'>
                                    <center>
                                        <p>© 2022 All Rights Reserved. Developed by <strong><a style='color:white;' href='https://smartplusadvert.so/' >SmartPlus Advert</a></strong></p>
                                    </center>
                                </div>
                            </body>
                        </html>

                    ";
                    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    if($mail->send()) {
                        $email_sent = true;
                    }
                } catch (Exception $e) {
                    // Email sending failed, but continue with login
                    $email_sent = false;
                }
            }
            
            // Always proceed with 2FA even if email fails
            echo "auth";
           
        }else{
            $_SESSION['isLogedIn'] = True;
            $_SESSION['uname'] = $uname;
            $_SESSION['fname'] = $fname;
            $_SESSION['usergroup'] = $role;
            $_SESSION['uid'] = $uid;
            $_SESSION['profile'] = $profile;
        
            
            echo 'success';

        }

    }else{
        echo 'failedToLogin';
    }

}

if(isset($_POST['auth'])){

    $code = $_POST['auth-code'];

    $id = $_SESSION['auth'];

    $s = "SELECT * FROM users WHERE aut_code = '$code' AND userid = $id";
    $exec = mysqli_query($con, $s);
    if(mysqli_num_rows($exec) > 0){
        $row = mysqli_fetch_assoc($exec);
        $fname = $row['fullname'];
        $uname = $row['email_addr'];
        $role = $row['usergroup'];
        $uid = $row['userid'];

        $sql = "UPDATE users SET aut_code = '' WHERE userid  = $uid";
        $re = mysqli_query($con, $sql);
        if($re){
            $_SESSION['isLogedIn'] = True;
            $_SESSION['uname'] = $uname;
            $_SESSION['fname'] = $fname;
            $_SESSION['usergroup'] = $role;
            $_SESSION['uid'] = $uid;
           
            echo "success";
        }
    }else if(mysqli_num_rows($exec) < 1){
        echo "error_code";
    }
}
?>