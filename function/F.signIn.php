<?php
session_start(); // Start the session

require_once('../db.connection/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $Email = trim($_POST["Email"]); // Assuming Email is used as the username
    $Password = trim($_POST["Password"]);

    // Check admins table
    $sqlAdmins = "SELECT adminID, password, role FROM admin WHERE email = ?";
    $stmtAdmins = $conn->prepare($sqlAdmins);
    $stmtAdmins->bind_param("s", $Email);
    $stmtAdmins->execute();
    $resultAdmins = $stmtAdmins->get_result();

    if ($resultAdmins->num_rows == 1) {
        $row = $resultAdmins->fetch_assoc();
        if (password_verify($Password, $row["password"])) {
            $_SESSION['AdminID'] = $row['adminID'];
            $_SESSION['Role'] = $row['role'];
            header("Location: ../admin/adminDashboard.php");
            exit();
        }
    }

    // Check users table
    $sqlUsers = "SELECT userID, password FROM user WHERE email = ?";
    $stmtUsers = $conn->prepare($sqlUsers);
    $stmtUsers->bind_param("s", $Email);
    $stmtUsers->execute();
    $resultUsers = $stmtUsers->get_result();

    if ($resultUsers->num_rows == 1) {
        $row = $resultUsers->fetch_assoc();
        if (password_verify($Password, $row["password"])) {
            // User login successful, set UserID in the session
            $_SESSION["UserID"] = $row["userID"];

            // Check for missing attendance and insert "absent" records for all participants
            markAbsentDaysForAll($conn);

            header("Location: ../user_side/userDashboard.php");
            exit();
        }
    }

    // If no match found, redirect to an error page or handle accordingly
    header("Location: error/errorLogin.php");
    exit();
}

// Function to mark absent days for all participants
function markAbsentDaysForAll($conn)
{
    $today = date('Y-m-d');

    // Fetch all events and their participants whose end date has not passed
    $sqlEventsParticipants = "SELECT ep.participant_id, ep.event_id, e.date_start, e.date_end 
                              FROM eventparticipants ep
                              JOIN events e ON ep.event_id = e.event_id
                              WHERE e.date_end >= ?";
    $stmtEventsParticipants = $conn->prepare($sqlEventsParticipants);
    $stmtEventsParticipants->bind_param("s", $today);
    $stmtEventsParticipants->execute();
    $resultEventsParticipants = $stmtEventsParticipants->get_result();

    while ($eventParticipant = $resultEventsParticipants->fetch_assoc()) {
        $participantID = $eventParticipant['participant_id'];
        $eventID = $eventParticipant['event_id'];
        $dateStart = $eventParticipant['date_start'];
        $dateEnd = $eventParticipant['date_end'];

        // Generate dates from the start date to the day before today
        $interval = new DateInterval('P1D');
        $period = new DatePeriod(new DateTime($dateStart), $interval, new DateTime($today));

        foreach ($period as $date) {
            $attendanceDate = $date->format("Y-m-d");

            // Check if an attendance record exists for this date
            $sqlCheck = "SELECT * FROM attendance 
                         WHERE participant_id = ? 
                         AND event_id = ? 
                         AND attendance_date = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("iis", $participantID, $eventID, $attendanceDate);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            // If no attendance record, insert an "absent" entry
            if ($resultCheck->num_rows == 0) {
                $dayNumber = (new DateTime($attendanceDate))->diff(new DateTime($dateStart))->days + 1;

                $sqlInsert = "INSERT INTO attendance (participant_id, event_id, attendance_date, status, day)
                              VALUES (?, ?, ?, 'absent', ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bind_param("iisi", $participantID, $eventID, $attendanceDate, $dayNumber);
                $stmtInsert->execute();
            }
        }
    }

    echo "Absent records inserted for missed days.";
}
?>