<?php
include('../function/F.participants_retrieve.php');

// Check if the event title is set
$eventTitle = isset($_GET['eventTitle']) ? urldecode($_GET['eventTitle']) : null;
$eventId = isset($eventId) ? $eventId : '';

if ($eventTitle) {
    // Prepare and execute a query to fetch the event ID using the event title
    $sqlEventId = "SELECT event_id FROM Events WHERE event_title = ?";
    $stmtEventId = $conn->prepare($sqlEventId);
    $stmtEventId->bind_param("s", $eventTitle);
    $stmtEventId->execute();
    $resultEventId = $stmtEventId->get_result();

    // If the event ID is found, store it in $eventId
    if ($resultEventId->num_rows > 0) {
        $rowEventId = $resultEventId->fetch_assoc();
        $eventId = $rowEventId['event_id'];
    } else {
        // If no event is found with the given title, set $eventId to null or handle error
        $eventId = null;
    }
} else {
    // If eventTitle is not set, set eventId to null or empty
    $eventId = '';
}
// Fetch total participants for the specified event title
$totalParticipantsSql = "SELECT COUNT(*) AS totalParticipants FROM eventParticipants
                          WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?)";
$totalParticipantsStmt = $conn->prepare($totalParticipantsSql);
$totalParticipantsStmt->bind_param("s", $eventTitle);
$totalParticipantsStmt->execute();
$totalParticipantsResult = $totalParticipantsStmt->get_result();
$totalParticipantsRow = $totalParticipantsResult->fetch_assoc();
$totalParticipants = $totalParticipantsRow['totalParticipants'];

// Fetch event date range based on the event title
$eventDatesSql = "SELECT date_start, date_end FROM events WHERE event_title = ?";
$eventDatesStmt = $conn->prepare($eventDatesSql);
$eventDatesStmt->bind_param("s", $eventTitle);
$eventDatesStmt->execute();
$eventDatesResult = $eventDatesStmt->get_result();
$eventDatesRow = $eventDatesResult->fetch_assoc();

$dateStart = $eventDatesRow['date_start'];
$dateEnd = $eventDatesRow['date_end'];

// Generate all the dates between date_start and date_end
function generateDateRange($startDate, $endDate)
{
    $dates = [];
    $currentDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    while ($currentDate <= $endDate) {
        $dates[] = date('Y-m-d', $currentDate);
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return $dates;
}

$eventDates = generateDateRange($dateStart, $dateEnd);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>

    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <!-- font awesome cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/attendance.css">
    <link rel="stylesheet" href="css/table.css">
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

    $FirstName = "";
    $MI = "";
    $LastName = "";
    $Position = "";

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

    <div class="main-content">
        <div class="attendance">
            <div class="attendance_header">
                <div class="attendance_title">
                    <?php echo isset($eventTitle) ? htmlspecialchars($eventTitle) . ' Participants' : 'Event Title Here'; ?>
                </div>

                <div class="search_attendance">
                    <input type="text" id="search_input" placeholder="Filter using Name" onkeyup="filterParticipants()">
                </div>
            </div>


            <section class="category">

                <div class="box-container">

                    <a href="event_participants.php?eventTitle=<?php echo urlencode($eventTitle); ?>" class="box"
                        id="viewParticipants">
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <h3>Participants</h3>
                            <span><?php echo $totalParticipants; ?></span>
                        </div>
                    </a>



                    <!-- Present Participants -->
                    <a href="#" class="box" id="viewPresentParticipants">
                        <i class="fa-solid fa-user-check"></i>
                        <h3>Present</h3>
                        <div class="day-hover-menu red-palette">
                            <label class="filter-label">Present</label>
                            <div class="days-list">
                                <?php
                                foreach ($eventDates as $date) {
                                    $formattedDate = date('M j, Y', strtotime($date));
                                    echo "<span class='present_day_filter day-item' data-day='$date' data-filter='present'>$formattedDate</span>";
                                }
                                ?>
                            </div>
                        </div>
                    </a>

                    <!-- Absent Participants -->
                    <a href="#" class="box" id="viewAbsentParticipants">
                        <i class="fa-solid fa-user-xmark"></i>
                        <h3>Absent</h3>
                        <div class="day-hover-menu red-palette">
                            <label class="filter-label">Absent</label>
                            <div class="days-list">
                                <?php
                                foreach ($eventDates as $date) {
                                    $formattedDate = date('M j, Y', strtotime($date));
                                    echo "<span class='absent_day_filter day-item' data-day='$date' data-filter='absent'>$formattedDate</span>";
                                }
                                ?>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="box">
                        <i class="fa-solid fa-calendar-days"></i>
                        <div>
                            <select name="event_day-filter" id="event_day-filter" onchange="updateTable()">
                                <option value="">Select Day</option>
                                <?php
                                foreach ($eventDates as $date) {
                                    $formattedDate = date('M j, Y', strtotime($date));
                                    echo "<option value=\"$date\">$formattedDate</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </a>

                </div>
            </section>
            <div class="table_wrap">
                <div class="table_header">
                    <ul>
                        <li>
                            <div class="item">
                                <div class="name"><span>Date</span></div>
                                <div class="name"><span>Full Name</span></div>
                                <div class="department"><span>Affiliation</span></div>
                                <div class="department"><span>Position</span></div>
                                <div class="info"><span>Email</span></div>
                                <div class="phone"><span>Phone#</span></div>
                                <div class="phone"><span>Status</span></div>
                                <div class="status"><span>Action</span></div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table_body">
                    <?php
                    $selectedDate = isset($_GET['selectedDate']) ? $_GET['selectedDate'] : '';

                    $sql = "SELECT user.FirstName, user.MI, user.LastName, user.Affiliation, 
                                   user.Position, user.Email, user.ContactNo, 
                                   eventParticipants.participant_id, eventParticipants.event_id,
                                   attendance.status
                            FROM eventParticipants
                            INNER JOIN user ON eventParticipants.UserID = user.UserID
                            LEFT JOIN attendance ON eventParticipants.participant_id = attendance.participant_id 
                                                  AND eventParticipants.event_id = attendance.event_id 
                                                  AND attendance.attendance_date = ?
                            WHERE eventParticipants.event_id = 
                                  (SELECT event_id FROM Events WHERE event_title = ?)";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $selectedDate, $eventTitle);  // Bind the date and event title
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "<ul id='participant_list'>";
                        while ($row = $result->fetch_assoc()) {
                            $firstName = htmlspecialchars($row['FirstName']);
                            $MI = htmlspecialchars($row['MI']);
                            $lastName = htmlspecialchars($row['LastName']);
                            $fullName = $firstName . ' ' . $MI . ' ' . $lastName;
                            $affiliation = htmlspecialchars($row['Affiliation']);
                            $position = htmlspecialchars($row['Position']);
                            $email = htmlspecialchars($row['Email']);
                            $contactNo = htmlspecialchars($row['ContactNo']);
                            $status = htmlspecialchars($row['status']) ?: 'Not Marked';  // Handle NULL status
                            ?>
                            <form action='' method='POST'>
                                <li class="participant_item">
                                    <div class="item">
                                        <div class="name">
                                            <span><?php echo $selectedDate ? date('M j, Y', strtotime($selectedDate)) : 'No Date Selected'; ?></span>
                                        </div>
                                        <div class="name"><span><?php echo $fullName; ?></span></div>
                                        <div class="department"><span><?php echo $affiliation; ?></span></div>
                                        <div class="department"><span><?php echo $position; ?></span></div>
                                        <div class="info"><span><?php echo $email; ?></span></div>
                                        <div class="phone"><span><?php echo $contactNo; ?></span></div>
                                        <div class="status">
                                            <span>
                                                <?php
                                                if ($status === 'present') {
                                                    echo 'Present';
                                                } elseif ($status === 'absent') {
                                                    echo 'Absent';
                                                } else {
                                                    echo 'Not Marked';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        <div class="status">
                                            <input type="hidden" name="participant_id"
                                                value="<?php echo $row['participant_id']; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                            <button type="button"
                                                onclick="triggerAttendance('<?php echo $row['participant_id']; ?>')"
                                                class="attendance-btn">
                                                <i class="fas fa-user-check"></i> Attendance
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </form>
                            <?php
                        }
                        echo "</ul>";
                    } else {
                        echo "<div class='no-participants-container'>
                                <p class='no-participants-message'><i class='fas fa-exclamation-circle'></i> No participants found for the specified event.</p>
                              </div>";
                    }
                    ?>

                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const presentDayItems = document.querySelectorAll(".present_day_filter");
                        const absentDayItems = document.querySelectorAll(".absent_day_filter");

                        presentDayItems.forEach(item => {
                            item.addEventListener("click", function () {
                                const selectedDate = this.getAttribute("data-day");
                                const filter = 'present';

                                fetchParticipantsByDateAndStatus(selectedDate, filter);
                            });
                        });

                        absentDayItems.forEach(item => {
                            item.addEventListener("click", function () {
                                const selectedDate = this.getAttribute("data-day");
                                const filter = 'absent';

                                fetchParticipantsByDateAndStatus(selectedDate, filter);
                            });
                        });

                        function fetchParticipantsByDateAndStatus(selectedDate, filter) {
                            const eventTitle = "<?php echo $eventTitle; ?>";

                            fetch(`filter_participants.php?selectedDate=${selectedDate}&eventTitle=${eventTitle}&status=${filter}`)
                                .then(response => response.text())
                                .then(data => {
                                    document.querySelector('.table_body').innerHTML = data;
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });

                </script>

                <script src="js/eventsparticipants.js"></script>
                <!--sidebar functionality-->
                <script src="js/sidebar.js"></script>
                <!-- Include FontAwesome for icons -->
                <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.7.1.min.js"
                    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>




</html>