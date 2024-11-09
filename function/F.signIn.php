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
            $_SESSION['Role'] = $row['role']; // corrected 'Admin' to 'role'
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
            // User login successful, set UserID in the session and redirect
            $_SESSION["UserID"] = $row["userID"];

            // Insert "absent" record if not already done today
            $userID = $row["userID"];
            $sqlAbsent = "INSERT INTO attendance (participant_id, event_id, attendance_date, status, created_at, day)
                SELECT 
                    ep.participant_id,
                    ep.event_id,
                    CURDATE() AS attendance_date,
                    'absent' AS status,
                    NOW() AS created_at,
                    DATEDIFF(CURDATE(), e.date_start) + 1 AS day
                FROM 
                    eventparticipants ep
                INNER JOIN 
                    events e ON ep.event_id = e.event_id
                LEFT JOIN 
                    attendance a ON a.participant_id = ep.participant_id 
                    AND a.event_id = ep.event_id 
                    AND a.attendance_date = CURDATE()
                WHERE 
                    e.date_start <= CURDATE() 
                    AND e.date_end >= CURDATE() 
                    AND a.attendance_date IS NULL
                    AND ep.participant_id = ?";

            $stmtAbsent = $conn->prepare($sqlAbsent);
            $stmtAbsent->bind_param("i", $userID);
            $stmtAbsent->execute();

            header("Location: ../user_side/userDashboard.php");
            exit();
        }
    }

    // If no match found, redirect to an error page or handle accordingly
    header("Location: error/errorLogin.php");
    exit();
}
?>