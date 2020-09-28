<?php
include "classes/class.phpmailer.php";
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.zoho.com";
$mail->Port = 465;
$mail->IsHTML(true);
$mail->Username = "mailer@swiftcampus.com";
$mail->Password = "xhyv@dsc";
$mail->SetFrom("mailer@swiftcampus.com", "SwiftCampus Mailer");
$mail->Subject = $subject;
$mail->Body = $message;
$mail->AddAddress($to);
 if(!$mail->Send()){
	echo "Mailer Error: " . $mail->ErrorInfo;
}
else{
	echo "";
}
?>