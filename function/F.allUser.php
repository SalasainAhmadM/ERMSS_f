<?php

session_start();
require_once('../db.connection/connection.php');

// Function to show profile modal
function showProfileModal($message) {
    echo "<script>
              showModal('$message');
          </script>";
}

// Function to count pending users
function countPendingUsers($conn) {
    $sql = "SELECT COUNT(*) AS totalPendingUsers FROM pendinguser";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['totalPendingUsers'];
    } else {
        return 0; // Return 0 if there is an error or no pending users
    }
}

$pendingUsersCount = countPendingUsers($conn);

// Function to get admin data
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

$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
// Function to fetch user data
function getAllUsers() {
    global $conn;

    $usersQuery = "SELECT * FROM user"; // Modify this query as per your table structure
    $usersResult = mysqli_query($conn, $usersQuery);
    $users = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);

    return $users;
}

// Call the function to get all users
$allUsers = getAllUsers();

// Function to count users
function countUsers($conn) {
    $sql = "SELECT COUNT(*) AS totalUsers FROM user";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['totalUsers'];
    } else {
        return 0; // Return 0 if there is an error or no users
    }
}

// Call the function to count users
$totalUsersCount = countUsers($conn);


?>