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
            <h3 class="dashboard">HISTORY</h3>

            <!--======= event filter starts ======= -->
            <section class="event-filter"> <!--dapat naka drop down ito-->

                <h1 class="heading">Attended Events</h1>

            </section>
            <!-- ======= event filter ends ========-->

        </div>


        <div class="containerr">
            <!--========= all event start =============-->




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
                            <?php include('../function/F.historyU.php'); ?>
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