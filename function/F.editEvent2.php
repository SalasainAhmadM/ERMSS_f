<?php
require_once('../db.connection/connection.php');

function showAlert($message, $redirectPath = null) {
    echo "<script>alert('$message');";
    if ($redirectPath) {
        echo "window.location.href = '$redirectPath';";
    }
    echo "</script>";
}

function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if (isset($_GET['event_id'])) {
    $eventId = cleanInput($_GET['event_id']);

    $sql = "SELECT * FROM pendingevents WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $eventDetails = $result->fetch_assoc();

        $eventTitle = $eventDetails['event_title'];
        $eventDescription = $eventDetails['event_description'];
        $eventType = $eventDetails['event_type'];
        $eventMode = $eventDetails['event_mode'];
        $eventLink = ($eventMode === 'Face-to-Face') ? '' : $eventDetails['event_link']; // Set to empty if Face-to-Face
        $eventLocation = ($eventMode === 'Online') ? '' : $eventDetails['location']; // Set to empty if Online
        $eventDateStart = $eventDetails['date_start'];
        $eventDateEnd = $eventDetails['date_end'];
        $eventTimeStart = $eventDetails['time_start'];
        $eventTimeEnd = $eventDetails['time_end'];
        $eventPhotoPath = $eventDetails['event_photo_path'];
        $participantLimit = $eventDetails['participant_limit']; // Retrieve participant limit

        $result->close();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventTitle = cleanInput($_POST['event_title']);
            $eventDescription = cleanInput($_POST['event_description']);
            $eventType = cleanInput($_POST['event_type']);
            $eventMode = cleanInput($_POST['event_mode']);
            $eventLocation = cleanInput($_POST['location']);
            $eventDateStart = cleanInput($_POST['date_start']);
            $eventDateEnd = cleanInput($_POST['date_end']);
            $eventTimeStart = cleanInput($_POST['time_start']);
            $eventTimeEnd = cleanInput($_POST['time_end']);
            $participantLimit = cleanInput($_POST['participant_limit']); // Retrieve participant limit from form data

            $eventPhotoPath = $eventDetails['event_photo_path']; // Use existing photo path as a default

            $uploadDir = "../admin/img/eventPhoto/";
            $uploadOk = 1;

            if (!empty($_FILES['event_photo']['name'])) {
                $newEventPhotoPath = $uploadDir . basename($_FILES['event_photo']['name']);

                if ($uploadOk == 1 && move_uploaded_file($_FILES['event_photo']['tmp_name'], $newEventPhotoPath)) {
                    $eventPhotoPath = $newEventPhotoPath;
                } else {
                }
            }

            $eventLink = ($eventMode === 'Hybrid' || $eventMode === 'Online') ? cleanInput($_POST['zoom_link']) : $eventLink;

            if ($eventMode === 'Online' && $eventDetails['event_mode'] !== 'Online') {
                $eventLocation = '';
            }

            if ($eventMode === 'Face-to-Face' && $eventDetails['event_mode'] !== 'Face-to-Face') {
                $eventLink = '';
            }

            if (!empty($_POST['event_cancel'])) {
                $cancelReason = cleanInput($_POST['event_cancel']);
                $cancelStatus = 'Cancelled';
            } else {
                $cancelReason = ''; // If cancel reason is not provided
                $cancelStatus = ''; // If event is not cancelled
            }

            $updateSql = "UPDATE pendingevents SET 
                event_title = ?, 
                event_description = ?, 
                event_type = ?, 
                event_mode = ?, 
                event_link = ?, 
                location = ?, 
                date_start = ?, 
                date_end = ?, 
                time_start = ?, 
                time_end = ?, 
                event_photo_path = ?, 
                cancelReason = ?, 
                event_cancel = ?, 
                participant_limit = ? 
                WHERE event_id = ?";

            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param(
                "sssssssssssssii", 
                $eventTitle, 
                $_POST['event_description'], 
                $_POST['event_type'], 
                $_POST['event_mode'], 
                $_POST['zoom_link'], 
                $_POST['location'], 
                $_POST['date_start'], 
                $_POST['date_end'], 
                $_POST['time_start'], 
                $_POST['time_end'], 
                $_FILES['event_photo']['name'], 
                $_POST['event_cancel'], 
                $cancelStatus, 
                $_POST['participant_limit'], 
                $eventId
            );

            if ($updateStmt->execute()) {
                $_SESSION['success'] = 'Event successfully updated!';
                header("Location: ../admin/pendingEvents2.php?event_id=$eventId&status=success");
                exit();
            } else {
                echo "Error updating record: " . $updateStmt->error;
            }
            
            $updateStmt->close();
        }
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    die("Event ID not provided.");
}
?>
