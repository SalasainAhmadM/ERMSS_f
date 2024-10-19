<?php
require_once('../db.connection/connection.php');

function showAlert($message, $redirectPath = null) {
    echo "<script>alert('$message');";
    if ($redirectPath) {
        echo "window.location.href = '$redirectPath';";
    }
    echo "</script>";
}

// Function to clean user inputs
function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Check if the event_id is set in the URL
if (isset($_GET['event_id'])) {
    $eventId = cleanInput($_GET['event_id']);

    // Fetch event details from the database based on the event ID
    $sql = "SELECT * FROM Events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $eventDetails = $result->fetch_assoc();

        // Extract event details
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

        // Close the result set
        $result->close();

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data after cleaning
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

            // Initialize event photo path
            $eventPhotoPath = $eventDetails['event_photo_path']; // Use existing photo path as a default

            // File upload handling
            $uploadDir = "../admin/img/eventPhoto/";
            $uploadOk = 1;

            // Check if a new file is uploaded
            if (!empty($_FILES['event_photo']['name'])) {
                $newEventPhotoPath = $uploadDir . basename($_FILES['event_photo']['name']);

                // Additional file upload checks and handling (same as in F.addEvent.php)

                // Set the new photo path if upload is successful
                if ($uploadOk == 1 && move_uploaded_file($_FILES['event_photo']['tmp_name'], $newEventPhotoPath)) {
                    $eventPhotoPath = $newEventPhotoPath;
                } else {
                    // Handle file upload error
                }
            }

            // Retrieve form data for the event link
            $eventLink = ($eventMode === 'Hybrid' || $eventMode === 'Online') ? cleanInput($_POST['zoom_link']) : $eventLink;

            // Check if event mode is updated to 'Online'
            if ($eventMode === 'Online' && $eventDetails['event_mode'] !== 'Online') {
                // Delete data of location when updating to 'Online'
                $eventLocation = '';
            }

            // Check if event mode is updated to 'Face-to-Face'
            if ($eventMode === 'Face-to-Face' && $eventDetails['event_mode'] !== 'Face-to-Face') {
                // Delete data of event link when updating to 'Face-to-Face'
                $eventLink = '';
            }

            // Check if event is cancelled
            if (!empty($_POST['event_cancel'])) {
                $cancelReason = cleanInput($_POST['event_cancel']);
                $cancelStatus = 'Cancelled';
            } else {
                $cancelReason = ''; // If cancel reason is not provided
                $cancelStatus = ''; // If event is not cancelled
            }

            // Update the database with the new data using prepared statement
            $updateSql = "UPDATE Events SET 
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

            // Bind parameters
            $updateStmt->bind_param(
                "sssssssssssssii",
                $eventTitle,
                $eventDescription,
                $eventType,
                $eventMode,
                $eventLink,
                $eventLocation,
                $eventDateStart,
                $eventDateEnd,
                $eventTimeStart,
                $eventTimeEnd,
                $eventPhotoPath,
                $cancelReason,
                $cancelStatus,
                $participantLimit,
                $eventId
            );

            if ($updateStmt->execute()) {
                showAlert("Event updated successfully!", "../admin/landingPage.php");
                // Event updated successfully
                // Redirect or perform other actions after successful update
            } else {
                echo "Error updating record: " . $updateStmt->error;
            }

            // Close the statement
            $updateStmt->close();
        }
    } else {
        // Handle the error if the query fails
        die("Error: " . $stmt->error);
    }

    
} else {
    // Handle the case when event_id is not set in the URL
    die("Event ID not provided.");
}
?>
