<?php
session_start();
require_once('../db.connection/connection.php');

if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);

    $deleteFromEvaluationSql = "DELETE FROM evaluation WHERE participant_id IN (SELECT participant_id FROM eventparticipants WHERE UserID = ?)";
    if ($stmt = $conn->prepare($deleteFromEvaluationSql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "<script>alert('Failed to delete related evaluation records!'); window.location.href='allUser.php';</script>";
        exit;
    }

    $deleteFromAttendanceSql = "DELETE FROM attendance WHERE participant_id IN (SELECT participant_id FROM eventparticipants WHERE UserID = ?)";
    if ($stmt = $conn->prepare($deleteFromAttendanceSql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "<script>alert('Failed to delete related attendance records!'); window.location.href='allUser.php';</script>";
        exit;
    }

    $deleteFromEventParticipantsSql = "DELETE FROM eventparticipants WHERE UserID = ?";
    if ($stmt1 = $conn->prepare($deleteFromEventParticipantsSql)) {
        $stmt1->bind_param("i", $userId);
        $stmt1->execute();
        $stmt1->close();
    } else {
        echo "<script>alert('Failed to delete related event participants!'); window.location.href='allUser.php';</script>";
        exit;
    }

    $deleteUserSql = "DELETE FROM user WHERE UserID = ?";
    if ($stmt2 = $conn->prepare($deleteUserSql)) {
        $stmt2->bind_param("i", $userId);
        if ($stmt2->execute()) {
            $_SESSION['success'] = 'User deleted successfully!';
            header('Location: allUser.php');
            exit;
        } else {
            echo "<script>alert('Error deleting user!'); window.location.href='allUser.php';</script>";
        }
        $stmt2->close();
    } else {
        echo "<script>alert('Failed to prepare the SQL statement!'); window.location.href='allUser.php';</script>";
    }

} else {
    echo "<script>alert('Invalid user ID!'); window.location.href='allUser.php';</script>";
}

mysqli_close($conn);
?>