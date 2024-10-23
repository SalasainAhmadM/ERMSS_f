<?php
session_start();
require_once('../db.connection/connection.php');

$alertMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitp'])) {
    $userId = $_SESSION['UserID']; // Get user ID from session
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Fetch the current password hash from database
    $sql = "SELECT Password FROM user WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($storedPasswordHash);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (password_verify($currentPassword, $storedPasswordHash)) {
        // Check if new passwords match
        if ($newPassword === $confirmNewPassword) {
            // Encrypt the new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update password in database
            $updateSql = "UPDATE user SET Password = ? WHERE UserID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $newPasswordHash, $userId);
            if ($updateStmt->execute()) {
                $alertMessage = "
<script>
    Swal.fire({
        title: 'Success!',
        text: 'Password updated successfully.',
        icon: 'success',
        customClass: { popup: 'larger-swal' }
    });
</script>";
            } else {
                $alertMessage = "
<script>
    Swal.fire({
        title: 'Error!',
        text: 'Failed to update password.',
        icon: 'error',
        customClass: { popup: 'larger-swal' }
    });
</script>";
            }
            $updateStmt->close();
        } else {
            $alertMessage = "
<script>
    Swal.fire({
        title: 'Error!',
        text: 'New password and confirmation do not match.',
        icon: 'error',
        customClass: { popup: 'larger-swal' }
    });
</script>";
        }
    } else {
        $alertMessage = "
<script>
    Swal.fire({
        title: 'Error!',
        text: 'Current password is incorrect.',
        icon: 'error',
        customClass: { popup: 'larger-swal' }
    });
</script>";
    }

    $conn->close();
}
?>