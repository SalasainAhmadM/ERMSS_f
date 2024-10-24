<?php
session_start();
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if UserID is set in the session
    if (isset($_SESSION['UserID'])) {
        $UserID = $_SESSION['UserID'];

        // Fetch user data
        $sqlUser = "SELECT * FROM user WHERE UserID = ?";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("i", $UserID);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();

        if ($resultUser->num_rows > 0) {
            // Fetch the user details
            while ($row = $resultUser->fetch_assoc()) {
                $LastName = $row['LastName'];
                $FirstName = $row['FirstName'];
                $MI = $row['MI'];
                $Position = $row['Position'];
                $Image = $row['Image'];
                // User data can now be used
            }
        }

        // Fetch canceled events based on the cancel_reason table
        $sqlCancelEvents = "
            SELECT e.event_id, e.event_title, e.event_type, e.event_mode, e.location, e.date_start, e.date_end, e.time_start, e.time_end, cr.description 
            FROM cancel_reason cr 
            JOIN events e ON cr.event_id = e.event_id 
            WHERE cr.UserID = ?";

        $stmtCancelEvents = $conn->prepare($sqlCancelEvents);
        $stmtCancelEvents->bind_param("i", $UserID);
        $stmtCancelEvents->execute();
        $resultCancelEvents = $stmtCancelEvents->get_result();

        $cancelledEvents = [];

        if ($resultCancelEvents->num_rows > 0) {
            // Loop through the canceled events
            while ($event = $resultCancelEvents->fetch_assoc()) {
                $cancelledEvents[] = [
                    'event_id' => $event['event_id'],
                    'event_title' => $event['event_title'],
                    'location' => $event['location'],
                    'event_type' => $event['event_type'],
                    'event_mode' => $event['event_mode'],
                    'date_start' => $event['date_start'],
                    'date_end' => $event['date_end'],
                    'time_start' => $event['time_start'],
                    'time_end' => $event['time_end'],
                    'cancel_reason' => $event['description']
                ];
            }
        } else {
            // No canceled events found
            $cancelledEvents = [];
        }


    } else {
        // Redirect to login if UserID is not set
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


    <!-- ====SIDEBAR==== -->
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
            <h3 class="dashboard"></h3>
        </div>


        <div class="containerr">
            <!--========= all event start =============-->


            <!-- ALL EVENTS TABULAR FORM-->
            <div class="event-table">
                <div class="tbl-container">
                    <h2>Cancelled Events</h2>
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Event Type</th>
                                <th>Event Mode</th>
                                <th>Event Location</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Reason</th>
                                <!-- <th colspan="3">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (!empty($cancelledEvents)) {

                                foreach ($cancelledEvents as $row) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['event_title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['event_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['event_mode']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                    echo "<td>" . date("F j, Y", strtotime($row['date_start'])) . " - " . date("F j, Y", strtotime($row['date_end'])) . "</td>";
                                    echo "<td>" . date("h:i a", strtotime($row['time_start'])) . " - " . date("h:i a", strtotime($row['time_end'])) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['cancel_reason']) . "</td>";
                                    // echo "<td><a href='view_cancel.php?event_id=" . $row['event_id'] . "'><button class='btn_edit'><i class='fa-solid fa-eye'></i></button></a></td>";
                                    echo "</tr>";
                                }
                            } else {

                                echo "<tr><td colspan='8'>No canceled events found for this user.</td></tr>";
                            }
                            ?>
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