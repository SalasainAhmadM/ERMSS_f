<?php
    include('../function/F.addParticipant.php');

    $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;


    function showAlert($message, $redirectPath = null) {
        echo "<script>alert('$message');";
        if ($redirectPath) {
            echo "window.location.href = '$redirectPath';";
        }
        echo "</script>";
    }
    
    function getUserData($conn, $UserID) {
        $sql = "SELECT * FROM user WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $UserID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        } else {
            $stmt->close();
            return false;
        }
    }

    $eventLimitQuery = "SELECT participant_limit FROM Events WHERE event_id = ?";
    $stmtLimit = $conn->prepare($eventLimitQuery);
    $stmtLimit->bind_param("i", $eventId);
    $stmtLimit->execute();
    $eventLimitResult = $stmtLimit->get_result();
    $eventLimit = $eventLimitResult->fetch_assoc()['participant_limit'];
    $stmtLimit->close();

    $participantCountQuery = "SELECT COUNT(*) AS currentParticipants FROM EventParticipants WHERE event_id = ?";
    $stmtCount = $conn->prepare($participantCountQuery);
    $stmtCount->bind_param("i", $eventId);
    $stmtCount->execute();
    $participantCountResult = $stmtCount->get_result();
    $currentParticipants = $participantCountResult->fetch_assoc()['currentParticipants'];
    $stmtCount->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('../db.connection/connection.php');
    
        if (isset($_SESSION['UserID'])) {
            $UserID = $_SESSION['UserID'];
            $eventId = isset($_POST['event_id']) ? $_POST['event_id'] : null;
    
            echo "UserID: $UserID, EventID: $eventId";  // Debugging line
    
            if ($currentParticipants >= $eventLimit) {
                echo "<script>alert('Participant limit reached!'); window.location.href='';</script>";
            } else {
                $sqlInsert = "INSERT INTO EventParticipants (event_id, UserID) VALUES (?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bind_param("ii", $eventId, $UserID);
    
                if ($stmtInsert->execute()) {
                    echo "<script>alert('Successfully added to the event!'); window.location.href='';</script>";
                } else {
                    echo "<script>alert('Failed to join the event. Please try again.'); window.location.href='';</script>";
                }
    
                $stmtInsert->close();
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
                
                <li class="events-side nav-sidebar">
                    <a href="#" class="a-events">
                        <i class='bx bx-archive'></i>
                        <span class="nav-item">Events</span>
                        <i class='bx bx-chevron-down hide'></i>
                    </a>
                    <span class="tooltip">Events</span>
                    <div class="uno">
                        <ul>
                             <?php if ($_SESSION['Role'] === 'superadmin') { ?>
                            <a href="eventsValidation.php">Events Validation <span><?php echo $pendingEventsCount; ?></span></a>
                            <?php } elseif ($_SESSION['Role'] === 'Admin') { ?>
                                <a href="pendingEvents.php">Pending Events <span><?php echo $pendingEventsCount; ?></span></a>
                            <?php } ?>
                            <a href="landingPage.php">Events</a>
                            <a href="addEvent.php">Add Event</a>
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
                            <th>ID</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Affiliation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td>Event ID: <?php echo $eventId; ?></td>
                                <td><?php echo $user['UserID']; ?></td>
                                <td><?php echo $user['FirstName'] . ' ' . $user['LastName']; ?></td>
                                <td><?php echo $user['Email']; ?></td>
                                <td><?php echo $user['Affiliation']; ?></td>
                                <td>
                                    <form action="" method="post">
                                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                                    <button type="submit" class="action-button"><i class="fa-solid fa-plus"></i></button>
                                    </form>
                                    <!-- <button class="action-button" data-userid="<?php echo $user['UserID']; ?>" data-eventid="<?php echo $eventId; ?>"><i class="fa-solid fa-plus"></i></button> -->
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