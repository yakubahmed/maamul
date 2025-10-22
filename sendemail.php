<?php 

require_once "PHPMailer/PHPMailerAutoload.php"; //PHPMailer Object 
$mail = new PHPMailer; //From email address and name 
$mail->From = "me@yakubahmed.com"; 
$mail->FromName = "Yakub Ahmed"; //To address and name 
$mail->addAddress("yaaqa91@gmail.com", "Recepient Name");//Recipient name is optional
// $mail->addAddress("yaaqa91@gmail.com"); //Address to which recipient will reply 
// $mail->addReplyTo("reply@yourdomain.com", "Reply"); //CC and BCC 
// $mail->addCC("cc@example.com"); 
// $mail->addBCC("bcc@example.com"); //Send HTML or Plain Text email 
$mail->isHTML(true); 
$mail->Subject = "Subject Text"; 
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content"; 
if(!$mail->send()) 
{
echo "Mailer Error: " . $mail->ErrorInfo; 
} 
else { echo "Message has been sent successfully"; 
}
