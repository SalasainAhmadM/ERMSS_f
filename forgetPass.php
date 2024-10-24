<?php
session_start();

require_once('db.connection/connection.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$alertMessage = '';

if (isset($_POST['submit'])) {
    $email = $_POST['Email'];

    // Query to search the email in all relevant tables
    $query = "
        SELECT Email, 'active' AS status FROM user WHERE Email = ? 
        UNION 
        SELECT Email, 'pending' AS status FROM pendinguser WHERE Email = ? 
        UNION 
        SELECT Email, 'admin' AS status FROM admin WHERE Email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the email belongs to a pending user
        if ($row['status'] === 'pending') {
            $alertMessage = "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'This email is pending approval. Please wait for confirmation.',
                        icon: 'warning'
                    }).then(() => {
            hideLoaderAfterAlert();
        });
                </script>
            ";
        } else {
            // Proceed with password reset for active or admin users
            $token = bin2hex(random_bytes(32)); // Generate token
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes')); // Expiry time

            // Insert token into the password_reset_tokens table
            try {
                $insertTokenQuery = "INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insertTokenQuery);
                $stmt->bind_param("sss", $email, $token, $expiresAt);
                $stmt->execute();

                // Initialize PHPMailer
                $mail = new PHPMailer(true);

                // SMTP server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
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
                        }).then(() => {
            hideLoaderAfterAlert();
        });
                    </script>
                ";
            } catch (mysqli_sql_exception $e) {
                // Handle duplicate entry error for token insertion
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $alertMessage = "
                        <script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'A reset link has already been generated for this email. Please check your inbox.',
                                icon: 'error'
                            }).then(() => {
            hideLoaderAfterAlert();
        });
                        </script>
                    ";
                } else {
                    // Handle other SQL errors
                    $alertMessage = "
                        <script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred: " . $e->getMessage() . "',
                                icon: 'error'
                            }).then(() => {
            hideLoaderAfterAlert();
        });
                        </script>
                    ";
                }
            } catch (Exception $e) {
                // Handle PHPMailer errors
                $alertMessage = "
                    <script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "',
                            icon: 'error'
                        }).then(() => {
            hideLoaderAfterAlert();
        });
                    </script>
                ";
            }
        }
    } else {
        // Email not found in any table
        $alertMessage = "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Email does not exist.',
                    icon: 'error'
                }).then(() => {
            hideLoaderAfterAlert();
        });
            </script>
        ";
    }

    // Display alert message
    echo $alertMessage;
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- remixicons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css" />

    <title>Forget Password | Event Record Management</title>
</head>
<style>
    /* HTML: <div class="loader"></div> */
    .loader {
        width: 90px;
        height: 14px;
        box-shadow: 0 3px 0 #fff;
        /* display: grid; */
        display: none;
    }

    .loader:before,
    .loader:after {
        content: "";
        grid-area: 1/1;
        background: radial-gradient(circle closest-side, var(--c, red) 92%, #0000) 0 0/calc(100%/4) 100%;
        animation: l4 1s infinite linear;
    }

    .loader:after {
        --c: #000;
        background-color: #fff;
        box-shadow: 0 -2px 0 0 #fff;
        clip-path: inset(-2px calc(50% - 10px));
    }

    @keyframes l4 {
        100% {
            background-position: calc(100%/3) 0
        }
    }
</style>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form id="forgetPassForm" action="forgetPass.php" class="sign-in-form" method="POST">
                    <h2 class="title">Forget Password</h2>

                    <div class="input-field">
                        <i class="fa-solid fa-user"></i>
                        <input type="email" name="Email" placeholder="Email" required>
                    </div>

                    <input type="submit" value="Send Reset Link" name="submit" class="btn solid">
                    <!-- Loader -->
                    <div class="loader" id="loader"></div>

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
                    <p>Explore, join, and manage events seamlessly with our platform. Sign in to your account and be
                        part of the vibrant WESMAARRDEC community. Collaborate on research events and stay informed
                        about upcoming opportunities.</p>
                    <button class="btn transparent" id="sign-in-btn">Sign in</button>
                </div>

                <img src="assets/img/wesmaarrdec-removebg-preview.png" class="image" alt="">
            </div>
        </div>
    </div>

    <?php echo $alertMessage; ?>
    <script>
        const form = document.getElementById('forgetPassForm');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', function (e) {
            loader.style.display = 'grid';
        });

        function hideLoaderAfterAlert() {
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    </script>
    <!-- SIGNIN-SIGNUP JS-->
    <script src="assets/js/signin-signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- font awesome kit -->
    <script src="https://kit.fontawesome.com/7b27fcfa62.js" crossorigin="anonymous"></script>
</body>

</html>