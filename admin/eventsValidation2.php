
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Management System</title>

        <!--boxicons-->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    return 0; // Return 0 if there is an error or no pending users
                }
            }
            function countPendingEvents($conn)
            {
                $sqls = "SELECT COUNT(*) AS totalPendingEvents FROM pendingevents";
                $result = $conn->query($sqls);
            
                if ($result) {
                    $row = $result->fetch_assoc();
                    return $row['totalPendingEvents'];
                } else {
                    return 0; 
                }
            }
            
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Check if AdminID is set in the session
                if (isset($_SESSION['AdminID'])) {
                    $AdminID = $_SESSION['AdminID'];

                    // Prepare and execute a query to fetch the specific admin's data
                    $sqlAdmin = "SELECT * FROM admin WHERE AdminID = ?";
                    $stmtAdmin = $conn->prepare($sqlAdmin);
                    $stmtAdmin->bind_param("i", $AdminID); // Assuming AdminID is an integer
                    $stmtAdmin->execute();
                    $resultAdmin = $stmtAdmin->get_result();

                    if ($resultAdmin->num_rows > 0) {
                        while ($row = $resultAdmin->fetch_assoc()) {
                            $LastName = $row['LastName'];
                            $FirstName = $row['FirstName'];
                            $MI = $row['MI'];
                            $Email = $row['Email'];
                            $ContactNo = $row['ContactNo'];
                            $Position = $row['Position']; // Corrected the column name
                            $Affiliation = $row['Affiliation'];
                            $Image = $row['Image'];
                            $Role = $row['Role']; 
                        }
                    } else {
                        echo "No records found";
                    }

                    $stmtAdmin->close();

                    // Example usage of the countPendingUsers function
                    $pendingUsersCount = countPendingUsers($conn);
                    $pendingEventsCount = countPendingEvents($conn);
                }
            }
        ?>
<?php
if (isset($_SESSION['success'])) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: '" . $_SESSION['success'] . "',
            icon: 'success'
        });
    </script>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<script>
        Swal.fire({
          title: 'Error!',
          text: '" . $_SESSION['error'] . "',
          icon: 'error'
        });
    </script>";
    unset($_SESSION['error']);
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
                
                <li class="events-side2 nav-sidebar">
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
                            <a href="allUser.php">All Users</a>
                            <!-- <a href="accountSettings.php">Account Settings</a> -->
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

                <!--======= event filter starts ======= -->
                <section class="event-filter"> <!--dapat naka drop down ito-->

                <h1 class="heading"></h1>
                <h1 class="heading">filter events</h1>

                <div style="display: flex; gap: 10px; margin-bottom:10px"> 

                    <form action="" method="post" style="margin-bottom:1rem; height:10%">
                        
                        <div class="dropdown-container">
                            <div class="dropdown">
                                
                                <input type="text" readonly name="eventDisplay" placeholder="Filter" maxlength="20" class="output">
                                <div class="lists">
                                        
                                    <a href="landingPage.php"><p class="items">List</p></a>
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
                                <input type="text" readonly name="eventType" placeholder="event type" maxlength="20" class="output">
                                <div class="lists">
                                    <?php
                                    // Fetch all distinct event types from the database
                                    $sqlEventType = "SELECT DISTINCT event_type FROM pendingevents";
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
                
                </section>
                <!-- ======= event filter ends ========-->

            </div>


            <div class="containerr">
                <!--========= all event start =============-->
                <section class="event-container">

                    <h1 class="heading">events</h1>
 
                    <div class="box-container" style="display: flex;flex-wrap: wrap;">
                        <!--just include the F.getEvent.php for grid display-->
                        <?php include('../function/F.getEvent3.php'); ?>
                                           
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


    <!--real-time update-->
    <script src="js/realTimeUpdate.js"></script>

</html>

