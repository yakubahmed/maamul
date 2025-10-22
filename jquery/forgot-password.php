<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['username'])){
    $username = $_POST['username'];

    $stmt = "SELECT * FROM users WHERE email_addr = '$username' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $name = $row['fullname'];
        $uid = $row['userid'];
        $code = rand()  . date('His');
            $sql = "UPDATE users SET `resetPasswordCode` = '$code' WHERE userid = $uid";
            $res = mysqli_query($con, $sql);

            require_once "../PHPMailer/PHPMailerAutoload.php"; //PHPMailer Object 
            $mail = new PHPMailer; //From email address and name 
            $mail->From = "no-replay@submalco.so"; 
            $mail->FromName = "EasyPos"; //To address and name 
            $mail->addAddress($username, $name);//Recipient name is optional
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->AddEmbeddedImage('../assets/images/logo/easposblue.png', 'logo');


            $mail->Subject = 'Reset password';
            $mail->Body    = "
                <html>
                    <head>
                        <style>
                            
                        </style>
                    </head>
                    <body>
                        <div class='' style='width:400px; margin: 0 auto; background-color:#efefef; padding: 3% 6%;'>
                            <center><a href='#'><img style='border-radius:10px' src='cid:logo' height='50'></a></center>
                            <h2>Salam <span style='color:#346cb0;'>$name </span></h2>
                            <p>You are receiving this email because we received a password reset request for your account. </p>
                            <a href='https://app.submalco.so/new-password?ref=$code' style='  background-color: #4CAF50;border: none;color: white;padding: 2% 6%;text-align: center;
                            text-decoration: none;
                            display: inline-block;
                            font-size: 16px;
                            margin: 4px 2px;
                            cursor: pointer;
                            border-radius:20px;' >Reset Password</a>
                            <p>Please do not share this Link with anyone.</p>
                            </center>

                            
                            </div>
                            <div style='width:400px;margin:0 auto; padding: 0.3% 6%; background:#346cb0; color:white;'>
                                <center>
                                    <p>© 2022 All Rights Reserved. Developed by <strong><a href='https://smartplusadvert.so/' style='color:white' target='_blank'>SmartPlus Advert</a></strong></p>
                                </center>
                            </div>
                    </body>
                </html>

            ";
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo "success";
            }
    }else{
        echo "not-found";
    }
}

?>