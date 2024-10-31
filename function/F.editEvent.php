<?php
require_once('../db.connection/connection.php');

function showAlert($message, $redirectPath = null)
{
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

    // Fetch event details
    $sql = "SELECT * FROM Events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $eventDetails = $result->fetch_assoc();

        $eventTitle = $eventDetails['event_title'];
        $eventDescription = $eventDetails['event_description'];
        $eventType = $eventDetails['event_type'];
        $eventMode = $eventDetails['event_mode'];
        $eventLink = ($eventMode === 'Face-to-Face') ? '' : $eventDetails['event_link'];
        $eventLocation = ($eventMode === 'Online') ? '' : $eventDetails['location'];
        $eventDateStart = $eventDetails['date_start'];
        $eventDateEnd = $eventDetails['date_end'];
        $eventTimeStart = $eventDetails['time_start'];
        $eventTimeEnd = $eventDetails['time_end'];
        $eventPhotoPath = $eventDetails['event_photo_path'];
        $participantLimit = $eventDetails['participant_limit'];

        $result->close();

        // Fetch speakers for the event
        $sqlSpeakers = "SELECT speaker_firstName, speaker_MI, speaker_lastName FROM speaker WHERE event_id = ?";
        $stmtSpeakers = $conn->prepare($sqlSpeakers);
        $stmtSpeakers->bind_param("i", $eventId);
        $stmtSpeakers->execute();
        $resultSpeakers = $stmtSpeakers->get_result();
        // Fetch sponsors for the event
        $sqlSponsors = "SELECT sponsor_firstName, sponsor_MI, sponsor_lastName FROM sponsor WHERE event_id = ?";
        $stmtSponsors = $conn->prepare($sqlSponsors);
        $stmtSponsors->bind_param("i", $eventId);
        $stmtSponsors->execute();
        $resultSponsors = $stmtSponsors->get_result();

        $sponsors = [];
        while ($row = $resultSponsors->fetch_assoc()) {
            $sponsors[] = $row;
        }
        $stmtSponsors->close();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Clean form data
            $eventTitle = cleanInput($_POST['event_title']);
            $eventDescription = cleanInput($_POST['event_description']);
            $eventType = cleanInput($_POST['event_type']);
            $eventMode = cleanInput($_POST['event_mode']);
            $eventLocation = cleanInput($_POST['location']);
            $eventDateStart = cleanInput($_POST['date_start']);
            $eventDateEnd = cleanInput($_POST['date_end']);
            $eventTimeStart = cleanInput($_POST['time_start']);
            $eventTimeEnd = cleanInput($_POST['time_end']);
            $participantLimit = cleanInput($_POST['participant_limit']);
            $eventPhotoPath = $eventDetails['event_photo_path'];

            // Handle file upload
            $uploadDir = "../admin/img/eventPhoto/";
            if (!empty($_FILES['event_photo']['name'])) {
                $newEventPhotoPath = $uploadDir . basename($_FILES['event_photo']['name']);
                if (move_uploaded_file($_FILES['event_photo']['tmp_name'], $newEventPhotoPath)) {
                    $eventPhotoPath = $newEventPhotoPath;
                }
            }

            // Update event link based on mode
            $eventLink = ($eventMode === 'Hybrid' || $eventMode === 'Online') ? cleanInput($_POST['zoom_link']) : '';

            if ($eventMode === 'Online') {
                $eventLocation = '';  // Clear location if Online
            }

            if ($eventMode === 'Face-to-Face') {
                $eventLink = '';  // Clear link if Face-to-Face
            }

            // Handle event cancellation
            $cancelReason = cleanInput($_POST['event_cancel_reason']);
            $cancelStatus = !empty($cancelReason) ? 'Cancelled' : '';

            // Check for duplicate event title
            $duplicateCheckSql = "SELECT COUNT(*) as count FROM Events WHERE event_title = ? AND event_id != ?";
            $duplicateCheckStmt = $conn->prepare($duplicateCheckSql);
            $duplicateCheckStmt->bind_param("si", $eventTitle, $eventId);
            $duplicateCheckStmt->execute();
            $duplicateResult = $duplicateCheckStmt->get_result();
            $duplicateRow = $duplicateResult->fetch_assoc();

            if ($duplicateRow['count'] > 0) {
                $_SESSION['duplicate'] = 'An event with this title already exists.';
                header("Location: ../admin/landingPage.php?event_id=$eventId&status=duplicate");
            } else {
                // Update the event details (same as before)
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
                    // First, delete existing sponsors for this event
                    $deleteSponsorSql = "DELETE FROM sponsor WHERE event_id = ?";
                    $stmtDeleteSponsor = $conn->prepare($deleteSponsorSql);
                    $stmtDeleteSponsor->bind_param("i", $eventId);
                    $stmtDeleteSponsor->execute();
                    $stmtDeleteSponsor->close();

                    // Handle sponsor updates/inserts
                    for ($i = 1; $i <= 5; $i++) {
                        $sponsorFirstName = cleanInput($_POST["sponsor{$i}_firstName"]);
                        $sponsorMI = cleanInput($_POST["sponsor{$i}_MI"]);
                        $sponsorLastName = cleanInput($_POST["sponsor{$i}_lastName"]);

                        if (!empty($sponsorFirstName) || !empty($sponsorMI) || !empty($sponsorLastName)) {
                            $insertSponsorSql = "INSERT INTO sponsor (event_id, sponsor_firstName, sponsor_MI, sponsor_lastName) 
                                                 VALUES (?, ?, ?, ?)";
                            $stmtInsertSponsor = $conn->prepare($insertSponsorSql);
                            $stmtInsertSponsor->bind_param("isss", $eventId, $sponsorFirstName, $sponsorMI, $sponsorLastName);
                            $stmtInsertSponsor->execute();
                            $stmtInsertSponsor->close();
                        }
                    }

                    // Redirect based on success (same as before)
                    if (!empty($cancelReason)) {
                        $_SESSION['cancelled'] = 'Event successfully cancelled!';
                        header("Location: ../admin/landingPage.php?event_id=$eventId&status=cancelled");
                    } else {
                        $_SESSION['success'] = 'Event successfully updated!';
                        header("Location: ../admin/landingPage.php?event_id=$eventId&status=success");
                    }
                    exit();
                } else {
                    echo "Error updating record: " . $updateStmt->error;
                }

                $updateStmt->close();
            }

            $duplicateCheckStmt->close();
        }
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    die("Event ID not provided.");
}


?>