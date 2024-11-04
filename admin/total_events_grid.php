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

$pendingUsersCount = countPendingUsers($conn);
$pendingEventsCount = countPendingEvents($conn);
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
        <?php
        session_start();

        if (isset($_SESSION['AdminID'])) {
            $adminID = $_SESSION['AdminID'];

            $query = "SELECT FirstName, MI, LastName, Position, Image FROM admin WHERE AdminID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $adminID);
            $stmt->execute();
            $stmt->bind_result($FirstName, $MI, $LastName, $Position, $Image);
            $stmt->fetch();
            $stmt->close();

            // Use the variables in your HTML output
            ?>
            <div class="user">
                <?php if (!empty($Image)): ?>
                    <img src="../assets/img/profilePhoto/<?php echo htmlspecialchars($Image); ?>" alt="user" class="user-img">
                <?php else: ?>
                    <img src="../assets/img/profile.jpg" alt="default user" class="user-img">
                <?php endif; ?>
                <div>
                    <p class="bold">
                        <?php echo htmlspecialchars($FirstName) . ' ' . htmlspecialchars($MI) . ' ' . htmlspecialchars($LastName); ?>
                    </p>
                    <p><?php echo htmlspecialchars($Position); ?></p>
                </div>
            </div>
            <?php
        } else {
            echo "User not logged in.";
        }
        ?>


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
                            <a href="eventsValidation.php">Events Validation
                                <span><?php echo $pendingEventsCount; ?></span></a>
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
                        <a href="validation.php">User Validation <span><?php echo $pendingUsersCount; ?></span></a>
                        <a href="newAccount.php">Create Account</a>
                        <a href="allUser.php">All Users</a>
                        <!-- <a href="accountSettings.php">Account Settings</a> -->
                    </ul>
                </div>
            </li>

            <li class="nav-sidebar">
                <a href="#" onclick="confirmLogout(event)">
                    <i class="bx bx-log-out"></i>
                    <span class="nav-item">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>

            <script>
                function confirmLogout(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You will be logged out.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, logout'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../login.php";
                        }
                    });
                }
            </script>
        </ul>
    </div>

    <!-- ============ CONTENT ============-->
    <div class="main-content">

        <div class="containerr">
            <h3 class="dashboard apply">EVENT MANAGEMENT</h3>

            <section class="category">

                <div class="box-container">

                    <a href="#" class="box">
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
        <style>
            .event-filter form .flex .box .input {
                width: 100%;
                border-radius: .5rem;
                color: var(--first-color-alt);
                margin: 0;
                background-color: var(--light-bg);
                font-size: 1.8rem;

            }

            .flex2 {
                display: flex;
            }

            /* Add this CSS */

            @media (max-width: 768px) {
                .event-filter {
                    display: flex;
                    flex-direction: column;
                }

                .event-filter form {
                    width: 100%;
                }

                .dropdown-container,
                .flex {
                    width: 100%;
                }

                .flex2 {
                    display: flex;
                    flex-direction: column;
                }

            }
        </style>

        <section class="event-filter"> <!--dapat naka drop down ito-->

            <div class="flex2" style=" gap: 10px; margin-bottom:10px">

                <form action="" method="post" style="margin-bottom:1rem; height:10%">

                    <div class="dropdown-container">
                        <div class="dropdown">

                            <input type="text" readonly name="eventDisplay" placeholder="Filter" maxlength="20"
                                class="output">
                            <div class="lists">

                                <a href="total_events.php">
                                    <p class="items">List</p>
                                </a>
                            </div>
                        </div>
                    </div>

                </form>

                <!-- -->
                <form action="" method="post" style="margin-bottom:1rem; height:10%">

                    <div class="flex">
                        <div class="box">
                            <input type="text" id="eventTitleInput" placeholder="Filter Event title" class="input">
                        </div>

                    </div>

                </form>

                <form action="" method="post" style="margin-bottom:1rem; height:10%">
                    <div class="dropdown-container">

                        <div class="dropdown">
                            <input type="text" readonly name="eventType" placeholder="event type" maxlength="20"
                                class="output">
                            <div class="lists">
                                <a href="#" onclick='filterEvents("All")'>
                                    <p class="items">All</p>
                                </a>
                                <?php
                                $sqlEventType = "SELECT DISTINCT event_type FROM events";
                                $resultEventType = $conn->query($sqlEventType);

                                if ($resultEventType->num_rows > 0) {
                                    while ($row = $resultEventType->fetch_assoc()) {
                                        echo "<a href='#' onclick='filterEvents(\"" . $row['event_type'] . "\")'><p class='items'>" . $row['event_type'] . "</p></a>";
                                    }
                                } else {
                                    echo "<p class='items'>No event types found</p>";
                                }
                                ?>
                            </div>
                        </div>



                    </div>

                </form>
            </div>
            <div class="flex2" style="gap: 10px; margin-bottom:10px">
                <form id="sponsorFilterForm" action="" method="post" style="margin-bottom:1rem;">
                    <div class="dropdown-container">
                        <div class="dropdown">
                            <input type="text" readonly name="sponsorDisplay" placeholder="Filter by Sponsor"
                                maxlength="20" class="output">
                            <input type="hidden" name="sponsorEventId" id="sponsorEventId" />
                            <div class="lists">
                                <p class="items" onclick='filterBySponsor("All Sponsors")'>All Sponsors</p>
                                <?php
                                require_once('../db.connection/connection.php');
                                $query = "SELECT DISTINCT sponsor_Name FROM sponsor";
                                $result = $conn->query($query);

                                while ($row = $result->fetch_assoc()) {
                                    $sponsorName = htmlspecialchars($row['sponsor_Name']);
                                    echo "<p class='items' onclick='filterBySponsor(\"$sponsorName\")'>$sponsorName</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>

                <script>
                    function filterBySponsor(sponsorName) {
                        document.querySelector('input[name="sponsorDisplay"]').value = sponsorName;
                        document.querySelector('input[name="sponsorEventId"]').value = sponsorName;
                        document.getElementById('sponsorFilterForm').submit();
                    }
                </script>


                <!-- Filter by Year -->
                <form action="" method="post" style="margin-bottom:1rem; height:10%">
                    <input type="hidden" name="selectedYear" id="selectedYear">
                    <div class="dropdown-container">
                        <div class="dropdown">
                            <input type="text" readonly name="yearDisplay" id="yearDisplay" placeholder="Filter by Year"
                                maxlength="20" class="output">
                            <div class="lists">
                                <p class="items" onclick='filterByYear("All Years")'>All Years</p>
                                <?php foreach ($years as $year): ?>
                                    <p class="items" onclick='filterByYear("<?php echo $year['event_year']; ?>")'>
                                        <?php echo htmlspecialchars($year['event_year']); ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Filter by Month -->
                <form action="" method="post" style="margin-bottom:1rem; height:10%">
                    <input type="hidden" name="selectedMonth" id="selectedMonth">
                    <div class="dropdown-container">
                        <div class="dropdown">
                            <input type="text" readonly name="monthDisplay" id="monthDisplay"
                                placeholder="Filter by Month" maxlength="20" class="output">
                            <div class="lists">
                                <p class="items" onclick='filterByMonth("All Months")'>All Months</p>
                                <!-- Month items -->
                                <?php
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                foreach ($months as $month): ?>
                                    <p class="items" onclick='filterByMonth("<?php echo $month; ?>")'><?php echo $month; ?>
                                    </p>
                                <?php endforeach; ?>
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
                        <?php include('../function/F.getAllEvents_grid.php'); ?>
                    </div>
                </section>
            </div>


            <!-- ============all event ends ========-->
    </div>
    </div>



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

        function filterByYear(year) {
            document.getElementById('yearDisplay').value = year;
            document.getElementById('selectedYear').value = year === 'All Years' ? '' : year;
            document.forms[0].submit(); // Automatically submit the form
        }

        function filterByMonth(month) {
            document.getElementById('monthDisplay').value = month;
            document.getElementById('selectedMonth').value = month === 'All Months' ? '' : month;
            document.forms[1].submit(); // Automatically submit the form
        }

    </script>




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