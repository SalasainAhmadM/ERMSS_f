<?php
session_start();
require_once('../db.connection/connection.php');

$eventId = $_GET['event_id'];

if (isset($eventId)) {
    mysqli_begin_transaction($conn);

    try {
        // Fetch event details from pendingevents
        $sqlFetchEvent = "SELECT * FROM pendingevents WHERE event_id = ?";
        $stmtEvent = mysqli_prepare($conn, $sqlFetchEvent);
        mysqli_stmt_bind_param($stmtEvent, "i", $eventId);
        mysqli_stmt_execute($stmtEvent);
        $eventResult = mysqli_stmt_get_result($stmtEvent);

        if ($eventRow = mysqli_fetch_assoc($eventResult)) {
            // Get the next event_id by fetching the latest event_id from the events table
            $sqlLatestEventId = "SELECT MAX(event_id) AS latest_event_id FROM events";
            $resultLatestEventId = mysqli_query($conn, $sqlLatestEventId);
            $latestEventIdRow = mysqli_fetch_assoc($resultLatestEventId);
            $nextEventId = $latestEventIdRow['latest_event_id'] + 1;

            // Insert into events table using nextEventId
            $sqlInsertEvent = "INSERT INTO events (
                event_id, event_title, event_description, event_type, audience_type, event_mode, 
                event_photo_path, location, date_start, date_end, 
                time_start, time_end, date_created, event_link, participant_limit
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtInsertEvent = mysqli_prepare($conn, $sqlInsertEvent);
            mysqli_stmt_bind_param(
                $stmtInsertEvent,
                "isssssssssssssi",
                $nextEventId,
                $eventRow['event_title'],
                $eventRow['event_description'],
                $eventRow['event_type'],
                $eventRow['audience_type'],
                $eventRow['event_mode'],
                $eventRow['event_photo_path'],
                $eventRow['location'],
                $eventRow['date_start'],
                $eventRow['date_end'],
                $eventRow['time_start'],
                $eventRow['time_end'],
                $eventRow['date_created'],
                $eventRow['event_link'],
                $eventRow['participant_limit']
            );

            mysqli_stmt_execute($stmtInsertEvent);

            // Fetch up to 5 sponsors related to this event from pendingsponsor
            $sqlFetchSponsors = "SELECT * FROM pendingsponsor WHERE event_id = ? LIMIT 5";
            $stmtSponsors = mysqli_prepare($conn, $sqlFetchSponsors);
            mysqli_stmt_bind_param($stmtSponsors, "i", $eventId);
            mysqli_stmt_execute($stmtSponsors);
            $sponsorResult = mysqli_stmt_get_result($stmtSponsors);

            // Only proceed if there are sponsors
            if (mysqli_num_rows($sponsorResult) > 0) {
                $sqlInsertSponsor = "INSERT INTO sponsor (sponsor_id, event_id, sponsor_Name)
                                     VALUES (?, ?, ?)";
                $stmtInsertSponsor = mysqli_prepare($conn, $sqlInsertSponsor);

                $sponsorCount = 0;
                while ($sponsorRow = mysqli_fetch_assoc($sponsorResult)) {
                    mysqli_stmt_bind_param(
                        $stmtInsertSponsor,
                        "iis",
                        $sponsorRow['sponsor_id'],
                        $nextEventId,
                        $sponsorRow['sponsor_Name']
                    );
                    mysqli_stmt_execute($stmtInsertSponsor);
                    $sponsorCount++;

                    if ($sponsorCount >= 5) {
                        break;
                    }
                }

                // Delete sponsors from pendingsponsor
                $sqlDeleteSponsors = "DELETE FROM pendingsponsor WHERE event_id = ?";
                $stmtDeleteSponsors = mysqli_prepare($conn, $sqlDeleteSponsors);
                mysqli_stmt_bind_param($stmtDeleteSponsors, "i", $eventId);
                mysqli_stmt_execute($stmtDeleteSponsors);
            }

            // Fetch up to 5 speakers related to this event from pendingspeaker
            $sqlFetchSpeakers = "SELECT * FROM pendingspeaker WHERE event_id = ? LIMIT 5";
            $stmtSpeakers = mysqli_prepare($conn, $sqlFetchSpeakers);
            mysqli_stmt_bind_param($stmtSpeakers, "i", $eventId);
            mysqli_stmt_execute($stmtSpeakers);
            $speakerResult = mysqli_stmt_get_result($stmtSpeakers);

            // Only proceed if there are speakers
            if (mysqli_num_rows($speakerResult) > 0) {
                $sqlInsertSpeaker = "INSERT INTO speaker (speaker_id, event_id, speaker_firstName, speaker_MI, speaker_lastName)
                                     VALUES (?, ?, ?, ?, ?)";
                $stmtInsertSpeaker = mysqli_prepare($conn, $sqlInsertSpeaker);

                $speakerCount = 0;
                while ($speakerRow = mysqli_fetch_assoc($speakerResult)) {
                    mysqli_stmt_bind_param(
                        $stmtInsertSpeaker,
                        "iisss",
                        $speakerRow['speaker_id'],
                        $nextEventId,
                        $speakerRow['speaker_firstName'],
                        $speakerRow['speaker_MI'],
                        $speakerRow['speaker_lastName']
                    );
                    mysqli_stmt_execute($stmtInsertSpeaker);
                    $speakerCount++;

                    if ($speakerCount >= 5) {
                        break;
                    }
                }

                // Delete the speakers from pendingspeaker
                $sqlDeleteSpeakers = "DELETE FROM pendingspeaker WHERE event_id = ?";
                $stmtDeleteSpeakers = mysqli_prepare($conn, $sqlDeleteSpeakers);
                mysqli_stmt_bind_param($stmtDeleteSpeakers, "i", $eventId);
                mysqli_stmt_execute($stmtDeleteSpeakers);
            }

            // Now delete the event from pendingevents
            $sqlDeleteEvent = "DELETE FROM pendingevents WHERE event_id = ?";
            $stmtDeleteEvent = mysqli_prepare($conn, $sqlDeleteEvent);
            mysqli_stmt_bind_param($stmtDeleteEvent, "i", $eventId);
            mysqli_stmt_execute($stmtDeleteEvent);

            // Commit the transaction
            mysqli_commit($conn);

            $_SESSION['success'] = "Event Approved successfully.";
        } else {
            $_SESSION['error'] = "Event not found.";
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    // Close statements and connection if defined
    mysqli_close($conn);
}

header('Location: landingPage.php');
exit;
?>