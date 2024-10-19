<?php
    include('../function/F.event_retrieve.php');
    $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Management System</title>

        <!--boxicons-->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!--browser icon-->
        <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

        <link rel="stylesheet" href="css/main.css">

        
    </head>

    <body>

        <?php
            // Include your database connection code here
            require_once('../db.connection/connection.php');
        ?>

        <?php include('sidebar.php'); ?>

        <!-- ============ CONTENT ============-->
        <div class="main-content">
            <div class="containerr">
                <h3 class="dashboard">EVENTS</h3>

                <!--======= event filter starts ======= -->
                <section class="event-filter"> <!--dapat naka drop down ito-->

                    <h1 class="heading">Event Participants</h1>

                    <form action="" method="post">
                        <div class="flex">
                            <div class="box">
                                <p>Paticipants Name <span>*</span></p>
                                <input type="text" id="participantInput" placeholder="filter using names" class="input">
                            </div>
                        </div>
                        
                        
                    </form>

                </section>
                <!-- ======= event filter ends ========-->

            </div>


            <div class="containerr">
                <!--========= all event start =============-->

                <section class="category">

                    <div class="box-container">

                        <a href="#" class="box">
                            <i class="fa-solid fa-child"></i>
                            <div>
                                <h3>Total</h3>
                                <span>50 Particpants</span>
                            </div>
                        </a>

                        <a href="#" class="box">
                            <i class="fa-solid fa-person-running"></i>
                            <div>
                                <h3>Attendees</h3>
                                <span>45 Participants</span>
                            </div>
                        </a>

                        <a href="#" class="box">
                            <i class="fa-solid fa-user-xmark"></i>
                            <div>
                                <h3>Absentees</h3>
                                <span>5 Participants</span>
                            </div>
                        </a>

                        <a href="#" class="box">
                            <i class="fa-solid fa-qrcode"></i>
                            <div>
                                <h3>Generate QR</h3>
                                <span>For Attendance</span>
                            </div>
                        </a>
                    </div>
                </section>
                

                <!-- ALL EVENTS TABULAR FORM-->
                <div class="event-table">
                    <div class="tbl-container">
                        <h2><?php echo $_SESSION['event_data']['eventTitle']; ?></h2>
                        <div class="tbl-wrapper">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Affiliation</th>
                                        <th>Position</th>
                                        <th>Event Date</th>
                                        <th>Event Time</th>
                                        <th class="day">Day 1</th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td data-label="Full Name">Mark Larenz tabotabo</td>
                                        <td data-label="Affiliation">WESMAARRDEC</td>
                                        <td data-label="Position">LEAD PROGRAMMER</td>
                                        <td data-label="Event Date">EVENT DATE HERE</td>
                                        <td data-label="Event Time">EVENT TIME HERE</td>
                                        <td data-label="Day">Present or Absent</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>

                <section class="category">
                    <div class="box-container">
                    <a href="view_event.php" class="box">
                        <i class="fa-solid fa-arrow-left"></i>
                        <div>
                        <h3>Go Back</h3>
                        <span>Click to go back</span>
                        </div>
                    </a>
            
                    </div>
                </section>

                
                <!-- ============all event ends ========-->
            </div>
        </div>



        




        <!-- CONFIRM DELETE -->
        <script src=js/deleteEvent.js></script>
            

        <!--JS -->
        <script src="js/eventscript.js"></script>


        <!--sidebar functionality-->
        <script src="js/sidebar.js"></script>

        <!--filter event-->
        <script src="js/event_filter.js"></script>

    </body> 

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tblWrapper = document.querySelector('.tbl-wrapper');
            const tblHead = document.querySelector('.tbl thead');

            tblWrapper.addEventListener('scroll', function () {
                const scrollLeft = tblWrapper.scrollLeft;
                const thElements = tblHead.getElementsByTagName('th');

                for (let th of thElements) {
                    th.style.left = `-${scrollLeft}px`;
                }
            });
        });
    </script>




    <!--real-time update-->
    <script src="js/realTimeUpdate.js"></script>

</html>
