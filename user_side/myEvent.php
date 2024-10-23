<?php
session_start();
include('../function/F.event_retrieve.php');
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;

// Fetch total number of cancelled events
$countCancelledEventsSql = "SELECT COUNT(*) AS totalCancelledEvents FROM Events WHERE event_cancel IS NOT NULL AND event_cancel <> ''";
$countCancelledEventsResult = mysqli_query($conn, $countCancelledEventsSql);
$countCancelledEventsRow = mysqli_fetch_assoc($countCancelledEventsResult);
$totalCancelledEvents = $countCancelledEventsRow['totalCancelledEvents'];

// Fetch total participants for the specified event title
$totalParticipantsSql = "SELECT COUNT(*) AS totalParticipants FROM eventParticipants
                          WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?)";
$totalParticipantsStmt = $conn->prepare($totalParticipantsSql);
$totalParticipantsStmt->bind_param("s", $eventTitle);
$totalParticipantsStmt->execute();
$totalParticipantsResult = $totalParticipantsStmt->get_result();
$totalParticipantsRow = $totalParticipantsResult->fetch_assoc();
$totalParticipants = $totalParticipantsRow['totalParticipants'];

// Fetch participant limit for the event
$participantLimitSql = "SELECT participant_limit FROM Events WHERE event_title = ?";
$participantLimitStmt = $conn->prepare($participantLimitSql);
$participantLimitStmt->bind_param("s", $eventTitle);
$participantLimitStmt->execute();
$participantLimitResult = $participantLimitStmt->get_result();
$participantLimitRow = $participantLimitResult->fetch_assoc();
$participantLimit = $participantLimitRow['participant_limit'];


// Calculate the ratio of total participants to participant limit
$participantRatio = $totalParticipants . "/" . $participantLimit;

// Check if the form is submitted for canceling the event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_event'])) {
    require_once('../db.connection/connection.php');

    // Check if UserID is set in the session
    if (isset($_SESSION['UserID'])) {
        $UserID = $_SESSION['UserID'];

        // Prepare and execute a query to remove user data from EventParticipants table
        $sqlDelete = "DELETE FROM EventParticipants WHERE event_id = ? AND UserID = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("ii", $eventId, $UserID);

        if ($stmtDelete->execute()) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js'></script>
            <script>
        Swal.fire({
            title: 'Success!',
            text: 'Successfully Cancelled!',
            icon: 'success',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'userDashboard.php'; 
            }
        });
    </script>";
        } else {
            // Deletion failed
            echo "<script>alert('Failed to cancel the event. Please try again.'); window.location.href='userDashboard.php';</script>";
        }

        $stmtDelete->close();

        
    } else {
        // Redirect to login page or handle the case where UserID is not set in the session
        header("Location: login.php");
        exit();
    }
}

?>
<?php
if (isset($_SESSION['success'])) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: '" . $_SESSION['success'] . "',
            icon: 'success',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'userDashboard.php'; 
            }
        });
    </script>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: '" . $_SESSION['error'] . "',
            icon: 'error',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'userDashboard.php'; 
            }
        });
    </script>";
    unset($_SESSION['error']);
}
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                } else {
                    // Redirect to login page or handle the case where UserID is not set in the session
                    header("Location: login.php");
                    exit();
                }
            }
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
                            <a href="cancelEventU.php">Cancelled <span><?php echo $totalCancelledEvents; ?></span></span></a>
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

                                <li>Participants: <?php echo $participantRatio; ?> </li>
                            </ul>    
                        </div>

                        <!-- Cancel Event Form -->
                        <form action="" method="post" class="flex-btn" id="cancelEventForm">
                            <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                            <textarea name="cancel_reason" placeholder="Enter reason for cancellation..." required></textarea>
                        </form>

                        <!-- Cancel Event Button -->
                         <?php if ($_SESSION['event_data']['eventStatus'] !== 'Ongoing') : ?>
                            <form action="" method="post" class="flex-btn" id="cancelEventBtn">
                                <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                                <button type="submit" name="cancel_event" class="btn">Cancel Event</button>
                            </form>
                            <script>
                            document.getElementById('cancelEventBtn').addEventListener('click', function(e) {
                                e.preventDefault(); // Prevent the form from submitting automatically
                                 Swal.fire({
                                    title: 'Are you sure you want to cancel the event?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, cancel it!',
                                    cancelButtonText: 'No, keep it',
                                    padding: '3rem'
                                    customClass: {
                                        popup: 'larger-swal'
                                    } }).then((result) => {
                                        if (result.isConfirmed) {
                                            // If confirmed, submit the form
                                            document.getElementById('cancelEventForm').submit();
                                        }
                                    });
                                });
                            </script>
                        <?php endif; ?>



                    </div>

                </section>
      
                <!-- ============all event ends ========-->
            </div>
        </div>



        <!-- CONFIRM DELETE -->
        <!-- <script src=js/deleteEvent.js></script> -->
            

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
