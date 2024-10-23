<?php session_start(); // Start the session
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

    require_once('../db.connection/connection.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check if UserID is set in the session
        if (isset($_SESSION['UserID'])) {
            $UserID = $_SESSION['UserID'];

            // Prepare and execute a query to fetch the specific admin's data
            $sql = "SELECT * FROM user WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $UserID); // Assuming UserID is an integer
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $LastName = $row['LastName'];
                    $FirstName = $row['FirstName'];
                    $MI = $row['MI'];
                    $Position = $row['Position']; // Corrected the column name
                    $Image = $row['Image'];

                    // Now, you have the specific admin's data
                }
            } else {
                echo "No records found";
            }

            $stmt->close();

            // Retrieve the total number of events joined by the user
            $countEventsSql = "SELECT COUNT(*) AS totalEventsJoined FROM EventParticipants WHERE UserID = ?";
            $countEventsStmt = $conn->prepare($countEventsSql);
            $countEventsStmt->bind_param("i", $UserID);
            $countEventsStmt->execute();
            $countEventsResult = $countEventsStmt->get_result();
            $countEventsRow = $countEventsResult->fetch_assoc();
            $totalEventsJoined = $countEventsRow['totalEventsJoined'];


            //retrieve total number of upcoming events
            $sqlUpdateCountUpcomingEvents = "SELECT COUNT(*) AS totalUpcomingEvents FROM Events
                                 INNER JOIN EventParticipants ON Events.event_id = EventParticipants.event_id
                                 WHERE EventParticipants.UserID = ? 
                                 AND Events.date_end >= NOW() 
                                 AND Events.date_start > NOW()";
            $stmtUpdateCountUpcomingEvents = $conn->prepare($sqlUpdateCountUpcomingEvents);
            $stmtUpdateCountUpcomingEvents->bind_param("i", $UserID);
            $stmtUpdateCountUpcomingEvents->execute();
            $resultUpdateCountUpcomingEvents = $stmtUpdateCountUpcomingEvents->get_result();
            $rowUpdateCountUpcomingEvents = $resultUpdateCountUpcomingEvents->fetch_assoc();
            $totalUpcomingEvents = $rowUpdateCountUpcomingEvents['totalUpcomingEvents'];

            // Update the count in the session
            $_SESSION['totalUpcomingEvents'] = $totalUpcomingEvents;




            // Retrieve the total number of ongoing events joined by the user
            $countOngoingEventsSql = "SELECT COUNT(*) AS totalOngoingEvents FROM Events
                    INNER JOIN EventParticipants ON Events.event_id = EventParticipants.event_id
                    WHERE EventParticipants.UserID = ? AND Events.date_end >= NOW() AND Events.date_start <= NOW()
                    ORDER BY Events.date_created DESC";
            $countOngoingEventsStmt = $conn->prepare($countOngoingEventsSql);
            $countOngoingEventsStmt->bind_param("i", $UserID);
            $countOngoingEventsStmt->execute();
            $countOngoingEventsResult = $countOngoingEventsStmt->get_result();
            $countOngoingEventsRow = $countOngoingEventsResult->fetch_assoc();
            $totalOngoingEvents = $countOngoingEventsRow['totalOngoingEvents'];

            // Fetch total number of cancelled events
            $countCancelledEventsSql = "SELECT COUNT(*) AS totalCancelledEvents FROM Events WHERE event_cancel IS NOT NULL AND event_cancel <> ''";
            $countCancelledEventsResult = mysqli_query($conn, $countCancelledEventsSql);
            $countCancelledEventsRow = mysqli_fetch_assoc($countCancelledEventsResult);
            $totalCancelledEvents = $countCancelledEventsRow['totalCancelledEvents'];

        } else {
            // Redirect to login page or handle the case where UserID is not set in the session
            header("Location: login.php");
            exit();
        }
    }
    function countCanceledEvents($conn)
    {
        $sql = "
            SELECT COUNT(DISTINCT e.event_id) AS totalCanceledEvents 
            FROM events e
            INNER JOIN cancel_reason cr ON e.event_id = cr.event_id";

        $result = $conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['totalCanceledEvents'];
        } else {
            return 0;
        }
    }

    // Call the function to get the number of canceled events
    $canceledEventsCount = countCanceledEvents($conn);
    ?>

    <!--=========== SIDEBAR =============-->
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <img src="img/wesmaarrdec-removebg-preview.png" alt="">
                <span>WESMAARRDEC</span>
            </div>
            <i class="bx bx-menu" id="btnn"></i>
        </div>
        <div class="user">
            <?php if (!empty($Image)): ?>
                <img src="../assets/img/profilePhoto/<?php echo $Image; ?>" alt="user" class="user-img">
            <?php else: ?>
                <img src="../assets/img/profile.jpg" alt="default user" class="user-img">
            <?php endif; ?>
            <div>
                <p class="bold"><?php echo $FirstName . ' ' . $MI . ' ' . $LastName; ?></p>
                <p><?php echo $Position; ?></p>
            </div>
        </div>


        <ul>
            <li class="nav-sidebar">
                <a href="userDashboard.php  ">
                    <i class="bx bxs-grid-alt"></i>
                    <span class="nav-item">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>

            <li class="events-side first nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-archive'></i>
                    <span class="nav-item">Events</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Events</span>
                <div class="uno">
                    <ul>
                        <a href="landingPageU.php">Join Event</a>
                        <a href="history.php">History</a>
                        <a href="cancelEventU.php">Cancelled <span><?php echo $canceledEventsCount; ?></span></a>
                    </ul>
                </div>
            </li>

            <li class="events-side nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-user'></i>
                    <span class="nav-item">Account</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Account</span>
                <div class="uno">
                    <ul>
                        <a href="profile.php">My Profile</a>
                    </ul>
                </div>
            </li>

            <li class="nav-sidebar">
                <a href="../login.php">
                    <i class="bx bx-log-out"></i>
                    <span class="nav-item">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
        </ul>
    </div>


    <!-- ============ CONTENT ============-->
    <div class="main-content">
        <div class="containerr">
            <h3 class="dashboard">DASHBOARD</h3>

            <!--======= event filter starts ======= -->
            <section class="event-filter"> <!--dapat naka drop down ito-->

                <h1 class="heading">My Upcoming Events</h1>

            </section>
            <!-- ======= event filter ends ========-->

        </div>


        <div class="containerr">
            <!--========= all event start =============-->
            <section class="category">

                <div class="box-container">

                    <a href="total_events.php" class="box">
                        <i class='bx bx-archive'></i>
                        <div>
                            <!-- <h3>Total</h3> -->
                            <h3>All</h3>
                            <span>Events Joined</span>
                        </div>
                    </a>

                    <a href="my_upcoming.php" class="box">
                        <i class='bx bx-archive'></i>
                        <div>
                            <h3>Upcoming</h3>
                            <span>Events</span>
                        </div>
                    </a>

                    <a href="my_ongoing.php" class="box">
                        <i class='bx bx-archive'></i>
                        <div>
                            <h3>Ongoing</h3>
                            <span>Events</span>
                        </div>
                    </a>

                </div>
            </section>




            <!-- ALL EVENTS TABULAR FORM-->
            <div class="event-table">
                <div class="tbl-container">
                    <h2></h2>
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Event Type</th>
                                <th>Event Mode</th>
                                <th>Event Location</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php include('../function/F.upcomingEventsU.php'); ?>
                        </tbody>
                    </table>
                </div>
            </div>


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


<!--real-time update-->
<script src="js/realTimeUpdate.js"></script>

</html>