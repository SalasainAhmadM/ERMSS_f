<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../db.connection/connection.php'); // Include your database connection

if (isset($_POST["submitAccept"])) {
    try {
        // 1. Fetch user data from pendinguser table
        $email = $_POST["Email"];
        $sql = "SELECT * FROM pendinguser WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // 2. Insert user data into the user table
            $sql_insert = "INSERT INTO user (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param(
                'ssssssssssss', 
                $row['LastName'], 
                $row['FirstName'], 
                $row['MI'], 
                $row['Gender'], 
                $row['Email'], 
                $row['Password'], 
                $row['ContactNo'], 
                $row['Address'], 
                $row['Affiliation'], 
                $row['Position'], 
                $row['Image'], 
                $row['Role']
            );
            $stmt_insert->execute();

            // 3. Delete user from pendinguser table
            $sql_delete = "DELETE FROM pendinguser WHERE Email = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param('s', $email);
            $stmt_delete->execute();

            // Email logic for approval
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = 'eventmanagement917@gmail.com';
            $mail->Password = 'meapvvmlkmiccnjx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('eventmanagement917@gmail.com', 'Management System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Confirmation Link for Your Registration';
            $mail->Body = 'Your account has been approved: <a href="http://localhost/ERMSS_f/index.php?email=' . urlencode($email) . '">Confirm Registration</a>';
            $mail->AltBody = 'Click on the following link to confirm your registration: index.php';

            $mail->send();

            $_SESSION['success'] = true;
        } else {
            $_SESSION['error'] = "No pending user found.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    header("Location: ../admin/validation.php");
    exit();
}

if (isset($_POST["submitDecline"])) {
    try {
        // 1. Delete the user from the pendinguser table
        $email = $_POST["Email"];
        $sql_delete = "DELETE FROM pendinguser WHERE Email = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param('s', $email);
        $stmt_delete->execute();

        // Email logic for decline
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'eventmanagement917@gmail.com';
        $mail->Password = 'meapvvmlkmiccnjx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('eventmanagement917@gmail.com', 'Event Management System');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Account Declined';
        $mail->Body = 'Your account request has been declined.';
        $mail->AltBody = 'Your account request has been declined.';

        $mail->send();

        $_SESSION['decline'] = true;
    } catch (Exception $e) {
        $_SESSION['error'] = "Decline message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    header("Location: ../admin/validation.php");
    exit();
}
?>
