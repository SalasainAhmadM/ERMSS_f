<?php
session_start();
require_once('../db.connection/connection.php');

function showProfileModal($message)
{
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

function getAdminData($conn, $AdminID)
{
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
    $Role = $adminData['Role'];
} else {
    showProfileModal("Admin data not found");
    exit();
}

function generateUniqueEventID($conn)
{
    $sqlMaxEvents = "SELECT MAX(event_id) as maxEventID FROM events";
    $sqlMaxPendingEvents = "SELECT MAX(event_id) as maxPendingEventID FROM pendingevents";

    $resultEvents = $conn->query($sqlMaxEvents);
    $resultPendingEvents = $conn->query($sqlMaxPendingEvents);

    $maxEventID = ($resultEvents->num_rows > 0) ? $resultEvents->fetch_assoc()['maxEventID'] : 0;
    $maxPendingEventID = ($resultPendingEvents->num_rows > 0) ? $resultPendingEvents->fetch_assoc()['maxPendingEventID'] : 0;

    return max($maxEventID, $maxPendingEventID) + 1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventTitle = $_POST['event_title'];
    $eventDescription = $_POST['event_description'];
    $eventType = $_POST['event_type'];
    $eventMode = $_POST['event_mode'];
    $audienceType = $_POST['audience_type'];
    $eventLocation = strtolower(trim($_POST['location']));
    $eventDateStart = trim($_POST['date_start']);
    $eventDateEnd = trim($_POST['date_end']);
    $eventTimeStart = trim($_POST['time_start']);
    $eventTimeEnd = trim($_POST['time_end']);
    $participantLimit = isset($_POST['participant_limit']) ? (int) $_POST['participant_limit'] : 0;

    $uploadDir = "../admin/img/eventPhoto/";
    $eventPhotoPath = "";

    // Check for duplicate event title in 'events' table
    $sqlCheckTitle = "SELECT * FROM events WHERE event_title = ? LIMIT 1";
    $stmtCheckTitle = $conn->prepare($sqlCheckTitle);
    $stmtCheckTitle->bind_param("s", $eventTitle);
    $stmtCheckTitle->execute();
    $resultCheckTitle = $stmtCheckTitle->get_result();

    if ($resultCheckTitle->num_rows > 0) {
        $_SESSION['error'] = 'An active event with this title already exists!';
        header("Location: addEvent.php");
        exit();
    }

    // Check for duplicate event title in 'pendingevents' table
    $sqlCheckTitle = "SELECT * FROM pendingevents WHERE event_title = ? LIMIT 1";
    $stmtCheckTitle = $conn->prepare($sqlCheckTitle);
    $stmtCheckTitle->bind_param("s", $eventTitle);
    $stmtCheckTitle->execute();
    $resultCheckTitle = $stmtCheckTitle->get_result();

    if ($resultCheckTitle->num_rows > 0) {
        $_SESSION['error'] = 'A pending event with this title already exists!';
        header("Location: addEvent.php");
        exit();
    }

    if (isset($_FILES['event_photo']) && $_FILES['event_photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['event_photo']['tmp_name'];
        $photoName = $_FILES['event_photo']['name'];
        $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);

        $newPhotoName = uniqid('event_', true) . '.' . $photoExtension;
        $eventPhotoPath = $uploadDir . $newPhotoName;

        if (!move_uploaded_file($photoTmpPath, $eventPhotoPath)) {
            echo "<script>alert('Error uploading event photo.');</script>";
            exit();
        }
    }

    $eventLink = isset($_POST['zoom_link']) ? $_POST['zoom_link'] : '';

    // Check if location is already occupied at the specified date and time
    $sqlCheckExisting = "SELECT * FROM events WHERE location = ? AND date_start = ? AND time_start = ? LIMIT 1";
    $stmtCheckExisting = $conn->prepare($sqlCheckExisting);
    $stmtCheckExisting->bind_param("sss", $eventLocation, $eventDateStart, $eventTimeStart);
    $stmtCheckExisting->execute();
    $resultCheckExisting = $stmtCheckExisting->get_result();

    if ($resultCheckExisting->num_rows > 0) {
        $_SESSION['error'] = 'Location venue is already occupied on this date in the current events!';
        header("Location: addEvent.php");
        exit();
    }

    $sqlCheckPending = "SELECT * FROM pendingevents WHERE location = ? AND date_start = ? AND time_start = ? LIMIT 1";
    $stmtCheckPending = $conn->prepare($sqlCheckPending);
    $stmtCheckPending->bind_param("sss", $eventLocation, $eventDateStart, $eventTimeStart);
    $stmtCheckPending->execute();
    $resultCheckPending = $stmtCheckPending->get_result();

    if ($resultCheckPending->num_rows > 0) {
        $_SESSION['error'] = 'Location venue is already occupied on this date in the pending events!';
        header("Location: addEvent.php");
        exit();
    }

    // Generate new unique event ID
    $newEventID = generateUniqueEventID($conn);

    // Insert the event based on user role
    if ($Role == 'superadmin') {
        $sql = "INSERT INTO events (event_id, event_title, event_description, event_type, event_mode, audience_type, location, date_start, date_end, time_start, time_end, participant_limit, event_photo_path, event_link) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } elseif ($Role == 'Admin') {
        $sql = "INSERT INTO pendingevents (event_id, event_title, event_description, event_type, event_mode, audience_type, location, date_start, date_end, time_start, time_end, participant_limit, event_photo_path, event_link) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        echo "<script>alert('You do not have the necessary permissions to create an event.'); window.location.href='landingPage.php';</script>";
        exit();
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isssssssssssss",
        $newEventID,
        $eventTitle,
        $eventDescription,
        $eventType,
        $eventMode,
        $audienceType,
        $eventLocation,
        $eventDateStart,
        $eventDateEnd,
        $eventTimeStart,
        $eventTimeEnd,
        $participantLimit,
        $eventPhotoPath,
        $eventLink
    );

    if ($stmt->execute()) {

        if ($Role == 'superadmin') {
            // Sponsor Insertion logic
            for ($i = 1; $i <= 5; $i++) {
                $sponsorName = isset($_POST["sponsor{$i}_Name"]) ? $_POST["sponsor{$i}_Name"] : null;
                // $sponsorFirstName = isset($_POST["sponsor{$i}_firstName"]) ? $_POST["sponsor{$i}_firstName"] : null;
                // $sponsorMI = isset($_POST["sponsor{$i}_MI"]) ? $_POST["sponsor{$i}_MI"] : null;
                // $sponsorLastName = isset($_POST["sponsor{$i}_lastName"]) ? $_POST["sponsor{$i}_lastName"] : null;

                if (
                    $sponsorName
                    //     $sponsorFirstName
                    //  && $sponsorLastName
                ) {
                    $sponsorSql = "INSERT INTO sponsor (event_id, sponsor_Name) 
                               VALUES (?, ?)";
                    $sponsorStmt = $conn->prepare($sponsorSql);
                    $sponsorStmt->bind_param("is", $newEventID, $sponsorName);
                    // $sponsorSql = "INSERT INTO sponsor (event_id, sponsor_firstName, sponsor_MI, sponsor_lastName) 
                    // VALUES (?, ?, ?, ?)";
                    // $sponsorStmt = $conn->prepare($sponsorSql);
                    // $sponsorStmt->bind_param("isss", $newEventID, $sponsorFirstName, $sponsorMI, $sponsorLastName);
                    $sponsorStmt->execute();
                    $sponsorStmt->close();
                }
            }
            // Speaker Insertion logic
            for ($i = 1; $i <= 5; $i++) {
                $speakerFirstName = isset($_POST["speaker{$i}_firstName"]) ? $_POST["speaker{$i}_firstName"] : null;
                $speakerMI = isset($_POST["speaker{$i}_MI"]) ? $_POST["speaker{$i}_MI"] : null;
                $speakerLastName = isset($_POST["speaker{$i}_lastName"]) ? $_POST["speaker{$i}_lastName"] : null;

                if ($speakerFirstName && $speakerLastName) {
                    $speakerSql = "INSERT INTO speaker (event_id, speaker_firstName, speaker_MI, speaker_lastName) 
                               VALUES (?, ?, ?, ?)";
                    $speakerStmt = $conn->prepare($speakerSql);
                    $speakerStmt->bind_param("isss", $newEventID, $speakerFirstName, $speakerMI, $speakerLastName);
                    $speakerStmt->execute();
                    $speakerStmt->close();
                }
            }
            $_SESSION['success'] = 'Event successfully created!';
        } elseif ($Role == 'Admin') {
            // Sponsor Insertion logic
            for ($i = 1; $i <= 5; $i++) {
                $sponsorName = isset($_POST["sponsor{$i}_Name"]) ? $_POST["sponsor{$i}_Name"] : null;
                // $sponsorFirstName = isset($_POST["sponsor{$i}_firstName"]) ? $_POST["sponsor{$i}_firstName"] : null;
                // $sponsorMI = isset($_POST["sponsor{$i}_MI"]) ? $_POST["sponsor{$i}_MI"] : null;
                // $sponsorLastName = isset($_POST["sponsor{$i}_lastName"]) ? $_POST["sponsor{$i}_lastName"] : null;

                if (
                    $sponsorName
                    // $sponsorFirstName && $sponsorLastName
                ) {
                    $sponsorSql = "INSERT INTO pendingsponsor (event_id, sponsor_Name) 
                               VALUES (?,?)";
                    $sponsorStmt = $conn->prepare($sponsorSql);
                    $sponsorStmt->bind_param("is", $newEventID, $sponsorName);
                    // $sponsorSql = "INSERT INTO pendingsponsor (event_id, sponsor_firstName, sponsor_MI, sponsor_lastName) 
                    // VALUES (?, ?, ?, ?)";
                    // $sponsorStmt = $conn->prepare($sponsorSql);
                    // $sponsorStmt->bind_param("isss", $newEventID, $sponsorFirstName, $sponsorMI, $sponsorLastName);
                    $sponsorStmt->execute();
                    $sponsorStmt->close();
                }
            }
            // Speaker Insertion logic
            for ($i = 1; $i <= 5; $i++) {
                $speakerFirstName = isset($_POST["speaker{$i}_firstName"]) ? $_POST["speaker{$i}_firstName"] : null;
                $speakerMI = isset($_POST["speaker{$i}_MI"]) ? $_POST["speaker{$i}_MI"] : null;
                $speakerLastName = isset($_POST["speaker{$i}_lastName"]) ? $_POST["speaker{$i}_lastName"] : null;

                if ($speakerFirstName && $speakerLastName) {
                    $speakerSql = "INSERT INTO pendingspeaker (event_id, speaker_firstName, speaker_MI, speaker_lastName) 
                               VALUES (?, ?, ?, ?)";
                    $speakerStmt = $conn->prepare($speakerSql);
                    $speakerStmt->bind_param("isss", $newEventID, $speakerFirstName, $speakerMI, $speakerLastName);
                    $speakerStmt->execute();
                    $speakerStmt->close();
                }
            }
            $_SESSION['success2'] = 'Event successfully created and is now pending approval!';
        }
        header("Location: addEvent.php");
        exit();
    } else {
        $_SESSION['error'] = 'Error creating event: ' . $stmt->error;
        header("Location: addEvent.php");
    }

    $stmt->close();
    $conn->close();
}

?>