<?php
session_start();
require_once('../db.connection/connection.php');

if (isset($_GET['event_id'])) {
    $eventId = intval($_GET['event_id']);

    // Step 1: Delete evaluations associated with the event
    $deleteEvaluationSql = "DELETE FROM evaluation WHERE event_id = ?";
    if ($stmt = $conn->prepare($deleteEvaluationSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            echo "<script>alert('Error deleting evaluation records!'); window.location.href='landingPage2.php';</script>";
            exit;
        }
        $stmt->close();
    }

    // Step 2: Delete attendance
    $deleteAttendanceSql = "DELETE FROM attendance WHERE participant_id IN (SELECT participant_id FROM eventparticipants WHERE event_id = ?)";
    if ($stmt = $conn->prepare($deleteAttendanceSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            echo "<script>alert('Error deleting attendance records!'); window.location.href='landingPage2.php';</script>";
            exit;
        }
        $stmt->close();
    }

    // Step 3: Delete event participants
    $deleteParticipantsSql = "DELETE FROM eventparticipants WHERE event_id = ?";
    if ($stmt = $conn->prepare($deleteParticipantsSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            echo "<script>alert('Error deleting participants!'); window.location.href='landingPage2.php';</script>";
            exit;
        }
        $stmt->close();
    }

    // Step 4: Delete sponsors
    $deletePendingSponsorsSql = "DELETE FROM sponsor WHERE event_id = ?";
    if ($stmt = $conn->prepare($deletePendingSponsorsSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            $_SESSION['error'] = 'Error deleting pending sponsors!';
            redirectBasedOnRole($conn);
            exit;
        }
        $stmt->close();
    }

    // Step 5: Delete speakers
    $deletePendingSpeakersSql = "DELETE FROM speaker WHERE event_id = ?";
    if ($stmt = $conn->prepare($deletePendingSpeakersSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            $_SESSION['error'] = 'Error deleting pending speakers!';
            redirectBasedOnRole($conn);
            exit;
        }
        $stmt->close();
    }

    // Step 6: Delete cancel_reason
    $deleteCancelReasonSql = "DELETE FROM cancel_reason WHERE event_id = ?";
    if ($stmt = $conn->prepare($deleteCancelReasonSql)) {
        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            $_SESSION['error'] = 'Error deleting cancel reason!';
            redirectBasedOnRole($conn);
            exit;
        }
        $stmt->close();
    }

    // Step 7: Finally, delete the event itself
    $deleteEventSql = "DELETE FROM Events WHERE event_id = ?";
    if ($stmt = $conn->prepare($deleteEventSql)) {
        $stmt->bind_param("i", $eventId);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Deleted successfully!';
            header('Location: history.php');
            exit;
        } else {
            echo "<script>alert('Error deleting event!'); window.location.href='history.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare the SQL statement!'); window.location.href='history.php';</script>";
    }
} else {
    echo "<script>alert('Invalid event ID!'); window.location.href='history.php';</script>";
}

mysqli_close($conn);

?>