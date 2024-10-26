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
                    <?php echo isset($eventTitle) ? htmlspecialchars($eventTitle) . ' Evaluation' : 'Event Title Here'; ?>
                </div>

                <div class="search_attendance">
                    <input type="text" id="search_input" placeholder="Filter using Name" onkeyup="filterParticipants()">
                </div>

            </div>


            <style>
                .parent-evaluation {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    padding: 20px;
                }

                .aButton {
                    background-color: #ff4d4d;
                    color: white;
                    border: 1px solid #cc0000;
                    padding: 12px 24px;
                    font-size: 18px;
                    border-radius: 8px;
                    cursor: pointer;
                    text-transform: uppercase;
                    transition: background-color 0.3s, transform 0.2s;
                    box-shadow: 0px 8px 16px rgba(204, 0, 0, 0.2);
                }

                .aButton:hover {
                    background-color: #cc0000;
                    transform: scale(1.05);
                }

                .aButton:active {
                    background-color: #990000;
                    transform: scale(0.98);
                }
            </style>
            <div class="parent-evaluation">
                <button class="aButton" onclick="navigateToAttendance()">Back to Attendance</button>
            </div>
            <script>
                function navigateToAttendance() {
                    window.location.href = "event_participants.php?eventTitle=<?php echo urlencode($_SESSION['event_data']['eventTitle']); ?>";
                }

            </script>
            <div class="table_wrap">
                <div class="table_header">
                    <ul>
                        <li>
                            <div class="item">
                                <div class="name"><span>Full Name</span></div>
                                <div class="department"><span>Affiliation</span></div>
                                <div class="department"><span>Position</span></div>
                                <div class="info"><span>Email</span></div>
                                <div class="phone"><span>Phone#</span></div>
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
                            <li class="participant_item">
                                <div class="item">
                                    <div class="name"><span><?php echo $fullName; ?></span></div>
                                    <div class="department"><span><?php echo $affiliation; ?></span></div>
                                    <div class="department"><span><?php echo $position; ?></span></div>
                                    <div class="info"><span><?php echo $email; ?></span></div>
                                    <div class="phone"><span><?php echo $contactNo; ?></span></div>
                                    <div class="status">
                                        <input type="hidden" name="participant_id"
                                            value="<?php echo $row['participant_id']; ?>">
                                        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                        <div class="status attendance-btn-container">
                                            <button type="button"
                                                onclick="fetchAttendance('<?php echo $row['participant_id']; ?>', '<?php echo $row['event_id']; ?>', '<?php echo $fullName; ?>', '<?php echo $eventTitle; ?>')"
                                                class="attendance-btn">
                                                <i class='fa-solid fa-clipboard-check'></i>
                                            </button>
                                            <!-- <button type="button"
                                                onclick="editAttendance('<?php echo $row['participant_id']; ?>', '<?php echo $row['event_id']; ?>', '<?php echo $fullName; ?>', '<?php echo $eventTitle; ?>')"
                                                class="attendance-btn">
                                                <i class='fa-solid fa-clipboard-check'></i>
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </li>
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
                    function fetchAttendance(participantId, eventId, fullName, eventName) {
                        // Check if evaluation record already exists
                        fetch(`check_evaluation.php?participant_id=${participantId}&event_id=${eventId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.exists) {
                                    // If evaluation already recorded, show error message
                                    Swal.fire({
                                        title: 'Evaluation Recorded Already!',
                                        text: `An evaluation record for ${fullName} in ${eventName} already exists.`,
                                        icon: 'error',
                                        confirmButtonText: 'Okay'
                                    });
                                } else {
                                    // Proceed with fetching attendance if no evaluation record exists
                                    fetch(`fetch_attendance_details.php?participant_id=${participantId}&event_id=${eventId}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data && data.length > 0) {
                                                let allPresent = true;
                                                let allAbsentOrNoRecord = true;
                                                let attendanceDetails = `
                                <div style="text-align: center; padding: 2.5rem;">
                                    <h2 style="margin-bottom: 1rem;">${eventName}</h2>
                                    <h3 style="margin-bottom: 1rem; font-weight: bold;">${fullName}</h3>`;
                                                // Function to format time
                                                function formatTime(time) {
                                                    if (!time) return 'N/A';
                                                    let [hours, minutes] = time.split(':');
                                                    let ampm = hours >= 12 ? 'PM' : 'AM';
                                                    hours = hours % 12 || 12;
                                                    return `${hours}:${minutes} ${ampm}`;
                                                }
                                                // Process attendance data and display
                                                const dateStart = new Date(data[0].date_start).toISOString().slice(0, 10);
                                                const dateEnd = new Date(data[0].date_end).toISOString().slice(0, 10);
                                                let eventDates = [];
                                                let currentDate = new Date(dateStart);
                                                while (currentDate <= new Date(dateEnd)) {
                                                    eventDates.push(currentDate.toISOString().slice(0, 10));
                                                    currentDate.setDate(currentDate.getDate() + 1);
                                                }
                                                const attendanceMap = data.reduce((acc, day) => {
                                                    acc[new Date(day.attendance_date).toISOString().slice(0, 10)] = day;
                                                    return acc;
                                                }, {});
                                                eventDates.forEach((eventDate, index) => {
                                                    let dayLabel = index === 0 ? "1st Day" :
                                                        index === eventDates.length - 1 ? "Last Day" : `Day ${index + 1}`;
                                                    if (attendanceMap[eventDate]) {
                                                        let day = attendanceMap[eventDate];
                                                        let formattedStatus = day.status.charAt(0).toUpperCase() + day.status.slice(1).toLowerCase();
                                                        let statusColor = formattedStatus === 'Present' ? 'green' : 'red';
                                                        if (formattedStatus === 'Absent') allPresent = false;
                                                        if (formattedStatus === 'Present') allAbsentOrNoRecord = false;
                                                        attendanceDetails += `
                                        <div style="margin-bottom: 1rem; text-align: center;">
                                            <h4 style="margin-bottom: 0.5rem;">${dayLabel} - ${new Date(eventDate).toLocaleDateString('en-US', {
                                                            month: 'short', day: 'numeric', year: 'numeric'
                                                        })} - <span style="color: ${statusColor};">${formattedStatus}</span></h4>
                                            <p><strong>Time-in:</strong> ${formatTime(day.time_in)} &nbsp;&nbsp;&nbsp;
                                            <strong>Time-out:</strong> ${formatTime(day.time_out)}</p>
                                        </div>`;
                                                    } else {
                                                        allPresent = false;
                                                        attendanceDetails += `
                                        <div style="margin-bottom: 1rem; text-align: center;">
                                            <h4 style="margin-bottom: 0.5rem; color: orange;">${dayLabel} - ${new Date(eventDate).toLocaleDateString('en-US', {
                                                            month: 'short', day: 'numeric', year: 'numeric'
                                                        })} - No Record!</h4>
                                        </div>`;
                                                    }
                                                });
                                                attendanceDetails += `</div>`;
                                                let showApprove = !allAbsentOrNoRecord;
                                                let showDecline = !allPresent;
                                                Swal.fire({
                                                    title: '',
                                                    html: attendanceDetails,
                                                    showConfirmButton: showApprove,
                                                    confirmButtonText: 'Approve?',
                                                    showDenyButton: showDecline,
                                                    denyButtonText: 'Decline?',
                                                    customClass: {
                                                        popup: 'larger-swal'
                                                    },
                                                    width: '600px',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        Swal.fire({
                                                            title: 'Approve Certificate?',
                                                            text: 'Are you sure you want to approve the certificate?',
                                                            icon: 'question',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Yes, Approve!',
                                                            cancelButtonText: 'Cancel',
                                                        }).then((confirmResult) => {
                                                            if (confirmResult.isConfirmed) {
                                                                let attendedDays = data.filter(day => day.status.toLowerCase() === 'present').length;
                                                                let absentDays = data.filter(day => day.status.toLowerCase() === 'absent').length;
                                                                let remarks = `${attendedDays} Attended, ${absentDays} Absent`;
                                                                saveEvaluation(participantId, eventId, 'approved', remarks);
                                                                Swal.fire('Approved!', 'The certificate has been approved.', 'success');
                                                            }
                                                        });
                                                    } else if (result.isDenied) {
                                                        Swal.fire({
                                                            title: 'Decline Certificate?',
                                                            text: 'Are you sure you want to decline the certificate?',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Yes, Decline!',
                                                            cancelButtonText: 'Cancel',
                                                        }).then((confirmResult) => {
                                                            if (confirmResult.isConfirmed) {
                                                                let attendedDays = data.filter(day => day.status.toLowerCase() === 'present').length;
                                                                let absentDays = data.filter(day => day.status.toLowerCase() === 'absent').length;
                                                                let remarks = `${attendedDays} Attended, ${absentDays} Absent`;
                                                                saveEvaluation(participantId, eventId, 'declined', remarks);
                                                                Swal.fire('Declined!', 'The certificate has been declined.', 'error');
                                                            }
                                                        });
                                                    }
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: `No attendance records found for ${fullName} in ${eventName}.`,
                                                    icon: 'error',
                                                    confirmButtonText: 'Okay'
                                                });
                                            }
                                        }).catch(error => {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'An error occurred while fetching attendance data. Please try again later.',
                                                icon: 'error',
                                                confirmButtonText: 'Okay'
                                            });
                                            console.error('Fetch error:', error);
                                        });
                                }
                            }).catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred while checking evaluation status. Please try again later.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay'
                                });
                                console.error('Evaluation check error:', error);
                            });
                    }


                    function saveEvaluation(participantId, eventId, status, remarks) {
                        fetch('save_evaluation.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                participant_id: participantId,
                                event_id: eventId,
                                status: status,
                                remarks: remarks
                            })
                        })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    Swal.fire('Success!', 'Evaluation saved successfully.', 'success');
                                } else {
                                    Swal.fire('Error!', 'Failed to save the evaluation. Please try again.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error saving evaluation:', error);
                                Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                            });
                    }

                </script>


                <script src="js/eventsParticipant.js"></script>
                <!--sidebar functionality-->
                <script src="js/sidebar.js"></script>
                <!-- Include FontAwesome for icons -->
                <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.7.1.min.js"
                    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>




</html>