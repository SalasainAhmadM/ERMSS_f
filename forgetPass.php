<?php
    require_once('db.connection/connection.php');
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


        
        <!-- remixicons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css"/>


        <title>Sign-in & Sign-up | Event Record Management</title>
    </head>


    <body>
        <div class="container">
            <div class="forms-container">
                <div class="signin-signup">

                    <form action="" class="sign-in-form" method="POST">
                        <h2 class="title">Forget Password</h2>

                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="Password" placeholder="New Password" required>
                        </div>

                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="Password" placeholder="Confirm Password" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="Email" placeholder="Email" required>
                        </div>

                        <!-- <input type="submit" value="Sign in" class="btn solid"> -->
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
                        <p>Explore, join, and manage events seamlessly with our platform. Sign in to your account and be part of the vibrant WESMAARRDEC community. Collaborate on research events and stay informed about upcoming opportunities.</p>
                        <button class="btn transparent" id="sign-in-btn">Sign in</button>
                    </div>
                    
                    <img src="assets/img/wesmaarrdec-removebg-preview.png" class="image" alt="">
                </div>
            </div>
        </div>
        






        <!-- SIGNIN-SIGNUP JS-->
        <script src="assets/js/signin-signup.js"></script>


        <!--font awesome kit -->
        <script src="https://kit.fontawesome.com/7b27fcfa62.js" crossorigin="anonymous"></script>


        <!-- <script>
            function redirectToLandingPage() {
                // Perform the redirection
                window.location.href = 'admin/landingPage_admin.html';
            }
        </script> -->

    </body>
</html>