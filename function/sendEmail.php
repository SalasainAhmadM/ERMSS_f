<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../db.connection/connection.php');

if(isset($_POST["submitAccept"])){
    try {
        $mail = new PHPMailer(true); 

        $mail->isSMTP();
        $mail->Host  = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'eventmanagement917@gmail.com'; 
        $mail->Password = 'meap vvml kmic cnjx'; 
        $mail->SMTPSecure = 'tsl';
        $mail->Port = 587;

        $mail->setFrom('eventmanagement917@gmail.com', 'Event');
        $mail->addAddress($_POST["Email"]); // Use the email address from the form

        $mail->isHTML(true);
        $mail->Subject = 'Confirmation Link for Your Registration';
        $mail->Body = 'Your account has been approved: <a href="index.php?email=' . urlencode($_POST["Email"]) . '">Confirm Registration</a>';
        $mail->AltBody = 'Click on the following link to confirm your registration: index.php';

        $mail->send();
        echo 'Message has been sent'; //make an pop alert here
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if(isset($_POST["submitDecline"])){
    try {
        $mail = new PHPMailer(true); 

        $mail->isSMTP();
        $mail->Host  = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'eventmanagement917@gmail.com'; 
        $mail->Password = 'meap vvml kmic cnjx'; 
        $mail->SMTPSecure = 'tsl';
        $mail->Port = 587;

        $mail->setFrom('eventmanagement917@gmail.com', 'Event');
        $mail->addAddress($_POST["Email"]); // Use the email address from the form

        $mail->isHTML(true);
        $mail->Subject = 'Account Declined';
        $mail->Body = 'Your account request has been declined.';
        $mail->AltBody = 'Your account request has been declined.';

        $mail->send();
        echo 'Decline message has been sent';
    } catch (Exception $e) {
        echo "Decline message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
