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
        <!-- browser icon-->
        <link rel="icon" href="assets/img/wesmaarrdec.jpg" type="image/png">
        
        <!-- remixicons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css"/>


        <title>Contact | Event Record Management</title>
    </head>
    <body>
        <!-- HEADER -->
        <header class="header header-pages" id="header">
            <nav class="nav container">
                <a href="index.php" class="nav__logo">WESMAARDEC</a>

                <div class="nav__menu" id="nav-menu">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a href="index.php" class="nav__link">Home</a>
                        </li>

                        <li class="nav__item">
                            <a href="about.php" class="nav__link">About Us</a>
                        </li>

                        <li class="nav__item">
                            <a href="#" class="nav__link">Contact Us</a>
                        </li> <!--login/admin-->

                        <li class="nav__item">
                            <a href="login.php" class="button">Get Started</a>
                        </li>
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


        <!-- MAIN-->
        <main class="main">
            <!-- CONTACT -->
            <section class="contact section">
                <h2 class="section__title">
                    Contact Us
                </h2>

                <div class="contact__page container grid">
                    <form action="" class="contact__form grid" id="contact-form">
                        <div class="contact__group grid">
                            <div class="contact__box">
                                <input type="text" name="user_name" id="name" required placeholder="Write your name" class="contact__input">
                                <label for="name" class="contact__label">Full Name</label>
                            </div>

                            <div class="contact__box">
                                <input type="email" name="user_email" id="" required placeholder="Write your email" class="contact__input">
                                <label for="email" class="contact__label">Email Address</label>
                            </div>
                        </div>

                        <div class="contact__box contact__area">
                            <textarea name="user_message" id="message" requred placeholder="Write your message" class="contact__input"></textarea>
                            <label for="message" class="contact__label">Message</label>
                        </div>

                        <p class="contact__message" id="contact-message"></p>

                        <button type="submit" class="contact__send button">Send Message</button>
                        
                    </form>
                </div>
            </section>
        </main>


        <!-- FOOTER -->
        <footer class="footer">
            <div class="footer__container container grid">
                <div class="footer__content grid">
                    <a href="index.php" class="footer__logo">WESMAARRDEC</a>

                    <ul class="footer__links">
                        <li>
                            <a href="about.php" class="footer__link">About Us</a>
                        </li>

                        <li>
                            <a href="contact.#" class="footer__link">Contact Us</a>
                        </li>

                        <li>
                            <a href="login.php" class="footer__link">Get Started</a>
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
        </footer>



        <!-- MAIN JS-->
        <script src="assets/js/main.js"></script>

        <!-- INDEX JS-->
        <script src="assets/js/index.js"></script>

        <!-- EMAIL JS -->
        <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>

        <!--CONTACT JS-->
        <script src="assets/js/contact.js"></script>
    </body>
</html>