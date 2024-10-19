<?php
session_start();
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

        <?php include('sidebar.php'); ?>

        <!-- ============ CONTENT ============-->
        <div class="main-content">
            <div class="containerr">
                <h3 class="dashboard">EVENT DETAILS</h3>
                <!-- view event starts-->
                <section class="event-details">

                    <h1 class="heading">event details</h1>

                    <div class="details">
                        <div class="event-info">
                            <h3><?php echo $_SESSION['event_data']['eventTitle']; ?></h3>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo $_SESSION['event_data']['eventLocation']; ?></p>
                        </div>

                        <?php if (!empty($_SESSION['event_data']['eventPhoto'])) : ?>
                            <div class="info">
                                <img src="<?php echo $_SESSION['event_data']['eventPhoto']; ?>" alt="">
                            </div>
                        <?php endif; ?>

                        <div class="description">
                            <h3>event description</h3>
                            <p><?php echo $_SESSION['event_data']['eventDesc']; ?></p>
                            <ul>
                                <li>Date: <?php echo $_SESSION['event_data']['eventDateStart'] . ' - ' . $_SESSION['event_data']['eventDateEnd']; ?></li>
                                <li>Time: <?php echo $_SESSION['event_data']['eventTimeStart'] . ' - ' . $_SESSION['event_data']['eventTimeEnd']; ?></li>
                                <li>Event Type: <?php echo $_SESSION['event_data']['eventType']; ?></li>
                                <li>Event Mode: <?php echo $_SESSION['event_data']['eventMode']; ?></li>
                                <?php if ($_SESSION['event_data']['eventMode'] !== 'Face-to-Face') : ?>
                                    <li>Event link: <a href="<?php echo $_SESSION['event_data']['eventLink']; ?>" target="_blank"><?php echo $_SESSION['event_data']['eventLink']; ?></a></li>
                                <?php endif; ?>
                                <?php if ($_SESSION['event_data']['eventMode'] === 'Hybrid' || $_SESSION['event_data']['eventMode'] === 'Face-to-Face') : ?>
                                <li>Location: <?php echo $_SESSION['event_data']['eventLocation']; ?></li>
                                <?php endif; ?>
                                <li>Status: <?php echo $_SESSION['event_data']['eventStatus']; ?></li>
                                <?php if ($_SESSION['event_data']['eventStatus'] === 'Cancelled') : ?>
                                    <li>Reason for Cancellation: <?php echo $_SESSION['event_data']['cancelReason']; ?></li>
                                <?php endif; ?>
                            </ul>    
                        </div>
                    </div>

                </section>
                <!-- view event ends-->

            </div>


        </div>


        


        <!--JS -->
        <script src="js/eventscript.js"></script>


        <!--sidebar functionality-->
        <script src="js/sidebar.js"></script>




        <script>

            let dropdown_items = document.querySelectorAll('.event-filter form .dropdown-container .dropdown .lists .items');

            dropdown_items.forEach(items =>{
                items.onclick = () =>{
                    items_parent = items.parentElement.parentElement;
                    let output = items_parent.querySelector('.output');
                    output.value = items.innerText;
                }
            });

        </script>
    </body>


</html>