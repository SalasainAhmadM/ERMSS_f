<?php
include('../function/F.participants_retrieve.php');

// Function to calculate the number of days between two dates
function dateRange($start, $end) {
    $start = new DateTime($start);
    $end = new DateTime($end);
    // Adjust the end date to include it in the counting
    $end->modify('+1 day');
    $interval = new DateInterval('P1D'); // 1 Day interval
    $dateRange = new DatePeriod($start, $interval, $end);

    // Count each day individually
    $daysCount = 0;
    foreach ($dateRange as $date) {
        $daysCount++;
    }

    return $daysCount;
}

// Check if the event title is set
$eventTitle = isset($_GET['eventTitle']) ? urldecode($_GET['eventTitle']) : null;

// Fetch event start and end dates
$eventDatesSql = "SELECT date_start, date_end FROM Events WHERE event_title = ?";
$eventDatesStmt = $conn->prepare($eventDatesSql);
$eventDatesStmt->bind_param("s", $eventTitle);
$eventDatesStmt->execute();
$eventDatesResult = $eventDatesStmt->get_result();
$eventDatesRow = $eventDatesResult->fetch_assoc();
$dateStart = $eventDatesRow['date_start'];
$dateEnd = $eventDatesRow['date_end'];

// Calculate the number of days between start and end dates
$numDays = dateRange($dateStart, $dateEnd);

// Close statement and result set
$eventDatesStmt->close();

// Check if the event title is set
$eventTitle = isset($_GET['eventTitle']) ? urldecode($_GET['eventTitle']) : null;

// Fetch total participants for the specified event title
$totalParticipantsSql = "SELECT COUNT(*) AS totalParticipants FROM eventParticipants
                          WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?)";
$totalParticipantsStmt = $conn->prepare($totalParticipantsSql);
$totalParticipantsStmt->bind_param("s", $eventTitle);
$totalParticipantsStmt->execute();
$totalParticipantsResult = $totalParticipantsStmt->get_result();
$totalParticipantsRow = $totalParticipantsResult->fetch_assoc();
$totalParticipants = $totalParticipantsRow['totalParticipants'];


// Fetch participants who do not have attendance records for the specified event
$participantsWithoutAttendanceSql = "SELECT * FROM eventParticipants 
                                     WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?) 
                                     AND participant_id NOT IN 
                                     (SELECT participant_id FROM attendance WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?))";
$participantsWithoutAttendanceStmt = $conn->prepare($participantsWithoutAttendanceSql);
$participantsWithoutAttendanceStmt->bind_param("ss", $eventTitle, $eventTitle);
$participantsWithoutAttendanceStmt->execute();
$participantsWithoutAttendanceResult = $participantsWithoutAttendanceStmt->get_result();


// Fetch participants with status "Present" and their IDs !present for absent
$presentParticipantIdsSql = "SELECT participant_id FROM attendance WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?) AND status = 'present'";
$presentParticipantIdsStmt = $conn->prepare($presentParticipantIdsSql);
$presentParticipantIdsStmt->bind_param("s", $eventTitle);
$presentParticipantIdsStmt->execute();
$presentParticipantIdsResult = $presentParticipantIdsStmt->get_result();

// Initialize an array to store participant IDs with the present status
$presentParticipantIds = array();
while ($row = $presentParticipantIdsResult->fetch_assoc()) {
    $presentParticipantIds[] = $row['participant_id'];
}

// Close statement and result set
$presentParticipantIdsStmt->close();

// Pass the presentParticipantIds array to JavaScript as a JSON object
echo "<script>var presentParticipantIds = " . json_encode($presentParticipantIds) . ";</script>";








// Fetch total attendees with status "Present"
$totalPresentSql = "SELECT COUNT(*) AS totalPresent FROM attendance WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?) AND status = 'present'";
$totalPresentStmt = $conn->prepare($totalPresentSql);
$totalPresentStmt->bind_param("s", $eventTitle);
$totalPresentStmt->execute();
$totalPresentResult = $totalPresentStmt->get_result();
$totalPresentRow = $totalPresentResult->fetch_assoc();
$totalPresent = $totalPresentRow['totalPresent'];

// Fetch total attendees with status "Absent"
$totalAbsentSql = "SELECT COUNT(*) AS totalAbsent FROM attendance WHERE event_id = (SELECT event_id FROM Events WHERE event_title = ?) AND status = 'absent'";
$totalAbsentStmt = $conn->prepare($totalAbsentSql);
$totalAbsentStmt->bind_param("s", $eventTitle);
$totalAbsentStmt->execute();
$totalAbsentResult = $totalAbsentStmt->get_result();
$totalAbsentRow = $totalAbsentResult->fetch_assoc();
$totalAbsent = $totalAbsentRow['totalAbsent'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the status button is clicked
    if (isset($_POST['status'])) {
        // Retrieve data from the form
        $participant_id = $_POST['participant_id'];
        $event_id = $_POST['event_id'];
        $selectedDay = $_POST['event_day']; // Get the selected day
        $attendance_date = $dateStart; // Use the event start date as base
        $attendance_date = date('Y-m-d', strtotime($attendance_date . ' +' . ($selectedDay - 1) . ' days')); // Add selected day to start date

        $status = $_POST['status']; // 'present' or 'absent'

        // Check if a record already exists for the participant_id, event_id, and attendance_date
        $existingRecordSql = "SELECT * FROM attendance WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
        $existingRecordStmt = $conn->prepare($existingRecordSql);
        $existingRecordStmt->bind_param("iis", $participant_id, $event_id, $attendance_date);
        $existingRecordStmt->execute();
        $existingRecordResult = $existingRecordStmt->get_result();

        if ($existingRecordResult->num_rows > 0) {
            // A record already exists
            $existingRecordRow = $existingRecordResult->fetch_assoc();
            if ($existingRecordRow['status'] == $status) {
                // The status is the same, so display a message
                echo "<script>alert('Attendance already recorded.');</script>";
            } else {
                // The status is different, so update the existing record
                $updateSql = "UPDATE attendance SET status = ?, day = ? WHERE participant_id = ? AND event_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("siii", $status, $selectedDay, $participant_id, $event_id);

                if ($updateStmt->execute()) {
                    echo "<script>alert('Attendance updated successfully.'); window.location.reload();</script>";
                } else {
                    echo "<script>alert('Error updating attendance.');</script>";
                }
                $updateStmt->close();
            }
        } else {
            // No record exists, so insert a new record
            $insertSql = "INSERT INTO attendance (participant_id, event_id, attendance_date, status, day) VALUES (?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("iissi", $participant_id, $event_id, $attendance_date, $status, $selectedDay);
            if ($insertStmt->execute()) {
                echo "<script>alert('Attendance marked successfully.'); window.location.reload();</script>";
            } else {
                echo "<script>alert('Error marking attendance.');</script>";
            }
            $insertStmt->close();
        }

        // Close statement and result set
        $existingRecordStmt->close();
        $existingRecordResult->close();
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

    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <!-- font awesome cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
            $pendingEventsCount = countPendingEvents($conn);
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

    <div class="main-content">
        <div class="attendance">
            <div class="attendance_header">
                <div class="attendance_title">
                    <?php echo isset($eventTitle) ? htmlspecialchars($eventTitle) . ' Participants' : 'Event Title Here'; ?>
                </div>

                <div class="search_attendance">
                    <input type="text" id="search_input" placeholder="Filter using Name">
                </div>
            </div>

            <section class="category">

                <div class="box-container">

                    <a href="#" class="box" id="viewParticipants">
                        <i class="fa-solid fa-child"></i>
                        <div>
                            <h3>Participants</h3>
                            <span><?php echo $totalParticipants; ?></span>
                        </div>
                    </a>

                    <a href="#" class="box" id="viewPresent">
                        <i class="fa-solid fa-person-running"></i>
                        <div>
                            <h3>Present</h3>
                            <span><?php echo $totalPresent; ?></span>
                        </div>
                    </a>

                    <a href="#" class="box" id="viewAbsent">
                        <i class="fa-solid fa-user-xmark"></i>
                        <div>
                            <h3>Absent</h3>
                            <span><?php echo $totalAbsent; ?></span>
                        </div>
                    </a>

                    <a href="#" class="box">
                        <i class="fa-solid fa-calendar-days"></i>
                        <div>
                            <select name="event_day-filter" id="event_day-filter" onchange="filterByDay()">
                                <option value="">All Days</option> <!-- Add an option to show all -->
                                <?php for ($i = 1; $i <= $numDays; $i++) : ?>
                                    <option value="<?php echo $i; ?>">Day <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </a>

                </div>
            </section>

            <button class="aButton"  onclick="filterAttendance()">Attendance</button>

            <div class="table_wrap">
                <div class="table_header">
                    <ul>
                        <li>
                            <div class="item">
                                <div class="name">
                                    <span>FULL NAME</span>
                                </div>
                                <div class="department">
                                    <span>AFFILIATION</span>
                                </div>
                                <div class="department">
                                    <span>OCCUPATION</span>
                                </div>
                                <div class="info">
                                    <span>EMAIL</span>
                                </div>
                                <div class="phone">
                                    <span>PHONE#</span>
                                </div>
                                
                                <div class="phone">
                                    <span>DAY</span>
                                </div>
                                
                                <div class="status" id="action_tab">
                                    <span>ACTION</span>
                                </div>
                                

                                <!-- 
                                <div class="days"></div>
                                <div class="status">
                                    <span></span>
                                </div> -->

                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table_body">
                    <?php
                    // Fetch and display participants for the specified event title
                    $sql = "SELECT user.FirstName, user.LastName, user.Affiliation, user.Position, user.Email, user.ContactNo, 
                    attendance.day, eventParticipants.participant_id, eventParticipants.event_id
                    FROM eventParticipants 
                    INNER JOIN user ON eventParticipants.UserID = user.UserID
                    LEFT JOIN attendance ON attendance.participant_id = eventParticipants.participant_id 
                    WHERE eventParticipants.event_id = (SELECT event_id FROM Events WHERE event_title = ?)";
     
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $eventTitle);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $firstName = htmlspecialchars($row['FirstName']);
                            $lastName = htmlspecialchars($row['LastName']);
                            $fullName = $firstName . ' ' . $lastName;
                            $affiliation = htmlspecialchars($row['Affiliation']);
                            $position = htmlspecialchars($row['Position']);
                            $email = htmlspecialchars($row['Email']);
                            $contactNo = htmlspecialchars($row['ContactNo']);
                            $attendanceDay = htmlspecialchars($row['day']); 
                    ?>
                            
                            <form action='' method='POST' onsubmit="return confirmAttendance(this);">
                                <ul>
                                    <li>
                                        <div class="item">
                                            <div class="name">
                                                <span><?php echo $fullName; ?></span>
                                            </div>

                                            <div class="department">
                                                <span><?php echo $affiliation . ' ' ?></span>
                                            </div>

                                            <div class="department">
                                                <span><?php echo $position . ' ' ?></span>
                                            </div>

                                            <div class="info">
                                                <span><?php echo $email . ' ' ?></span>
                                            </div>

                                            <div class="phone">
                                                <span><?php echo $contactNo . ' ' ?></span>
                                            </div>

                                            <div class="phone">
                                            <?php if (!empty($attendanceDay)) : ?>
                                                    <input type="hidden" name="attendance_day" value="<?php echo $attendanceDay; ?>">
                                                    <span>(Day: <?php echo $attendanceDay; ?>)</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="action" id="action">
                                            <div class="days">
                                                <input type="hidden" name="attendance_date" id="attendance_date">
                                                <!--hide-->
                                                <select name="event_day" id="event_day">
                                                    <?php for ($i = 1; $i <= $numDays; $i++) : ?>
                                                        <option value="<?php echo $i; ?>">Day <?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>

                                            <div class="status">
                                               <input type="hidden" name="participant_id" value="<?php echo $row['participant_id']; ?>">
                                                <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                                <button type="submit" name="status" value="present" class="approve att">Present</button>
                                            </div>

                                            <div class="status">
                                                <input type="hidden" name="participant_id" value="<?php echo $row['participant_id']; ?>">
                                                <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                                <button type="submit" name="status" value="absent" class="approve att">Absent</button>
                                            </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                    <?php
                        }
                    } else {
                        echo "No participants found for the specified event.";
                    }
                    ?>
                </div>
            </div>

            <script>
                // Function to filter participants by selected day
                function filterByDay() {
                    var selectedDay = document.getElementById("event_day-filter").value; // Get the selected day
                    var participants = document.querySelectorAll('.table_body ul li'); // Get all participant elements

                    participants.forEach(function(participant) {
                        var participantDay = participant.querySelector("input[name='attendance_day']"); // This should refer to your attendance day data

                        // Check if the participant has a day set and if it matches the selected day
                        if (participantDay && participantDay.value) {
                            if (selectedDay === "" || participantDay.value == selectedDay) { // Show if "All Days" is selected or day matches
                                participant.style.display = 'block'; // Show the participant
                            } else {
                                participant.style.display = 'none'; // Hide if it doesn't match
                            }
                        } else {
                            // If there’s no day (meaning no attendance record), hide the participant
                            participant.style.display = 'none';
                        }
                    });
                }
            </script>
            

            <!--search filter-->
            <script>
                var search_input = document.querySelector("#search_input");

                search_input.addEventListener("keyup", function(e){
                var span_items = document.querySelectorAll(".table_body .name span");
                var table_body = document.querySelector(".table_body ul");
                var search_item = e.target.value.toLowerCase();
                
                span_items.forEach(function(item){
                if(item.textContent.toLowerCase().indexOf(search_item) != -1){
                    item.closest("li").style.display = "block";
                }
                else{
                    item.closest("li").style.display = "none";
                    }
                })
                
                });
            </script>

            <!--display participants that has no attendance records-->
            <script>
                // Function to handle the Attendance button click event
                function filterAttendance() {
                    var participants = document.querySelectorAll(".table_body ul li");
                    var participantsWithoutAttendance = <?php echo json_encode($participantsWithoutAttendanceResult->fetch_all(MYSQLI_ASSOC)); ?>;
                    
                    // Hide all participants initially
                    participants.forEach(function(participant) {
                        participant.style.display = "none";
                    });

                    // Show participants who have no attendance records
                    participantsWithoutAttendance.forEach(function(participant) {
                        var participantId = participant.participant_id;

                        participants.forEach(function(participantElement) {
                            var participantInput = participantElement.querySelector(".status input[name='participant_id']");
                            if (participantInput && participantInput.value == participantId) {
                                participantElement.style.display = "block";
                            }
                        });
                    });

                                        // Show all action divs when Absent is active
                                        var actionDivs = document.querySelectorAll('.action');
                    actionDivs.forEach(function(actionDiv) {
                        actionDiv.style.display = 'block'; // Show each action div
                    });

                    document.getElementById('action_tab').style.display = 'block'; 
                }
            </script>


            <!--DISPLAY ALL PARTICIPANTS-->
            <script>
                // Function to handle the box click event and filter participants
                function handleBoxClick(event) {
                    
                    event.preventDefault();

                    // Get the clicked box element
                    var clickedBox = event.currentTarget;

                    // Remove 'active' class from all boxes
                    var boxes = document.querySelectorAll('.box');
                    boxes.forEach(function(box) {
                        box.classList.remove('active');
                    });

                    // Add 'active' class to the clicked box
                    clickedBox.classList.add('active');

                    // Get all participant elements
                    var participants = document.querySelectorAll('.table_body ul li');

                    // Show all participants
                    participants.forEach(function(participant) {
                        participant.style.display = 'block';
                    });

                    // Show all action divs when Absent is active
                    var actionDivs = document.querySelectorAll('.action');
                    actionDivs.forEach(function(actionDiv) {
                        actionDiv.style.display = 'block'; // Show each action div
                    });

                    document.getElementById('action_tab').style.display = 'block'; 
                }

                // Add event listener to the element with id="viewParticipants" only
                var viewParticipants = document.getElementById('viewParticipants');
                viewParticipants.addEventListener('click', handleBoxClick);

            </script>

            <!-- DISPLAY PRESENT -->
            <script>
                function handlePresentBoxClick(event) {
                    event.preventDefault();

                    // Get the clicked box element
                    var clickedBox = event.currentTarget;

                    // Remove 'active' class from all boxes
                    var boxes = document.querySelectorAll('.box');
                    boxes.forEach(function(box) {
                        box.classList.remove('active');
                    });

                    // Add 'active' class to the clicked box
                    clickedBox.classList.add('active');

                    // Get the selected day from the dropdown
                    var selectedDay = document.getElementById("event_day-filter").value;

                    // Get all participant elements
                    var participants = document.querySelectorAll('.table_body ul li');

                    // Hide all participants initially
                    participants.forEach(function(participant) {
                        participant.style.display = 'none';
                    });

                    // Show only participants fetched from the attendance table with status 'Present'
                    participants.forEach(function(participant) {
                        var participantId = participant.querySelector("input[name='participant_id']").value;
                        var attendanceDay = participant.querySelector("input[name='attendance_day']").value; // Get attendance day
                        
                        // Check if participant is present and matches selected day
                        if (participantId && presentParticipantIds.includes(parseInt(participantId)) &&
                            (selectedDay === "" || attendanceDay == selectedDay)) {
                            participant.style.display = 'block'; // Show the participant
                        }
                    });

                    // Hide all action divs when Present is active
                    var actionDivs = document.querySelectorAll('.action');
                    actionDivs.forEach(function(actionDiv) {
                        actionDiv.style.display = 'none'; // Hide each action div
                    });

                    document.getElementById('action_tab').style.display = 'none'; 
                }
            </script>

            <!-- DISPLAY ABSENT -->
            <script>
            function handleAbsentBoxClick(event) {
                event.preventDefault();

                // Get the clicked box element
                var clickedBox = event.currentTarget;

                // Remove 'active' class from all boxes
                var boxes = document.querySelectorAll('.box');
                boxes.forEach(function(box) {
                    box.classList.remove('active');
                });

                // Add 'active' class to the clicked box
                clickedBox.classList.add('active');

                // Get the selected day from the dropdown
                var selectedDay = document.getElementById("event_day-filter").value;

                // Get all participant elements
                var participants = document.querySelectorAll('.table_body ul li');

                // Hide all participants initially
                participants.forEach(function(participant) {
                    participant.style.display = 'none';
                });

                // Show only participants fetched from the attendance table with status 'Absent'
                participants.forEach(function(participant) {
                    var participantId = participant.querySelector("input[name='participant_id']").value;
                    var attendanceDay = participant.querySelector("input[name='attendance_day']").value; // Get attendance day
                    
                    // Check if participant is absent and matches selected day
                    if (participantId && !presentParticipantIds.includes(parseInt(participantId)) &&
                        (selectedDay === "" || attendanceDay == selectedDay)) {
                        participant.style.display = 'block'; // Show the participant
                    }
                });

                // Show all action divs when Absent is active
                var actionDivs = document.querySelectorAll('.action');
                actionDivs.forEach(function(actionDiv) {
                    actionDiv.style.display = 'none'; // Hide each action div
                });

                document.getElementById('action_tab').style.display = 'none'; 
            </script>

            }
            

        </div>
    </div>


    <!--sidebar functionality-->
    <script src="js/sidebar.js"></script>


    

    <script>
        // Function to show confirmation pop-up before submitting attendance
        function confirmAttendance(form) {
            // Display confirmation dialog
            if (confirm("Are you sure you want to record the attendance?")) {
                // If user clicks OK, submit the form
                form.submit();
            } else {
                // If user clicks Cancel, do nothing
                return false;
            }
        }
    </script>

    <!-- ATTENDANCE
    <script>
        // Function to update the attendance date based on the selected day
        function updateAttendanceDate() {
            // Get the selected day from the event_day select tag
            var selectedDay = document.getElementById("event_day").value;

            // Convert selected day into a number
            var selectedDayNumber = parseInt(selectedDay);

            // Get the start date of the event
            var startDate = new Date("<?php echo $dateStart; ?>");

            // Calculate the attendance date by adding the selected day number
            var attendanceDate = new Date(startDate);
            attendanceDate.setDate(startDate.getDate() + selectedDayNumber - 1);

            // Format the date as YYYY-MM-DD
            var formattedDate = attendanceDate.toISOString().split('T')[0];

            // Set the value of the attendance_date input field
            document.getElementById("attendance_date").value = formattedDate;
        }
    </script> -->
    
</body>




</html>
