<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

    // Fetch distinct years from the Events table
    $yearQuery = "SELECT DISTINCT YEAR(date_start) AS event_year FROM Events ORDER BY event_year DESC";
    $yearResult = mysqli_query($conn, $yearQuery);
    $years = mysqli_fetch_all($yearResult, MYSQLI_ASSOC);


    // Fetch total events
    $totalEventsQuery = "SELECT COUNT(*) AS totalEvents FROM Events";
    $totalEventsResult = mysqli_query($conn, $totalEventsQuery);
    $totalEvents = mysqli_fetch_assoc($totalEventsResult)['totalEvents'];

    // Fetch total upcoming events (excluding cancelled events)
    $totalUpcomingQuery = "SELECT COUNT(*) AS totalUpcoming FROM Events WHERE (NOW() < CONCAT(date_start, ' ', time_start)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalUpcomingResult = mysqli_query($conn, $totalUpcomingQuery);
    $totalUpcoming = mysqli_fetch_assoc($totalUpcomingResult)['totalUpcoming'];


    // Fetch total ongoing events (excluding cancelled events)
    $totalOngoingQuery = "SELECT COUNT(*) AS totalOngoing FROM Events WHERE (NOW() BETWEEN CONCAT(date_start, ' ', time_start) AND CONCAT(date_end, ' ', time_end)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalOngoingResult = mysqli_query($conn, $totalOngoingQuery);
    $totalOngoing = mysqli_fetch_assoc($totalOngoingResult)['totalOngoing'];

    // Fetch total ended events (excluding cancelled events)
    $totalEndedQuery = "SELECT COUNT(*) AS totalEnded FROM Events WHERE (NOW() > CONCAT(date_end, ' ', time_end)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalEndedResult = mysqli_query($conn, $totalEndedQuery);
    $totalEnded = mysqli_fetch_assoc($totalEndedResult)['totalEnded'];
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

                            // Now, you have the specific admin's data
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
                <h3 class="dashboard apply">EVENT MANAGEMENT</h3>

                <section class="category">

                    <div class="box-container">

                        <a href="total_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Total</h3>
                                <span><?php echo $totalEvents; ?> Events</span>
                            </div>
                        </a>

                        <a href="upcoming_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Upcoming</h3>
                                <span><?php echo $totalUpcoming; ?> Events</span>
                            </div>
                        </a>

                        <a href="ongoing_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Ongoing</h3>
                                <span><?php echo $totalOngoing; ?> Events</span>
                            </div>
                        </a>

                        <a href="ended_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Completed</h3>
                                <span><?php echo $totalEnded; ?> Events</span>
                            </div>
                        </a>
                    </div>
                </section>

                
            </div>


            <div class="containerr">
                <!--========= all event start =============-->
                

                <!-- ALL EVENTS TABULAR FORM-->
                <div class="event-table">
                    <div class="tbl-container">
                        <h2>Ongoing Events</h2>
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
                            <?php include('../function/F.ongoingEvents.php'); ?>
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


<script>
            function filterEvents(eventType) {
                // Get all rows in the events table
                const rows = document.querySelectorAll('.event-table tbody tr');
                
                rows.forEach(row => {
                    // Get the text content of the event type cell (adjust the index if necessary)
                    const eventTypeCell = row.querySelector('td:nth-child(2)').textContent;
                    
                    // If the event type matches or 'All' is selected, display the row, otherwise hide it
                    if (eventType === 'All' || eventTypeCell === eventType) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const eventTitleInput = document.getElementById('eventTitleInput');
                
                // Add an input event listener to the Event Title input
                eventTitleInput.addEventListener('input', function () {
                    const filterValue = eventTitleInput.value.toLowerCase(); // Get the input value and convert it to lowercase
                    
                    // Get all rows in the events table
                    const rows = document.querySelectorAll('.event-table tbody tr');
                    
                    // Iterate through each row and filter based on the Event Title column
                    rows.forEach(row => {
                        const eventTitleCell = row.querySelector('td[data-label="Event Title"]').textContent.toLowerCase(); // Get the event title from the specific column
                        
                        // Check if the event title contains the filter value
                        if (eventTitleCell.includes(filterValue)) {
                            row.style.display = ''; // Show the row if it matches the filter
                        } else {
                            row.style.display = 'none'; // Hide the row if it doesn't match the filter
                        }
                    });
                });
            });
            </script>


    <!--real-time update-->
    <script src="js/realTimeUpdate.js"></script>

</html>
