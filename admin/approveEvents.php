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
            // Insert into events table
            $sqlInsertEvent = "INSERT INTO events (
                event_title, event_description, event_type, event_mode, 
                event_photo_path, location, date_start, date_end, 
                time_start, time_end, date_created, event_link, 
                cancelReason, event_cancel, participant_limit
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtInsertEvent = mysqli_prepare($conn, $sqlInsertEvent);
            mysqli_stmt_bind_param(
                $stmtInsertEvent,
                "ssssssssssssssi",
                $eventRow['event_title'],
                $eventRow['event_description'],
                $eventRow['event_type'],
                $eventRow['event_mode'],
                $eventRow['event_photo_path'],
                $eventRow['location'],
                $eventRow['date_start'],
                $eventRow['date_end'],
                $eventRow['time_start'],
                $eventRow['time_end'],
                $eventRow['date_created'],
                $eventRow['event_link'],
                $eventRow['cancelReason'],
                $eventRow['event_cancel'],
                $eventRow['participant_limit']
            );

            mysqli_stmt_execute($stmtInsertEvent);

            // Get the last inserted event_id
            $newEventId = mysqli_insert_id($conn);

            // Fetch up to 5 sponsors related to this event from pendingsponsor
            $sqlFetchSponsors = "SELECT * FROM pendingsponsor WHERE event_id = ? LIMIT 5";
            $stmtSponsors = mysqli_prepare($conn, $sqlFetchSponsors);
            mysqli_stmt_bind_param($stmtSponsors, "i", $eventId);
            mysqli_stmt_execute($stmtSponsors);
            $sponsorResult = mysqli_stmt_get_result($stmtSponsors);

            // Prepare the insert statement for sponsors
            $sqlInsertSponsor = "INSERT INTO sponsor (sponsor_id, event_id, sponsor_firstName, sponsor_MI, sponsor_lastName)
                                 VALUES (?, ?, ?, ?, ?)";
            $stmtInsertSponsor = mysqli_prepare($conn, $sqlInsertSponsor);

            // Loop through all sponsors and insert them into the sponsor table with the new event_id
            $sponsorCount = 0;
            while ($sponsorRow = mysqli_fetch_assoc($sponsorResult)) {
                mysqli_stmt_bind_param(
                    $stmtInsertSponsor,
                    "iisss",
                    $sponsorRow['sponsor_id'],
                    $newEventId,
                    $sponsorRow['sponsor_firstName'],
                    $sponsorRow['sponsor_MI'],
                    $sponsorRow['sponsor_lastName']
                );
                mysqli_stmt_execute($stmtInsertSponsor);
                $sponsorCount++;

                if ($sponsorCount >= 5) {
                    break; // Limit to 5 sponsors
                }
            }

            // Delete the sponsors from pendingsponsor first to avoid foreign key constraint violation
            $sqlDeleteSponsors = "DELETE FROM pendingsponsor WHERE event_id = ?";
            $stmtDeleteSponsors = mysqli_prepare($conn, $sqlDeleteSponsors);
            mysqli_stmt_bind_param($stmtDeleteSponsors, "i", $eventId);
            mysqli_stmt_execute($stmtDeleteSponsors);

            // Speakers
            // Fetch up to 5 speakers related to this event from pendingspeaker
            $sqlFetchSpeakers = "SELECT * FROM pendingspeaker WHERE event_id = ? LIMIT 5";
            $stmtSpeakers = mysqli_prepare($conn, $sqlFetchSpeakers);
            mysqli_stmt_bind_param($stmtSpeakers, "i", $eventId);
            mysqli_stmt_execute($stmtSpeakers);
            $speakerResult = mysqli_stmt_get_result($stmtSpeakers);

            // Prepare the insert statement for speakers
            $sqlInsertSpeaker = "INSERT INTO speaker (speaker_id, event_id, speaker_firstName, speaker_MI, speaker_lastName)
                                 VALUES (?, ?, ?, ?, ?)";
            $stmtInsertSpeaker = mysqli_prepare($conn, $sqlInsertSpeaker);

            // Loop through all speakers and insert them into the speaker table with the new event_id
            $speakerCount = 0;
            while ($speakerRow = mysqli_fetch_assoc($speakerResult)) {
                mysqli_stmt_bind_param(
                    $stmtInsertSpeaker,
                    "iisss",
                    $speakerRow['speaker_id'],
                    $newEventId,
                    $speakerRow['speaker_firstName'],
                    $speakerRow['speaker_MI'],
                    $speakerRow['speaker_lastName']
                );
                mysqli_stmt_execute($stmtInsertSpeaker);
                $speakerCount++;

                if ($speakerCount >= 5) {
                    break; // Limit to 5 speakers
                }
            }

            // Delete the speakers from pendingspeaker first to avoid foreign key constraint violation
            $sqlDeleteSpeakers = "DELETE FROM pendingspeaker WHERE event_id = ?";
            $stmtDeleteSpeakers = mysqli_prepare($conn, $sqlDeleteSpeakers);
            mysqli_stmt_bind_param($stmtDeleteSpeakers, "i", $eventId);
            mysqli_stmt_execute($stmtDeleteSpeakers);

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

    // Close statements and connection, but only if they are defined
    if (isset($stmtEvent))
        mysqli_stmt_close($stmtEvent);
    if (isset($stmtInsertEvent))
        mysqli_stmt_close($stmtInsertEvent);
    // Sponsors
    if (isset($stmtSponsors))
        mysqli_stmt_close($stmtSponsors);
    if (isset($stmtInsertSponsor))
        mysqli_stmt_close($stmtInsertSponsor);
    if (isset($stmtDeleteSponsors))
        mysqli_stmt_close($stmtDeleteSponsors);
    // Speakers
    if (isset($stmtSpeakers))
        mysqli_stmt_close($stmtSpeakers);
    if (isset($stmtInsertSpeaker))
        mysqli_stmt_close($stmtInsertSpeaker);
    if (isset($stmtDeleteSpeakers))
        mysqli_stmt_close($stmtDeleteSpeakers);
    if (isset($stmtDeleteEvent))
        mysqli_stmt_close($stmtDeleteEvent);

    mysqli_close($conn);
}

header('Location: landingPage.php');
exit;


?>