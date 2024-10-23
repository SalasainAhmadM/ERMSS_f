<?php
require_once('db.connection/connection.php');

$alertMessage = ''; // To store the alert script

// Check if token is present in URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Fetch the token from the password_reset_tokens table
    $query = "SELECT email, expires_at FROM password_reset_tokens WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $expires_at = $row['expires_at'];

        // Check if the token has expired
        if (strtotime($expires_at) > time()) {
            // If form is submitted
            if (isset($_POST['submit'])) {
                $password = $_POST['Password'];
                $confirmPassword = $_POST['ConfirmPassword'];

                // Check if passwords match
                if ($password === $confirmPassword) {
                    // Hash the new password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Update the user's password in the user table
                    $updateQuery = "UPDATE user SET Password = ? WHERE Email = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("ss", $hashedPassword, $email);
                    $stmt->execute();

                    // Delete the token after password reset
                    $deleteTokenQuery = "DELETE FROM password_reset_tokens WHERE email = ?";
                    $stmt = $conn->prepare($deleteTokenQuery);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();

                    // Success message
                    $alertMessage = "
                        <script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your password has been successfully reset.',
                                icon: 'success'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                }
                            });
                        </script>
                    ";
                } else {
                    // Error message: Passwords do not match
                    $alertMessage = "
                        <script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Passwords do not match.',
                                icon: 'error'
                            });
                        </script>
                    ";
                }
            }
        } else {
            // Error message: Token expired
            $alertMessage = "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'This password reset link has expired.',
                        icon: 'error'
                    });
                </script>
            ";
        }
    } else {
        // Error message: Invalid token
        $alertMessage = "
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid password reset link.',
                    icon: 'error'
                });
            </script>
        ";
    }
} else {
    // Error message: No token found
    $alertMessage = "
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'No password reset token found.',
                icon: 'error'
            });
        </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/ccs/signin-signup.css">

    <!-- Browser icon -->
    <link rel="icon" href="assets/img/wesmaarrdec.jpg" type="image/png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Remixicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css" />

    <title>Reset Password | Event Record Management</title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" class="sign-in-form" method="POST">
                    <h2 class="title">Reset Password</h2>

                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="Password" placeholder="New Password" required>
                    </div>

                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="ConfirmPassword" placeholder="Confirm Password" required>
                    </div>

                    <input type="submit" value="Save" name="submit" class="btn solid">

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
                        part of the vibrant WESMAARRDEC community.</p>
                    <button class="btn transparent" id="sign-in-btn">Sign in</button>
                </div>
                <img src="assets/img/wesmaarrdec-removebg-preview.png" class="image" alt="">
            </div>
        </div>
    </div>

    <!-- SIGNIN-SIGNUP JS -->
    <script src="assets/js/signin-signup.js"></script>
    <?php echo $alertMessage; ?>
    <!-- Font Awesome Kit -->
    <script src="https://kit.fontawesome.com/7b27fcfa62.js" crossorigin="anonymous"></script>
</body>

</html>