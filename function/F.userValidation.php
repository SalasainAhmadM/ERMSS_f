<?php
session_start(); // Start the session

require_once('sendEmail.php');
require_once('../db.connection/connection.php');

function showProfileModal($message) {
    echo "<script>
              showModal('$message');
          </script>";
}

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

$pendingUsersCount = countPendingUsers($conn);
function countPendingEvents($conn)
            {
                $sqls = "SELECT COUNT(*) AS totalPendingEvents FROM pendingevents";
                $result = $conn->query($sqls);
            
                if ($result) {
                    $row = $result->fetch_assoc();
                    return $row['totalPendingEvents'];
                } else {
                    return 0; 
                }
            }
            
            $pendingEventsCount = countPendingEvents($conn);
function getAdminData($conn, $AdminID) {
    $sql = "SELECT * FROM admin WHERE AdminID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $AdminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return false;
    }
}

// Check if AdminID is set in the session
if (isset($_SESSION['AdminID'])) {
    $AdminID = $_SESSION['AdminID']; // Retrieve AdminID from the session
    $adminData = getAdminData($conn, $AdminID);

    if ($adminData) {
        // Retrieve existing admin data
        $LastName = $adminData['LastName'];
        $FirstName = $adminData['FirstName'];
        $MI = $adminData['MI'];
        $Gender = $adminData['Gender'];
        $Email = $adminData['Email'];
        $ContactNo = $adminData['ContactNo'];
        $Address = $adminData['Address'];
        $Affiliation = $adminData['Affiliation'];
        $Position = $adminData['Position'];
        $Image = isset($adminData['Image']) ? $adminData['Image'] : null;
    } else {
        // Redirect or handle error if admin data is not found
        showProfileModal("Admin data not found");
        exit();
    }
} else {
    // Handle the case where AdminID is not set in the session
    showProfileModal("Admin session not found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitAccept'])) {
        $PendingUserID = $_POST['PendingUserID'];
        $userData = getUserData($PendingUserID);

        if ($userData) {
            if (acceptPendingUser($userData)) {
                $message = "User '{$userData['FirstName']} {$userData['LastName']}' approved successfully.";
                $_SESSION['userData'] = $userData; // Store userData in session
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

    $stmt = $conn->prepare("INSERT INTO user (FirstName, LastName, MI, Gender, Email, Position, ContactNo, Affiliation, Password, Role) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $userData['FirstName'], $userData['LastName'], $userData['MI'], $userData['Gender'], $userData['Email'], $userData['Position'], $userData['ContactNo'], $userData['Affiliation'], $userData['Password'], $userData['Role']);

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
