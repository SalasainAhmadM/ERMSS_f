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


        <!-- Kaizoku -->
        <!-- remixicons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css"/>


        <title>Sign-in & Sign-up | Event Record Management</title>
    </head>


    <body>
        <div class="container">
            <div class="forms-container">
                <div class="signin-signup">

                    <form action="function/F.signIn.php" class="sign-in-form" method="POST">
                        <h2 class="title">Sign in</h2>
                        <div class="input-field">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="Email" placeholder="Email" required>
                        </div>

                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="Password" placeholder="Password" required>
                        </div>
                        <!-- <input type="submit" value="Sign in" class="btn solid"> -->
                        <input type="submit" value="Sign in" name="submit" class="btn solid">

                        <div class="options">
                            <p class="social-text"><a href="forgetPass.php">Forget Password?</a></p>
                            <p class="social-text"><a href="index.php">Go Back</a></p>
                        </div>
                        
                    </form>

                    <form action="function/F.signUp.php" class="sign-up-form" method="POST">
                        <h2 class="title">Sign up</h2>
    
                        <h3 lass="title" style="margin-top:1rem;">Personal Information</h3>
                        <div class="input-field">
                            <i class="fa-solid fa-circle-user"></i>
                            <input type="text" name="LastName" placeholder="Last Name" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-circle-user"></i>
                            <input type="text" name="FirstName" placeholder="First Name" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-circle-user"></i>
                            <input type="text" name="MI" placeholder="Middle Initial">
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-circle-user"></i>
                            <select id="genderSelect" name="Gender" required>
                                <option value="" disabled selected>Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <h3 lass="title" style="margin-top:1rem;">Account Information</h3>
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="Email" placeholder="Email" required>
                        </div>

                        <!-- <div class="input-field">
                            <i class="fa-solid fa-phone"></i>
                            <input type="number" name="ContactNo" placeholder="Contact Number" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-building"></i>
                            <input type="text" name="Address" placeholder="Street, Barangay, City" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-building"></i>
                            <input type="text" name="Affiliation" placeholder="Affiliation" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-address-card"></i>
                            <input type="text" name="Position" placeholder="Position" required>
                        </div> -->

                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password"  name="Password" placeholder="Password" required>
                        </div>

                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="ConfirmPassword" placeholder="Confirm Password" required>
                        </div>

                        

                        <div class="options">
                            <input type="submit" name="submit" value="Sign up" class="btn solid signUp">
                            <p class="social-text"><a href="index.php">Go Back</a></p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panels-container">
                <div class="panel left-panel">
                    <div class="content">
                        <h3>Unlock Access to WESMAARRDEC's Collaborative Events</h3>
                        <p>Unlock a world of opportunities in agricultural and natural resources research and development. Sign up now to be part of our collaborative platform!</p>
                        <!-- <p>Unlock the ability to participate in exlusive events hosted by WESMAARRDEC. Sign up now to create your account and start connecting with researchers, joining impactful events, and contributing to the advancement of agricultural and natural resources research.</p> -->
                        <button class="btn transparent" id="sign-up-btn">Sign up</button>
                    </div>

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

        <script>
            document.getElementById("genderSelect").addEventListener("change", function() {
                this.style.color = "var(--body-color)";
            });
        </script>

        <!-- <script>
            function redirectToLandingPage() {
                // Perform the redirection
                window.location.href = 'admin/landingPage_admin.html';
            }
        </script> -->

        
    </body>

    <!-- Error Modal -->
<!-- <div id="errorModal" class="modal" style="display: <?php echo $message ? 'block' : 'none'; ?>">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p><?php echo $message; ?></p>
    </div>
</div> -->

</html>