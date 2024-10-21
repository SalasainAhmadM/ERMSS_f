<?php
session_start();
require_once('../db.connection/connection.php');

if (isset($_GET['event_id'])) {
    $eventId = intval($_GET['event_id']);
    
    // Delete attendance records
    $deleteAttendanceSql = "DELETE FROM attendance WHERE participant_id IN (SELECT participant_id FROM eventparticipants WHERE event_id = ?)";
    if ($stmt = $conn->prepare($deleteAttendanceSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            $_SESSION['error'] = 'Error deleting attendance records!';
            redirectBasedOnRole($conn);
            exit;
        }
        $stmt->close();
    }

    // Delete event participants
    $deleteParticipantsSql = "DELETE FROM eventparticipants WHERE event_id = ?";
    if ($stmt = $conn->prepare($deleteParticipantsSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            $_SESSION['error'] = 'Error deleting participants!';
            redirectBasedOnRole($conn);
            exit;
        }
        $stmt->close();
    }

    // Delete the event
    $deleteEventSql = "DELETE FROM pendingevents WHERE event_id = ?";
    if ($stmt = $conn->prepare($deleteEventSql)) {
        $stmt->bind_param("i", $eventId);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Event deleted successfully!';
        } else {
            $_SESSION['error'] = 'Error deleting the event!';
        }
        $stmt->close();
        redirectBasedOnRole($conn); 
        exit;
    } else {
        $_SESSION['error'] = 'Failed to prepare the SQL statement!';
        redirectBasedOnRole($conn);
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid event ID!';
    redirectBasedOnRole($conn);
    exit;
}


function redirectBasedOnRole($conn) {
    if (isset($_SESSION['AdminID'])) {
        $AdminID = $_SESSION['AdminID'];
        $sqlAdmin = "SELECT Role FROM admin WHERE AdminID = ?";
        $stmtAdmin = $conn->prepare($sqlAdmin);
        $stmtAdmin->bind_param("i", $AdminID);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        if ($resultAdmin->num_rows > 0) {
            $row = $resultAdmin->fetch_assoc();
            $Role = $row['Role'];
            if ($Role === 'superadmin') {
                header('Location: eventsValidation.php'); 
            } else if ($Role === 'Admin') {
                header('Location: pendingEvents.php'); 
            } else {
                header('Location: adminDashboard.php'); 
            }
        }
        $stmtAdmin->close();
    }
    exit;
}
?>
