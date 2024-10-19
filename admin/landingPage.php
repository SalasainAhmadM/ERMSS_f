
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
            session_start();
            require_once('../db.connection/connection.php');

            function countPendingUsers($conn)
            {
                $sqls = "SELECT COUNT(*) AS totalPendingUsers FROM pendinguser";
                $result = $conn->query($sqls);

                if ($result) {
                    $row = $result->fetch_assoc();
                    return $row['totalPendingUsers'];
                } else {
                    return 0; 
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Check if AdminID is set in the session
                if (isset($_SESSION['AdminID'])) {
                    $AdminID = $_SESSION['AdminID'];

                    $sqlAdmin = "SELECT * FROM admin WHERE AdminID = ?";
                    $stmtAdmin = $conn->prepare($sqlAdmin);
                    $stmtAdmin->bind_param("i", $AdminID); 
                    $stmtAdmin->execute();
                    $resultAdmin = $stmtAdmin->get_result();

                    if ($resultAdmin->num_rows > 0) {
                        while ($row = $resultAdmin->fetch_assoc()) {
                            $LastName = $row['LastName'];
                            $FirstName = $row['FirstName'];
                            $MI = $row['MI'];
                            $Email = $row['Email'];
                            $ContactNo = $row['ContactNo'];
                            $Position = $row['Position']; 
                            $Affiliation = $row['Affiliation'];
                            $Image = $row['Image'];

                        }
                    } else {
                        echo "No records found";
                    }

                    $stmtAdmin->close();

                    $pendingUsersCount = countPendingUsers($conn);
                
                }
            }
        ?>

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
                <h3 class="dashboard">EVENTS</h3>

                <section class="event-filter">

                    <h1 class="heading"></h1>
                    <h1 class="heading">filter events</h1>
                    
                    <form action="" method="post">
                        
                        <div class="dropdown-container">
                            <div class="dropdown">
                                
                                <input type="text" readonly name="eventDisplay" placeholder="Filter" maxlength="20" class="output">
                                <div class="lists">
                                        
                                    <a href="landingPage2.php"><p class="items">Grid</p></a>
                                </div>
                            </div>
                        </div>
                        
                    </form>

                </section>

            </div>


            <div class="containerr">
                <!--========= all event start =============-->
                

                <!-- ALL EVENTS TABULAR FORM-->
                <div class="event-table">
                    <div class="tbl-container">
                        <h2>Events</h2>
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
                                    <th colspan="3">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php include('../function/F.getEventTbl.php'); ?>
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
