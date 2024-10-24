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

            // Retrieve the total number of upcoming events joined by the user
            $countUpcomingEventsSql = "SELECT COUNT(*) AS totalUpcomingEvents FROM Events
                                            INNER JOIN EventParticipants ON Events.event_id = EventParticipants.event_id
                                            WHERE EventParticipants.UserID = ? AND Events.date_end >= NOW() AND Events.date_start > NOW()
                                            ORDER BY Events.date_created DESC";
            $countUpcomingEventsStmt = $conn->prepare($countUpcomingEventsSql);
            $countUpcomingEventsStmt->bind_param("i", $UserID);
            $countUpcomingEventsStmt->execute();
            $countUpcomingEventsResult = $countUpcomingEventsStmt->get_result();
            $countUpcomingEventsRow = $countUpcomingEventsResult->fetch_assoc();
            $totalUpcomingEvents = $countUpcomingEventsRow['totalUpcomingEvents'];


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
    function countCanceledEvents($conn, $UserID)
    {
        $sql = "
        SELECT COUNT(DISTINCT e.event_id) AS totalCanceledEvents 
        FROM events e
        INNER JOIN cancel_reason cr ON e.event_id = cr.event_id
        WHERE cr.UserID = ?"; // Ensure that the UserID matches
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $UserID); // Bind the UserID as a parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['totalCanceledEvents'];
        } else {
            return 0;
        }
    }

    // Call the function to get the number of canceled events
    $canceledEventsCount = countCanceledEvents($conn, $UserID);
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

                <h1 class="heading">All Joined Events</h1>

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


            <section class="event-filter"> <!--dapat naka drop down ito-->

                <h1 class="heading"></h1>
                <h1 class="heading">filter events</h1>

                <div style="display: flex; gap: 10px; margin-bottom:10px">

                    <form action="" method="post" style="margin-bottom:1rem; height:10%">

                        <div class="dropdown-container">
                            <div class="dropdown">

                                <input type="text" readonly name="eventDisplay" placeholder="Filter" maxlength="20"
                                    class="output">
                                <div class="lists">

                                    <a href="total_events.php">
                                        <p class="items">Grid</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                    <form action="" method="post" style="width:65%">
                        <div class="flex">
                            <div class="box">
                                <p>Event Title <span>*</span></p>
                                <input type="text" id="eventTitleInput" placeholder="Filter event title" class="input">
                            </div>

                            <!-- <div class="dropdown-container">
                                <div class="dropdown">
                                    <p>Display <span>*</span></p>
                                    <input type="text" readonly name="eventDisplay" placeholder="Grid" maxlength="20" class="output">
                                    <div class="lists">
                                        <a href="landingPageU.php"><p class="items">List</p></a>
                                        <a href="landingPage2.php"><p class="items">Grid</p></a>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="dropdown-container">
                            <!-- <div class="dropdown">
                                <input type="text" readonly name="eventMode" placeholder="event mode" maxlength="20" class="output">
                                <div class="lists">
                                    <p class="items">Face-to-Face</p>
                                    <p class="items">Online</p>
                                    <p class="items">Hybrid</p>
                                    <p class="items"><i class="fa-solid fa-rotate"></i></i></p>
                                </div>
                            </div> -->

                            <div class="dropdown">
                                <input type="text" readonly name="eventType" placeholder="event type" maxlength="20"
                                    class="output">
                                <div class="lists">
                                    <?php
                                    // Fetch all distinct event types from the database
                                    $sqlEventType = "SELECT DISTINCT event_type FROM events";
                                    $resultEventType = $conn->query($sqlEventType);

                                    // Check if there are rows returned
                                    if ($resultEventType->num_rows > 0) {
                                        // Loop through each row and display event types as dropdown items
                                        while ($row = $resultEventType->fetch_assoc()) {
                                            echo "<p class='items'>" . $row['event_type'] . "</p>";
                                        }
                                    } else {
                                        echo "<p class='items'>No event types found</p>";
                                    }


                                    ?>
                                    <p class="items"><i class="fa-solid fa-rotate"></i></i></p>
                                </div>
                            </div>


                        </div>

                    </form>
                </div>



                <div class="containerr">
                    <!--========= all event start =============-->
                    <section class="event-container">
                        <h2 class="heading">Events</h2>
                        <div class="box-container" style="display: flex; flex-wrap: wrap;">
                            <!-- Just include the F.getEvent.php for grid display -->
                            <?php include('../function/F.totalEventsU_grid.php'); ?>
                        </div>
                    </section>
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