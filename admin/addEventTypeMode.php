<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>

    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <link rel="stylesheet" href="css/main.css">


</head>
<style>
    .container {
        width: 100%;
        padding: 20px;
    }

    .event-wrapper {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .event-table {
        width: 48%;
        margin-bottom: 20px;
    }

    .tbl-container {
        border: 1px solid #ccc;
        padding: 20px;
        background-color: #f9f9f9;
    }

    .tbl {
        width: 100%;
        border-collapse: collapse;
    }

    .tbl th,
    .tbl td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .tbl th {
        background-color: #f2f2f2;
    }

    @media (max-width: 768px) {
        .event-wrapper {
            flex-direction: column;
        }

        .event-table {
            width: 100%;
        }

        .tbl th,
        .tbl td {
            padding: 12px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {

        .tbl th,
        .tbl td {
            padding: 10px;
            font-size: 12px;
        }

        .container {
            padding: 10px;
        }

        .tbl-container {
            padding: 15px;
        }

        .event-filter form {
            width: 60%;
            padding: 15px;
        }
    }
</style>

<body>

    <?php
    session_start();
    require_once('../db.connection/connection.php');
    $sql_event_type = "SELECT event_type_name ,event_type_id as id FROM event_type";
    $result_event_type = $conn->query($sql_event_type);

    $sql_event_mode = "SELECT event_mode_name ,event_mode_id as id FROM event_mode";
    $result_event_mode = $conn->query($sql_event_mode);
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

    <!-- ============ CONTENT ============-->
    <div class="main-content">
        <div class="containerr">
            <h3 class="dashboard">EVENTS TYPES AND MODES</h3>
            <!--======= event filter starts ======= -->
            <section class="event-filter">
                <!-- Dropdown menu for selecting between adding event type or mode -->
                <div style="display: flex; gap: 10px; margin-bottom:10px">
                    <form action="" method="post" style="margin-bottom:1rem; height:10%">
                        <div class="dropdown-container">
                            <div class="dropdown">
                                <input type="text" readonly name="eventDisplay" placeholder="Add" maxlength="20"
                                    class="output">
                                <div class="lists">
                                    <p class="items" onclick="openEventTypeModal()">Event Type</p>
                                    <p class="items" onclick="openEventModeModal()">Event Mode</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>


        </div>

        <div class="container">
            <!--========= all event start =============-->
            <div class="event-wrapper">
                <div class="event-table">
                    <div class="tbl-container">
                        <h2>Events Type</h2>
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if ($result_event_type->num_rows > 0) {
                                    while ($row = $result_event_type->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td data-label='Event Title'>" . $row['event_type_name'] . "</td>";
                                        echo "<td data-label='Action'>
                                            <button class='btn_edit' onclick=\"editEventType('" . $row['id'] . "', '" . $row['event_type_name'] . "')\"><i class='fa fa-pencil'></i></button>
                                            <button class='btn_delete' onclick=\"confirmDeleteEventType('" . $row['id'] . "')\"><i class='fa fa-trash'></i></button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No event types available</td></tr>";
                                }
                                ?>
                            </tbody>
                            <script></script>
                        </table>
                    </div>
                </div>

                <div class="event-table">
                    <div class="tbl-container">
                        <h2>Events Mode</h2>
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_event_mode->num_rows > 0) {
                                    while ($row = $result_event_mode->fetch_assoc()) {
                                        // Escape the event_mode_name to handle special characters
                                        $escapedEventModeName = htmlspecialchars($row['event_mode_name'], ENT_QUOTES, 'UTF-8');

                                        echo "<tr>";
                                        echo "<td data-label='Event Title'>" . $escapedEventModeName . "</td>";
                                        echo "<td data-label='Action'>
                            <button class='btn_edit' onclick=\"editEventMode('" . $row['id'] . "', '" . $escapedEventModeName . "')\"><i class='fa fa-pencil'></i></button>
                            <button class='btn_delete' onclick=\"confirmDeleteEventMode('" . $row['id'] . "')\"><i class='fa fa-trash'></i></button>
                          </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No event modes available</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- CONFIRM DELETE -->
    <script src=js/deleteEvent.js></script>

    <script src=js/eventSettings.js></script>

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

    if (status === 'success') {
        Swal.fire({
            title: "Success!",
            text: "Event successfully updated!",
            icon: "success"
        }).then(() => {
            const newUrl = window.location.pathname;
            window.history.replaceState(null, '', newUrl);
        });
    }
    if (status === 'cancelled') {
        Swal.fire({
            title: "Cancelled!",
            text: "Event successfully cancelled!",
            icon: "error"
        }).then(() => {
            const newUrl = window.location.pathname;
            window.history.replaceState(null, '', newUrl);
        });
    }
</script>

<!--real-time update-->
<script src="js/realTimeUpdate.js"></script>

</html>