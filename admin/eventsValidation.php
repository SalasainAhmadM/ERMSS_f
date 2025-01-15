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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/main.css">


</head>

<body>

    <?php
    require_once('../db.connection/connection.php');

    $yearQuery = "SELECT DISTINCT YEAR(date_start) AS event_year FROM Events ORDER BY event_year DESC";
    $yearResult = mysqli_query($conn, $yearQuery);
    $years = mysqli_fetch_all($yearResult, MYSQLI_ASSOC);


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
        });
    </script>";
            unset($_SESSION['error']);
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
    <!-- ============ CONTENT ============-->
    <div class="main-content">
        <div class="containerr">
            <h3 class="dashboard">EVENTS VALIDATION</h3>

            <!--======= event filter starts ======= -->
            <section class="event-filter"> <!--dapat naka drop down ito-->

                <h1 class="heading"></h1>
                <h1 class="heading">filter events</h1>

                <div class="flex2" style=" gap: 10px; margin-bottom:10px">

                    <form action="" method="post" style="margin-bottom:1rem; height:10%">

                        <div class="dropdown-container">
                            <div class="dropdown">

                                <input type="text" readonly name="eventDisplay" placeholder="Filter" maxlength="20"
                                    class="output">
                                <div class="lists">

                                    <a href="eventsValidation2.php">
                                        <p class="items">Grid</p>
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
                        <div class="dropdown-container">
                            <div class="dropdown">
                                <input type="text" readonly name="yearDisplay" id="yearDisplay"
                                    placeholder="Filter by Year" maxlength="20" class="output">
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
                        <div class="dropdown-container">
                            <div class="dropdown">
                                <input type="text" readonly name="monthDisplay" id="monthDisplay"
                                    placeholder="Filter by Month" maxlength="20" class="output">
                                <div class="lists">
                                    <p class="items" onclick='filterByMonth("All Months")'>All Months</p>
                                    <p class="items" onclick='filterByMonth("January")'>January</p>
                                    <p class="items" onclick='filterByMonth("February")'>February</p>
                                    <p class="items" onclick='filterByMonth("March")'>March</p>
                                    <p class="items" onclick='filterByMonth("April")'>April</p>
                                    <p class="items" onclick='filterByMonth("May")'>May</p>
                                    <p class="items" onclick='filterByMonth("June")'>June</p>
                                    <p class="items" onclick='filterByMonth("July")'>July</p>
                                    <p class="items" onclick='filterByMonth("August")'>August</p>
                                    <p class="items" onclick='filterByMonth("September")'>September</p>
                                    <p class="items" onclick='filterByMonth("October")'>October</p>
                                    <p class="items" onclick='filterByMonth("November")'>November</p>
                                    <p class="items" onclick='filterByMonth("December")'>December</p>

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
                            <?php include('../function/F.getEventTbl2.php'); ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- ============all event ends ========-->
        </div>
    </div>


    <!-- CONFIRM DELETE -->
    <!-- <script src=js/deleteEvent.js></script> -->


    <!--JS -->
    <script src="js/eventscript.js"></script>


    <!--sidebar functionality-->
    <script src="js/sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--filter event-->
    <script src="js/event_filter.js"></script>


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

        function filterByYear(year) {
            const rows = document.querySelectorAll('.event-table tbody tr');
            rows.forEach(row => {
                const eventYear = row.getAttribute('data-start-date').split('-')[0];
                row.style.display = (year === 'All Years' || eventYear === year) ? '' : 'none';
            });
        }

        function filterByMonth(month) {
            const monthNames = ["All Months", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const monthIndex = monthNames.indexOf(month);

            const rows = document.querySelectorAll('.event-table tbody tr');
            rows.forEach(row => {
                const eventMonth = parseInt(row.getAttribute('data-start-date').split('-')[1], 10);
                row.style.display = (monthIndex === 0 || eventMonth === monthIndex) ? '' : 'none';
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


</body>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'approved') {
        Swal.fire({
            title: "Success!",
            text: "Event successfully approved!",
            icon: "success",
            customClass: {
                popup: 'larger-swal'
            }
        }).then(() => {
            const newUrl = window.location.pathname;
            window.history.replaceState(null, '', newUrl);
        });
    }

    if (status === 'cancelled') {
        Swal.fire({
            title: "Cancelled!",
            text: "Event successfully cancelled!",
            icon: "error",
            customClass: {
                popup: 'larger-swal'
            }
        }).then(() => {
            const newUrl = window.location.pathname;
            window.history.replaceState(null, '', newUrl);
        });
    }
</script>

<!--real-time update-->
<script src="js/realTimeUpdate.js"></script>

</html>