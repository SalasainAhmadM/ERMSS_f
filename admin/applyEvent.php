<?php
    include('../function/F.addParticipant.php');

    $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;

    // Fetch distinct years from the Events table
    $yearQuery = "SELECT DISTINCT YEAR(date_start) AS event_year FROM Events ORDER BY event_year DESC";
    $yearResult = mysqli_query($conn, $yearQuery);
    $years = mysqli_fetch_all($yearResult, MYSQLI_ASSOC);

    $totalEventsQuery = "SELECT COUNT(*) AS totalEvents FROM Events";
    $totalEventsResult = mysqli_query($conn, $totalEventsQuery);
    $totalEvents = mysqli_fetch_assoc($totalEventsResult)['totalEvents'];

    $totalUpcomingQuery = "SELECT COUNT(*) AS totalUpcoming FROM Events WHERE (NOW() < CONCAT(date_start, ' ', time_start)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalUpcomingResult = mysqli_query($conn, $totalUpcomingQuery);
    $totalUpcoming = mysqli_fetch_assoc($totalUpcomingResult)['totalUpcoming'];

    $totalOngoingQuery = "SELECT COUNT(*) AS totalOngoing FROM Events WHERE (NOW() BETWEEN CONCAT(date_start, ' ', time_start) AND CONCAT(date_end, ' ', time_end)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalOngoingResult = mysqli_query($conn, $totalOngoingQuery);
    $totalOngoing = mysqli_fetch_assoc($totalOngoingResult)['totalOngoing'];

    $totalEndedQuery = "SELECT COUNT(*) AS totalEnded FROM Events WHERE (NOW() > CONCAT(date_end, ' ', time_end)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalEndedResult = mysqli_query($conn, $totalEndedQuery);
    $totalEnded = mysqli_fetch_assoc($totalEndedResult)['totalEnded'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('../db.connection/connection.php');
    
        if (isset($_SESSION['UserID'])) {
            $UserID = $_SESSION['UserID'];
            $eventId = isset($_POST['event_id']) ? $_POST['event_id'] : null;
            $participantId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    
            if ($eventId && $participantId) {
                // Check if the participant is already enrolled
                $checkParticipantQuery = "SELECT COUNT(*) AS participant_count FROM EventParticipants WHERE event_id = ? AND UserID = ?";
                $stmtCheckParticipant = $conn->prepare($checkParticipantQuery);
                $stmtCheckParticipant->bind_param("ii", $eventId, $participantId);
                $stmtCheckParticipant->execute();
                $resultCheckParticipant = $stmtCheckParticipant->get_result();
                $participantData = $resultCheckParticipant->fetch_assoc();
    
                if ($participantData['participant_count'] > 0) {
                    // Participant already enrolled
                    echo "<script>alert('The participant is already added to the event.'); window.location.href='';</script>";
                } else {
                    // Check the participant limit
                    $checkLimitQuery = "SELECT participant_limit, 
                                        (SELECT COUNT(*) FROM EventParticipants WHERE event_id = ?) AS current_participants 
                                        FROM Events WHERE event_id = ?";
                    $stmtLimit = $conn->prepare($checkLimitQuery);
                    $stmtLimit->bind_param("ii", $eventId, $eventId);
                    $stmtLimit->execute();
                    $resultLimit = $stmtLimit->get_result();
                    $eventData = $resultLimit->fetch_assoc();
    
                    if ($eventData['current_participants'] < $eventData['participant_limit']) {
                        // If participant limit is not reached, proceed with insertion
                        $sqlInsert = "INSERT INTO EventParticipants (event_id, UserID) VALUES (?, ?)";
                        $stmtInsert = $conn->prepare($sqlInsert);
                        $stmtInsert->bind_param("ii", $eventId, $participantId);
    
                        if ($stmtInsert->execute()) {
                            echo "<script>alert('Successfully joined the event!'); window.location.href='';</script>";
                        } else {
                            echo "<script>alert('Failed to join the event. Please try again.'); window.location.href='';</script>";
                        }
    
                        $stmtInsert->close();
                    } else {
                        // Participant limit reached
                        echo "<script>alert('This event has reached its participant limit.'); window.location.href='';</script>";
                    }
    
                    $stmtLimit->close();
                }
    
                $stmtCheckParticipant->close();
            }
        } else {
            header("Location: ../login.php");
            exit();
        }
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
                    <a href="adminDashboard.php">
                        <i class="bx bxs-grid-alt"></i>
                        <span class="nav-item">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>
                
                <li class="events-side2 nav-sidebar">
                    <a href="#" class="a-events">
                        <i class='bx bx-archive'></i>
                        <span class="nav-item">Events</span>
                        <i class='bx bx-chevron-down hide'></i>
                    </a>
                    <span class="tooltip">Events</span>
                    <div class="uno">
                        <ul>
                            <a href="landingPage.php">Events</a>
                            <a href="addEvent.php">Add Event</a>
                            <a href="addEventTypeMode.php">Event Settings</a>
                            <a href="history.php">History</a>
                            <a href="cancelEvent.php">Cancelled</a>
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
                            <a href="validation.php">User Validation <span><?php echo $pendingUsersCount;  ?></span></a>
                            <a href="newAccount.php">Create Account</a>
                            <!-- <a href="accountSettings.php">Account Settings</a> -->
                            <a href="allUser.php">All Users</a>
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
        <div class="tables container">
            <div class="table_header">
                <p>All Users</p>
                <div>
                    <input class="tb_input" placeholder="Search..." oninput="filterTable()">
                    <button class="add_new"> <?php echo $totalUsersCount; ?> Users</button>
                </div>
            </div>
            <div class="table_section">
                <table id="userTable" class="tb_eco">
                    <thead class="tb_head">
                        <tr>
                            <!-- <th style="width: 7%">ID</th> -->
                            <th>Name</th>
                            <th style="width: 7%">Age</th>
                            <th style="width: 10%">Gender</th>
                            <th>Email</th>
                            <th>Occupation</th>
                            <th>Affiliation</th>
                            <th style="width: 9%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <!-- <td><?php echo $user['UserID']; ?></td> -->
                                <td><?php echo $user['FirstName'] . ' ' . $user['LastName']; ?></td>
                                <td><?php echo $user['Age']; ?></td>
                                <td><?php echo $user['Gender']; ?></td>
                                <td><?php echo $user['Email']; ?></td>
                                <td><?php echo $user['Position']; ?></td>
                                <td><?php echo $user['Affiliation']; ?></td>
                                <td>
                                    <!-- Ensure the event_id is dynamically set here -->
                                    <form action="" method="post">
                                    <button type="submit" class="action-button"><i class="fa-solid fa-plus"></i></button>
                                        <input type="hidden" name="event_id" value="<?php echo $eventId; ?>"> <!-- Pass the correct event ID here -->
                                        <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>"> <!-- Pass the user ID to the form -->
 
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><tbody>
                
</div>
        

    

        <!--FILTER TABLE ROW-->
        <script>
            function filterTable() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.querySelector('.tb_input');
                filter = input.value.toUpperCase();
                console.log("Filter text:", filter); 

                table = document.getElementById("userTable");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1]; 
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        console.log("Text content:", txtValue);
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }

        </script>

        


        

   

        <!--JS -->
        <script src="js/eventscript.js"></script>


        <!--sidebar functionality-->
        <script src="js/sidebar.js"></script>

        <!--chart js-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <script src="js/myChart.js"></script>

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




</html>