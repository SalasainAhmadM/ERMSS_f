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

    <!-- remixicons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.1.0/remixicon.css" />


    <title>About | Event Record Management</title>
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
                        <a href="#" class="nav__link">About Us</a>
                    </li>

                    <li class="nav__item">
                        <a href="contact.php" class="nav__link">Contact Us</a>
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


                <!--Toggle Button-->
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-apps-2-line"></i>
                </div>
            </div>
        </nav>
    </header>


    <!-- MAIN-->
    <main class="main">
        <!-- ==== ABOUT === -->
        <section class="about section">
            <h2 class="section__title">
                ABOUT US
            </h2>

            <div class="about__container about__page container grid">
                <div class="about__perfil perfil">
                    <div class="perfil__content">
                        <img src="assets/img/innovixLogo.png" alt="logo" class="perfil__img">
                    </div>
                </div>

                <div class="about__content grid">
                    <div class="about__data grid">
                        <div class="about__info grid">
                            <h1 class="about__cname">Innovix:</h1>
                            <h2 class="about__cquote">"Elevating today, Innovating Tomorrow"</h2>
                            <p class="about__description">
                                Software Engineering Project
                            </p>
                        </div>

                        <a href="contact.php" class="about__button button">Contact Us</a>
                    </div>

                </div>

            </div>
        </section>
        <br><br>
        <!-- <div class="about__goals">
            <h3 class="section__title">Our Goals Are</h3>

        </div> -->

        <!-- <div class="goals__container contaienr grid"> -->
        <!-- <article class="goals__card">
                <i class="ri-code-s-slash-line goals__icon"></i>
                <h2 class="goals__title">Goals Here</h2>
                <p class="goals__description">
                    Description here...
                </p>

                <button class="goals__button button">Know More</button>

                <div class="goals__modal">
                    <div class="goals__modal-content">
                        <i class="ri-close-line goals__modal-close"></i>

                        <h2 class="goals__modal-title">Web Developer</h2>

                        <ul class="goals__modal-list grid">
                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>
                        </ul>
                    </div>
                </div>
            </article>

            <article class="goals__card">
                <i class="ri-code-s-slash-line goals__icon"></i>
                <h2 class="goals__title">Goals Here</h2>
                <p class="goals__description">
                    Description here...
                </p>

                <button class="goals__button button">Know More</button>

                <div class="goals__modal">
                    <div class="goals__modal-content">
                        <i class="ri-close-line goals__modal-close"></i>

                        <h2 class="goals__modal-title">Web Developer</h2>

                        <ul class="goals__modal-list grid">
                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>
                        </ul>
                    </div>
                </div>
            </article>

            <article class="goals__card">
                <i class="ri-code-s-slash-line goals__icon"></i>
                <h2 class="goals__title">Goals Here</h2>
                <p class="goals__description">
                    Description here...
                </p>

                <button class="goals__button button">Know More</button>

                <div class="goals__modal">
                    <div class="goals__modal-content">
                        <i class="ri-close-line goals__modal-close"></i>

                        <h2 class="goals__modal-title">Web Developer</h2>

                        <ul class="goals__modal-list grid">
                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">

                            </li>

                            <li class="goals__modal-item">
                                I
                            </li>

                            <li class="goals__modal-item">

                            </li>
                        </ul>
                    </div>
                </div>
            </article> -->
        <!-- </div> -->

        <!-- OUR TEAM-->
        <section class="team section">
            <h2 class="section__title">
                Our Team<br>

            </h2>

            <div class="team__container container">
                <div class="team__swiper swiper">
                    <div class="swiper-wrapper">
                        <article class="team__card swiper-slide">
                            <div class="team__border">
                                <img src="assets/img/dumaboc.jpeg" alt="" class="team__img">
                            </div>

                            <h2 class="team__name">Jaylen Dumaboc</h2>
                            <p class="team__description">
                                Project Manager
                            </p>
                        </article>

                        <article class="team__card swiper-slide">
                            <div class="team__border">
                                <img src="assets/img/delica.jpeg" alt="" class="team__img">
                            </div>

                            <h2 class="team__name">Faustine Delica</h2>
                            <p class="team__description">
                                System Analyst
                            </p>
                        </article>

                        <article class="team__card swiper-slide">
                            <div class="team__border">
                                <img src="assets/img/mark.png" alt="" class="team__img">
                            </div>

                            <h2 class="team__name">Mark Larenz Tabotabo</h2>
                            <p class="team__description">
                                Lead Programmer
                            </p>
                        </article>

                        <article class="team__card swiper-slide">
                            <div class="team__border">
                                <img src="assets/img/abule.png" alt="" class="team__img">
                            </div>

                            <h2 class="team__name">Zild Abule</h2>
                            <p class="team__description">
                                Tester/Quality Assurance
                            </p>
                        </article>
                    </div>
                </div>

                <!-- Pagination or next slides-->
                <div class="swiper-pagination"></div>
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
                        <a href="#" class="footer__link">About Us</a>
                    </li>

                    <li>
                        <a href="contact.php" class="footer__link">Contact Us</a>
                    </li>

                    <li>
                        <a href="login.php" class="footer__link">Get Started</a>
                    </li>
                </ul>

                <div class="footer__social">
                    <a href="https://www.facebook.com/WESMAARRDEC?mibextid=ZbWKwL" target="_blank"
                        class="footer__social-link">
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

    <!-- SWIPER JS-->
    <script src="assets/js/swiper-bundle.min.js"></script>

    <!-- INDEX JS-->
    <script src="assets/js/index.js"></script>


</body>

</html>