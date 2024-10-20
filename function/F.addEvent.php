<?php
session_start();
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
        return 0; 
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

$AdminID = $_SESSION['AdminID']; 
$adminData = getAdminData($conn, $AdminID);

if ($adminData) {
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
    showProfileModal("Admin data not found");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventTitle = $_POST['event_title'];
    $eventDescription = $_POST['event_description'];
    $eventType = $_POST['event_type'];
    $eventMode = $_POST['event_mode'];
    $eventLocation = strtolower(trim($_POST['location'])); // Normalize location input
    $eventDateStart = trim($_POST['date_start']); // Normalize date input
    $eventDateEnd = trim($_POST['date_end']);
    $eventTimeStart = trim($_POST['time_start']); // Normalize time input
    $eventTimeEnd = trim($_POST['time_end']);
    
    $participantLimit = isset($_POST['participant_limit']) ? (int)$_POST['participant_limit'] : 0;
    
    $eventPhotoPath = "";
    $eventLink = "";

    // Check if the event with the same location, date, and time already exists
    $sqlCheckExisting = "SELECT * FROM Events WHERE location = ? AND date_start = ? AND time_start = ? LIMIT 1";
    $stmtCheckExisting = $conn->prepare($sqlCheckExisting);
    $stmtCheckExisting->bind_param("sss", $eventLocation, $eventDateStart, $eventTimeStart);
    $stmtCheckExisting->execute();
    $resultCheckExisting = $stmtCheckExisting->get_result();

    if ($resultCheckExisting->num_rows > 0) {
        echo "<script>alert('Location venue is already occupied on this date!'); window.location.href='landingPage.php';</script>";
        exit(); // Stop further execution
    }

    // Insert into the database using parameterized query
    $sql = "INSERT INTO Events (event_title, event_description, event_type, event_mode, location, date_start, date_end, time_start, time_end, participant_limit, event_photo_path, event_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssss",
        $eventTitle,
        $eventDescription,
        $eventType,
        $eventMode,
        $eventLocation,
        $eventDateStart,
        $eventDateEnd,
        $eventTimeStart,
        $eventTimeEnd,
        $participantLimit,  // Ensure correct parameter binding
        $eventPhotoPath,
        $eventLink
    );

    if ($stmt->execute()) {
        echo "<script>alert('Event successfully created!'); window.location.href='landingPage.php';</script>";
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>
