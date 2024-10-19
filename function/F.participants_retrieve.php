<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitAccept'])) {
        $PendingUserID = $_POST['PendingUserID'];
        $userData = getUserData($PendingUserID);

        if ($userData) {
            if (acceptPendingUser($userData)) {
                $message = "User '{$userData['FirstName']} {$userData['LastName']}' approved successfully.";
            } else {
                $message = "Error processing approval.";
            }
        } else {
            $message = "Invalid PendingUserID.";
        }
    } elseif (isset($_POST['submitDecline'])) {
        $PendingUserID = $_POST['PendingUserID'];

        if (declinePendingUser($PendingUserID)) {
            $message = "User declined successfully.";
        } else {
            $message = "Error processing decline.";
        }
    }
}

function getUserData($PendingUserID)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM pendinguser WHERE PendingUserID = ?");
    $stmt->bind_param("s", $PendingUserID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function acceptPendingUser($userData)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO user (UserID, FirstName, LastName, MI, Email, Position, ContactNo, Affiliation, Password, Role) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $userData['PendingUserID'], $userData['FirstName'], $userData['LastName'], $userData['MI'], $userData['Email'], $userData['Position'], $userData['ContactNo'], $userData['Affiliation'], $userData['Password'], $userData['Role']);

    if ($stmt->execute()) {
        $stmt = $conn->prepare("DELETE FROM pendinguser WHERE PendingUserID = ?");
        $stmt->bind_param("s", $userData['PendingUserID']);
        return $stmt->execute();
    } else {
        return false;
    }
}

function declinePendingUser($PendingUserID)
{
    global $conn;

    $stmt = $conn->prepare("DELETE FROM pendinguser WHERE PendingUserID = ?");
    $stmt->bind_param("s", $PendingUserID);
    return $stmt->execute();
}
?>
