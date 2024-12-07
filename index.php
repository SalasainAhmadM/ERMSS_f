<?php
require_once('db.connection/connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/ccs/styles.css">

    <!-- SWIPER CSS-->
    <link rel="stylesheet" href="assets/ccs/swiper-bundle.min.css">

    <!-- browser icon-->
    <link rel="icon" href="assets/img/wesmaarrdec.jpg" type="image/png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- remixicons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css" />


    <title>Event Management System</title>
</head>

<body>
    <!-- HEADER -->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="index.html" class="nav__logo">WESMAARRDEC</a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#" class="nav__link">Home</a>
                    </li>

                    <li class="nav__item">
                        <a href="about.php" class="nav__link">About Us</a>
                    </li>

                    <li class="nav__item">
                        <a href="contact.php" class="nav__link">Contact Us</a>
                    </li> <!--login/admin-->

                    <!-- <li class="nav__item">
                            <a href="login.html" class="button">Login</a>
                        </li> -->
                </ul>

                <!--close button-->
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>

            <div class="nav__actions">
                <!--Theme Button-->

                <!--Toggle Button-->
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-apps-2-line"></i>
                </div>
            </div>
        </nav>
    </header>


    <!-- ====== MAIN =========-->
    <main class="main">
        <!-- HOME -->
        <section class="home section">
            <div class="home__rectangle"></div>

            <div class="home__container container grid">
                <div class="home__perfil perfil">
                    <div class="perfil__content">
                        <img src="assets/img/wesmaarrdec.jpg" alt="wesmaarrdec_logo" class="perfil__img">
                    </div>
                </div>

                <div class="home__content grid">
                    <div class="home__data grid">
                        <h1 class="home__name">EVENT</h1>

                        <h2 class="home__profession">MANAGEMENT SYSTEM</h2> <!-- MODIFY MODIFY MODIFY -->

                        <div class="home__social">
                            <a href="https://www.facebook.com/WESMAARRDEC?mibextid=ZbWKwL" target="_blank"
                                class="home__social-link">
                                <i class="ri-facebook-circle-line"></i>
                            </a>

                            <!-- <a href="" target="_blank" class="home__social-link">
                                    <i class="ri-instagram-line"></i>
                                </a>

                                <a href="" target="_blank" class="home__social-link">
                                    <i class="ri-twitter-line"></i>
                                </a> -->
                        </div>
                    </div>

                    <a href="login.php" class="home__button button">Get Started</a>
                </div>
            </div>
        </section>






        <!-- any additional here-->
        <section>


        </section>



    </main>

    <?php
    if (isset($_GET['email'])) {
        $email = htmlspecialchars($_GET['email']);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Registration Confirmed!',
                    text: 'Your account with email $email has been approved.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
    ?>
    <!-- FOOTER -->
    <!-- <footer class="footer">
            <div class="footer__container container grid">
                <div class="footer__content grid">
                    <a href="index.html" class="footer__logo">WESMAARRDEC</a>

                    <ul class="footer__links">
                        <li>
                            <a href="about.html" class="footer__link">About Us</a>
                        </li>

                        <li>
                            <a href="contact.html" class="footer__link">Contact Us</a>
                        </li>

                        <li>
                            <a href="login.html" class="footer__link">Get Started</a>
                        </li>
                    </ul>

                    <div class="footer__social">
                        <a href="" target="_blank" class="footer__social-link">
                            <i class="ri-facebook-circle-fill"></i>
                        </a>

                        <a href="" target="_blank" class="footer__social-link">
                            
                        </a>

                        <a href="" target="_blank" class="footer__social-link">
                            
                        </a>

                        <a href="" target="_blank" class="footer__social-link">
                            
                        </a>
                    </div>
                </div>

                <span class="footer__copy">
                    &#169; All Rights Reserved By Innovix
                </span>
            </div>
        </footer> -->

    <!--scroll up-->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="ri-arrow-up-line"></i>
    </a>


    <!-- MAIN JS-->
    <script src="assets/js/main.js"></script>

    <!-- SWIPER JS-->
    <script src="assets/js/swiper-bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- INDEX JS-->
    <script src="assets/js/index.js"></script>
</body>

</html>