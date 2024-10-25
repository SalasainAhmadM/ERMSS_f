<?php
include('../function/F.participants_retrieve.php');

function dateRange($start, $end)
{
    $start = new DateTime($start);
    $end = new DateTime($end);

    $end->modify('+1 day');
    $interval = new DateInterval('P1D');
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

// Fetch event details (including type and mode)
$eventDetailsSql = "SELECT event_id, event_type, event_mode, date_start, date_end FROM events WHERE event_title = ?";
$eventDetailsStmt = $conn->prepare($eventDetailsSql);
$eventDetailsStmt->bind_param("s", $eventTitle);
$eventDetailsStmt->execute();
$eventDetailsResult = $eventDetailsStmt->get_result();
$eventDetailsRow = $eventDetailsResult->fetch_assoc();

if ($eventDetailsRow) {
    $eventId = $eventDetailsRow['event_id'];
    $dateStart = $eventDetailsRow['date_start'];
    $dateEnd = $eventDetailsRow['date_end'];
    $eventType = $eventDetailsRow['event_type'];
    $eventMode = $eventDetailsRow['event_mode'];
} else {
    die("Event not found."); // Handle case where event is not found
}

// Calculate the number of days between start and end dates
$numDays = dateRange($dateStart, $dateEnd);

// Close statement and result set
$eventDetailsStmt->close();

// Fetch total participants for the specified event title
$totalParticipantsSql = "SELECT COUNT(*) AS totalParticipants FROM eventParticipants WHERE event_id = ?";
$totalParticipantsStmt = $conn->prepare($totalParticipantsSql);
$totalParticipantsStmt->bind_param("i", $eventId);
$totalParticipantsStmt->execute();
$totalParticipantsResult = $totalParticipantsStmt->get_result();
$totalParticipantsRow = $totalParticipantsResult->fetch_assoc();
$totalParticipants = $totalParticipantsRow['totalParticipants'];

// Fetch and display participants for the specified event title
$participantsSql = "SELECT user.FirstName, user.LastName, user.Age, user.Gender, user.Email, user.Affiliation, user.Position, user.Image, user.ContactNo, user.EducationalAttainment, eventParticipants.UserID FROM eventParticipants INNER JOIN user ON eventParticipants.UserID = user.UserID WHERE eventParticipants.event_id = ?";
$participantsStmt = $conn->prepare($participantsSql);
$participantsStmt->bind_param("i", $eventId);
$participantsStmt->execute();
$participantsResult = $participantsStmt->get_result();

// Fetch attendance data for participants
$attendanceData = [];
$attendanceSql = "SELECT participant_id, attendance_date, status FROM attendance WHERE event_id = ?";
$attendanceStmt = $conn->prepare($attendanceSql);
$attendanceStmt->bind_param("i", $eventId);
$attendanceStmt->execute();
$attendanceResult = $attendanceStmt->get_result();

while ($attendanceRow = $attendanceResult->fetch_assoc()) {
    $attendanceData[$attendanceRow['participant_id']][$attendanceRow['attendance_date']] = $attendanceRow['status'];
}

require('../fpdf186/fpdf.php');

if (isset($_GET['download'])) {
    $pdf = new FPDF('P');
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->AddPage();

    // Title
    $pdf->SetFont("Arial", 'B', 16);
    $pdf->Cell(0, 10, 'Event Details', 0, 1, 'C');
    $pdf->Ln(10);

    // Adding Event Information
    $pdf->SetFont("Arial", 'B', 10);
    $pdf->Cell(0, 10, 'Event Information', 0, 1);
    $pdf->SetFont("Arial", '', 10);

    // Event Information
    $event_info = [
        "Event Title: " . htmlspecialchars($eventTitle),
        "Event Type: " . htmlspecialchars($eventType),
        "Event Mode: " . htmlspecialchars($eventMode),
        "Start Date: " . htmlspecialchars($dateStart),
        "End Date: " . htmlspecialchars($dateEnd)
    ];

    foreach ($event_info as $item) {
        $pdf->Cell(0, 8, $item, 0, 1);
    }

    $pdf->Ln(5);

    // Participants Section
    $pdf->SetFont("Arial", 'B', 10);
    $pdf->Cell(0, 5, 'Participants', 0, 1);
    $pdf->SetFont("Arial", '', 10);

    // Table header for participants
    $headers = ["#", "Participants"];
    $maxParticipantWidth = 0;

    // Calculate maximum width for the participants column
    while ($row = $participantsResult->fetch_assoc()) {
        $fullName = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
        $nameWidth = $pdf->GetStringWidth($fullName);
        $maxParticipantWidth = max($maxParticipantWidth, $nameWidth);
    }

    $minWidth = 50;
    $participantColumnWidth = max($minWidth, $maxParticipantWidth + 10);

    $pdf->Ln();

    $participantCount = 1;
    $participantsResult->data_seek(0);
    $totalDays = $numDays;

    for ($startDay = 0; $startDay < $totalDays; $startDay += 7) {
        if ($startDay > 0) {
            $pdf->AddPage();
            $pdf->SetFont("Arial", 'B', 10);
            $pdf->SetFont("Arial", '', 10);

            foreach ($headers as $header) {
                $pdf->Cell($header === "Participants" ? $participantColumnWidth : 15, 8, $header, 1, 0, 'C');
            }

            $pdf->SetFont("Arial", 'B', 10);
            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $currentDay = $startDay + $dayOffset;

                if ($currentDay >= $totalDays) {
                    break;
                }

                $currentDate = (new DateTime($dateStart))->modify("+$currentDay day")->format('m-d');
                $pdf->Cell(15, 8, $currentDate, 1, 0, 'C');
            }
            $pdf->Ln();
        } else {
            // This part is added to handle the first set of dates
            $pdf->SetFont("Arial", 'B', 10);
            foreach ($headers as $header) {
                $pdf->Cell($header === "Participants" ? $participantColumnWidth : 15, 8, $header, 1, 0, 'C');
            }

            $pdf->SetFont("Arial", 'B', 10);
            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $currentDate = (new DateTime($dateStart))->modify("+$dayOffset day")->format('m-d');
                $pdf->Cell(15, 8, $currentDate, 1, 0, 'C');
            }
            $pdf->Ln();
        }

        $participantsResult->data_seek(0);
        while ($row = $participantsResult->fetch_assoc()) {
            $participantId = $row['UserID'];

            // Fetch participant ID
            $participantInfoSql = "SELECT participant_id FROM eventParticipants WHERE UserID = ? AND event_id = ?";
            $participantInfoStmt = $conn->prepare($participantInfoSql);
            $participantInfoStmt->bind_param("ii", $participantId, $eventId);
            $participantInfoStmt->execute();
            $participantInfoResult = $participantInfoStmt->get_result();
            $participantInfoRow = $participantInfoResult->fetch_assoc();
            $actualParticipantId = $participantInfoRow['participant_id'];

            $pdf->Cell(15, 8, $participantCount++, 1, 0, 'C');

            $currentY = $pdf->GetY();
            $pdf->SetXY($pdf->GetX(), $currentY);
            $participantName = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);

            $pdf->Cell($participantColumnWidth, 8, $participantName, 1, 'C');

            $multiCellHeight = $pdf->GetY() - $currentY;

            $pdf->SetXY($pdf->GetX() + $participantColumnWidth, $currentY);

            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $currentDay = $startDay + $dayOffset;

                if ($currentDay >= $totalDays) {
                    break;
                }

                $currentDate = (new DateTime($dateStart))->modify("+$currentDay day")->format('Y-m-d');

                // Check attendance status
                $status = isset($attendanceData[$actualParticipantId][$currentDate]) ? $attendanceData[$actualParticipantId][$currentDate] : 'absent';
                $symbol = ($status === 'present') ? '/' : 'X';

                $pdf->Cell(15, 8, $symbol, 1, 0, 'C');

            }
            $pdf->Ln();
        }

        // Legend
        $pdf->SetFont("Arial", 'B', 12);
        $pdf->Ln(5);
        $pdf->SetFont("Arial", 'I', 8);
        $pdf->Cell(0, 8, '* X = Absent, / = Present', 0, 1);
        $pdf->Ln(5);
    }




    // Sponsors Section
    $pdf->SetFont("Arial", 'B', 10);
    $pdf->Cell(0, 10, 'Sponsors', 0, 1);
    $pdf->SetFont("Arial", 'B', 10);
    $pdf->Cell(15, 8, "#", 1, 0, 'C');
    $pdf->Cell(0, 8, "Name", 1, 1);
    $pdf->SetFont("Arial", '', 10);

    $sponsors_query = "SELECT sponsor_id, sponsor_firstName, sponsor_MI, sponsor_lastName FROM sponsor WHERE event_id = ?";
    $stmt = $conn->prepare($sponsors_query);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $sponsors_result = $stmt->get_result();

    $sponsor_count = 1;
    while ($sponsor_row = $sponsors_result->fetch_assoc()) {
        $sponsor_full_name = trim($sponsor_row['sponsor_firstName'] . ' ' . $sponsor_row['sponsor_MI'] . ' ' . $sponsor_row['sponsor_lastName']);
        $pdf->Cell(15, 8, strval($sponsor_count++), 1, 0, 'C');
        $pdf->Cell(0, 8, $sponsor_full_name, 1, 1);
    }

    $stmt->close();

    // Output PDF
    $pdf_output = $pdf->Output('event_details.pdf', 'S');

    // Prepare to download the PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="event_details.pdf"');
    echo $pdf_output;

    $conn->close();
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
            <!-- <h3 class="dashboard apply">Day 1</h3> -->

            <style>
                .download-button {
                    background-color: #ffd60a;
                }

                .download-button:hover {
                    background-color: #1d3557;
                }
            </style>
            <div class="tables container">
                <div class="table_header">
                    <p>
                        <?php echo isset($eventTitle) ? htmlspecialchars($eventTitle) . ' Participants' : 'Event Title Here'; ?>
                        <a style="" href="?download=true&eventTitle=<?php echo urlencode($eventTitle); ?>"
                            class="download-button">
                            <i class="fa fa-print" aria-hidden="true"></i> Download Event Report
                        </a>

                    </p>
                    <div>
                        <input class="tb_input" placeholder="Search..." oninput="filterTable()">
                        <a
                            href="event_participants.php?eventTitle=<?php echo urlencode($_SESSION['event_data']['eventTitle']); ?>"><button
                                class="add_new">View Attendance</button></a>
                        <!-- <button class="add_new"> <span><?php echo $totalParticipants; ?></span> Participants</button> -->
                        <!-- <select class="add_new" name="event_day" id="event_day">
                                <?php for ($i = 1; $i <= $numDays; $i++): ?>
                                    <option value="<?php echo $i; ?>">Day <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select> -->
                    </div>
                </div>

                <div class="table_section">
                    <table id="userTable" class="tb_eco">
                        <thead class="tb_head">
                            <tr>

                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <!-- <th>Email</th> -->
                                <th>Occupation</th>
                                <!-- <th>Affiliation</th> -->
                                <th>Contact No.</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if ($participantsResult->num_rows > 0) {
                                while ($row = $participantsResult->fetch_assoc()) {
                                    $fullName = htmlspecialchars($row['FirstName']) . ' ' . htmlspecialchars($row['LastName']);
                                    $age = htmlspecialchars($row['Age']);
                                    $gender = htmlspecialchars($row['Gender']);
                                    $email = htmlspecialchars($row['Email']);
                                    $affiliation = htmlspecialchars($row['Affiliation']);
                                    $position = htmlspecialchars($row['Position']);
                                    $image = htmlspecialchars($row['Image']);
                                    $contact = htmlspecialchars($row['ContactNo']);
                                    $educationalAttainment = htmlspecialchars($row['EducationalAttainment']);

                                    ?>
                                    <!-- Add the onclick event to trigger SweetAlert with participant info -->
                                    <tr
                                        onclick="showProfile('<?php echo $fullName; ?>', '<?php echo $age; ?>', '<?php echo $gender; ?>', '<?php echo $email; ?>', '<?php echo $affiliation; ?>', '<?php echo $position; ?>', '<?php echo $image; ?>' , '<?php echo $contact; ?>', '<?php echo $educationalAttainment; ?>')">
                                        <td><?php echo $fullName; ?></td>
                                        <td><?php echo $age; ?></td>
                                        <td><?php echo $gender; ?></td>
                                        <!-- <td><?php echo $email; ?></td> -->
                                        <td><?php echo $position; ?></td>
                                        <!-- <td><?php echo $affiliation; ?></td> -->
                                        <td><?php echo $contact; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center;'>No participants found for this event.</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        function showProfile(fullName, age, gender, email, affiliation, position, image, contact, educationalAttainment) {
            Swal.fire({
                title: 'Participant Profile',
                html: `
                    <div style="text-align: left; padding:2.5rem; ">

                        <div style="text-align: center; margin-bottom: 1rem;">
                        <img src="${image ? '../assets/img/profilePhoto/' + image : '../assets/img/profile.jpg'}" 
                            alt="Profile Image" 
                            style="width: 100px; height: 100px; border-radius: 50%;">
                        </div>

                        <strong><h3 style="margin-bottom: 0.25rem; ; margin-top: 4rem">Personal Info:</h3></strong>
                            <strong>Name:</strong> ${fullName}
                            <strong style="margin-left:5rem;">Age:</strong> ${age} <br/>
                            <strong>Gender:</strong> ${gender} <br/>
                            <strong>Educational Attainment:</strong> ${educationalAttainment}
                        <br/>
                        <br/>
       
                        <strong><h3 style="margin-bottom: 0.25rem;">Contact Info:</h3></strong>
                            <strong>Email:</strong> ${email} <br/>
                            <strong>Contact:</strong> ${contact} 
                        <br/>
                        <br/>

                        <strong><h3 style="margin-bottom: 0.25rem;">Profile Details:</h3></strong>
                            <strong>Affiliation:</strong> ${affiliation} <br/>
                            <strong>Occupation:</strong> ${position} 

                    </div>
                    `,
                customClass: {
                    popup: 'larger-swal'
                },
                confirmButtonText: 'Close'
            });

        }
    </script>

    <!--FILTER TABLE ROW-->
    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.querySelector('.tb_input');
            filter = input.value.toUpperCase().trim(); // Trim leading and trailing spaces
            console.log("Filter text:", filter);

            table = document.getElementById("userTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Change index to [0] for the name column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    console.log("Text content:", txtValue);
                    // Check if the text content contains the filter text as a substring
                    if (txtValue.toUpperCase().includes(filter)) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>



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




<!--real-time update-->
<script src="js/realTimeUpdate.js"></script>

</html>