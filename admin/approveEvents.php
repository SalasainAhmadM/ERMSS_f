<?php
require_once('../db.connection/connection.php');

// Get event_id from the URL
$eventId = $_GET['event_id'];

// Begin transaction
mysqli_begin_transaction($conn);

try {
    // 1. Fetch event data from `pendingevents`
    $sqlFetchEvent = "SELECT * FROM pendingevents WHERE event_id = $eventId";
    $resultFetchEvent = mysqli_query($conn, $sqlFetchEvent);

    if (mysqli_num_rows($resultFetchEvent) > 0) {
        $eventData = mysqli_fetch_assoc($resultFetchEvent);

        // 2. Insert event data into `events`
        $sqlInsertEvent = "
            INSERT INTO events (
                event_title, event_description, event_type, event_mode, event_photo_path, location, 
                date_start, date_end, time_start, time_end, date_created, event_link, cancelReason, 
                event_cancel, participant_limit, audience_type
            ) VALUES (
                '{$eventData['event_title']}', '{$eventData['event_description']}', '{$eventData['event_type']}', 
                '{$eventData['event_mode']}', '{$eventData['event_photo_path']}', '{$eventData['location']}', 
                '{$eventData['date_start']}', '{$eventData['date_end']}', '{$eventData['time_start']}', 
                '{$eventData['time_end']}', '{$eventData['date_created']}', '{$eventData['event_link']}', 
                '{$eventData['cancelReason']}', '{$eventData['event_cancel']}', 
                {$eventData['participant_limit']}, '{$eventData['audience_type']}'
            )";
        mysqli_query($conn, $sqlInsertEvent);
        $newEventId = mysqli_insert_id($conn); // Get the new event ID

        // 3. Fetch and insert related speakers into `speaker`
        $sqlFetchSpeakers = "SELECT * FROM pendingspeaker WHERE event_id = $eventId";
        $resultFetchSpeakers = mysqli_query($conn, $sqlFetchSpeakers);

        while ($speaker = mysqli_fetch_assoc($resultFetchSpeakers)) {
            $sqlInsertSpeaker = "
                INSERT INTO speaker (event_id, speaker_firstName, speaker_MI, speaker_lastName) 
                VALUES ($newEventId, '{$speaker['speaker_firstName']}', '{$speaker['speaker_MI']}', '{$speaker['speaker_lastName']}')";
            mysqli_query($conn, $sqlInsertSpeaker);
        }

        // 4. Fetch and insert related sponsors into `sponsor`
        $sqlFetchSponsors = "SELECT * FROM pendingsponsor WHERE event_id = $eventId";
        $resultFetchSponsors = mysqli_query($conn, $sqlFetchSponsors);

        while ($sponsor = mysqli_fetch_assoc($resultFetchSponsors)) {
            $sqlInsertSponsor = "
                INSERT INTO sponsor (event_id, sponsor_firstName, sponsor_MI, sponsor_lastName, sponsor_Name) 
                VALUES ($newEventId, '{$sponsor['sponsor_firstName']}', '{$sponsor['sponsor_MI']}', '{$sponsor['sponsor_lastName']}', '{$sponsor['sponsor_Name']}')";
            mysqli_query($conn, $sqlInsertSponsor);
        }

        // 5. Delete related entries from `pendingspeaker` and `pendingsponsor`
        $sqlDeleteSpeakers = "DELETE FROM pendingspeaker WHERE event_id = $eventId";
        mysqli_query($conn, $sqlDeleteSpeakers);

        $sqlDeleteSponsors = "DELETE FROM pendingsponsor WHERE event_id = $eventId";
        mysqli_query($conn, $sqlDeleteSponsors);

        // 6. Delete the event from `pendingevents`
        $sqlDeleteEvent = "DELETE FROM pendingevents WHERE event_id = $eventId";
        mysqli_query($conn, $sqlDeleteEvent);

        // Commit transaction
        mysqli_commit($conn);

        // Redirect back with success message
        header("Location: eventsValidation.php?status=approved");
    } else {
        echo "Event not found.";
    }
} catch (mysqli_sql_exception $exception) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    echo "Failed to approve event: " . $exception->getMessage();
}

// Close the connection
mysqli_close($conn);
?>