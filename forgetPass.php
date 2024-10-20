<?php
session_start(); 

require_once('db.connection/connection.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$alertMessage = ''; // To hold the alert script

if (isset($_POST['submit'])) {
    $email = $_POST['Email'];

    $query = "SELECT Email FROM user WHERE Email = ? 
    UNION 
    SELECT Email FROM pendinguser WHERE Email = ? 
    UNION 
    SELECT Email FROM admin WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, proceed with sending the reset link
        $token = bin2hex(random_bytes(32)); // Generate a token
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes')); // Token expiry time

        // Insert the token into the password_reset_tokens table
        $insertTokenQuery = "INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertTokenQuery);
        $stmt->bind_param("sss", $email, $token, $expiresAt);
        $stmt->execute();

        // PHPMailer initialization
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'eventmanagement917@gmail.com'; // Your email
            $mail->Password = 'meapvvmlkmiccnjx'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Email settings
            $mail->setFrom('eventmanagement917@gmail.com', 'Event Management System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset your password';
            $mail->Body = "
                <h3>Reset Password Request</h3>
                <p>We received a request to reset your password. Click the link below to reset it:</p>
                <a href='http://localhost/ERMSS_f/resetPass.php?token=$token'>Reset Password</a>
                <p>If you did not request a password reset, please ignore this email.</p>
            ";

            $mail->send();

            $alertMessage = "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Password reset link has been sent to your email.',
                        icon: 'success'
                    });
                </script>
            ";
        } catch (Exception $e) {
         
            $alertMessage = "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "',
                        icon: 'error'
                    });
                </script>
            ";
        }
    } else {
        $alertMessage = "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Email does not exist.',
                    icon: 'error'
                });
            </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/ccs/signin-signup.css">

    <!-- browser icon-->
    <link rel="icon" href="assets/img/wesmaarrdec.jpg" type="image/png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- remixicons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css"/>

    <title>Forget Password | Event Record Management</title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="forgetPass.php" class="sign-in-form" method="POST">
                    <h2 class="title">Forget Password</h2>

                    <div class="input-field">
                        <i class="fa-solid fa-user"></i>
                        <input type="email" name="Email" placeholder="Email" required>
                    </div>

                    <input type="submit" value="Send Reset Link" name="submit" class="btn solid">

                    <div class="options">
                        <p class="social-text"><a href="index.php">Go Back</a></p>
                    </div>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <img src="assets/img/wesmaarrdec-removebg-preview.png" class="image" alt="">
            </div>

            <div class="panel right-panel">
                <div class="content">
                    <h3>Welcome to Event Management System!</h3>
                    <p>Explore, join, and manage events seamlessly with our platform. Sign in to your account and be part of the vibrant WESMAARRDEC community. Collaborate on research events and stay informed about upcoming opportunities.</p>
                    <button class="btn transparent" id="sign-in-btn">Sign in</button>
                </div>
                
                <img src="assets/img/wesmaarrdec-removebg-preview.png" class="image" alt="">
            </div>
        </div>
    </div>

    <?php echo $alertMessage; ?>

    <!-- SIGNIN-SIGNUP JS-->
    <script src="assets/js/signin-signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- font awesome kit -->
    <script src="https://kit.fontawesome.com/7b27fcfa62.js" crossorigin="anonymous"></script>
</body>
</html>
