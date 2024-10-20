<?php
    require_once('../db.connection/connection.php');

    // Get the event ID from the URL
    if (isset($_GET['event_id'])) {
        $eventId = intval($_GET['event_id']);
          
        $deleteAttendanceSql = "DELETE FROM attendance WHERE participant_id IN (SELECT participant_id FROM eventparticipants WHERE event_id = ?)";
        
        // Prepare statement
        if ($stmt = $conn->prepare($deleteAttendanceSql)) {
            $stmt->bind_param("i", $eventId);
            // Execute the statement
            if (!$stmt->execute()) {
                echo "<script>alert('Error deleting attendance records!'); window.location.href='landingPage.php';</script>";
                exit;
            }
            $stmt->close();
        }

        $deleteParticipantsSql = "DELETE FROM eventparticipants WHERE event_id = ?";
        
        // Prepare statement
        if ($stmt = $conn->prepare($deleteParticipantsSql)) {
            $stmt->bind_param("i", $eventId);
            // Execute the statement
            if (!$stmt->execute()) {
                echo "<script>alert('Error deleting participants!'); window.location.href='landingPage.php';</script>";
                exit;
            }
            $stmt->close();
        }

        $deleteEventSql = "DELETE FROM Events WHERE event_id = ?";
        
        if ($stmt = $conn->prepare($deleteEventSql)) {
            $stmt->bind_param("i", $eventId);
            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Event, participants, and attendance records deleted successfully!'); window.location.href='landingPage.php';</script>";
            } else {
                echo "<script>alert('Error deleting event!'); window.location.href='landingPage.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Failed to prepare the SQL statement!'); window.location.href='landingPage.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid event ID!'); window.location.href='landingPage.php';</script>";
    }

    mysqli_close($conn);
?>
