<?php
session_start();
require_once('../db.connection/connection.php');

if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);

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
