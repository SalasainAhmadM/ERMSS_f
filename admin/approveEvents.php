<?php
    // Include your database connection code
    require_once('../db.connection/connection.php');

    // Get the event ID from the URL
    $eventId = $_GET['event_id'];

    // Check if the event ID exists
    if (isset($eventId)) {
        // Start a transaction
        mysqli_begin_transaction($conn);

        try {
            // Fetch the event details from the pendingevents table
            $sqlFetch = "SELECT * FROM pendingevents WHERE event_id = ?";
            $stmt = mysqli_prepare($conn, $sqlFetch);
            mysqli_stmt_bind_param($stmt, "i", $eventId); // Bind the event_id as an integer
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // If the event exists in the pendingevents table
            if ($row = mysqli_fetch_assoc($result)) {
                // Insert the event into the events table
                $sqlInsert = "INSERT INTO events (event_id, event_title, event_description, event_type, event_mode, event_photo_path, location, date_start, date_end, time_start, time_end, date_created, event_link, cancelReason, event_cancel, participant_limit)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $sqlInsert);
                
                // Bind the parameters to the INSERT statement
                mysqli_stmt_bind_param($stmtInsert, "issssssssssssssi", 
                    $row['event_id'], 
                    $row['event_title'], 
                    $row['event_description'], 
                    $row['event_type'], 
                    $row['event_mode'], 
                    $row['event_photo_path'], 
                    $row['location'], 
                    $row['date_start'], 
                    $row['date_end'], 
                    $row['time_start'], 
                    $row['time_end'], 
                    $row['date_created'], 
                    $row['event_link'], 
                    $row['cancelReason'], 
                    $row['event_cancel'], 
                    $row['participant_limit']
                );
                
                // Execute the INSERT statement
                mysqli_stmt_execute($stmtInsert);

                // Delete the event from the pendingevents table
                $sqlDelete = "DELETE FROM pendingevents WHERE event_id = ?";
                $stmtDelete = mysqli_prepare($conn, $sqlDelete);
                mysqli_stmt_bind_param($stmtDelete, "i", $eventId);
                mysqli_stmt_execute($stmtDelete);

                // Commit the transaction
                mysqli_commit($conn);

                // Redirect to a success page or display a success message
                echo "Event approved successfully.";
            } else {
                echo "Event not found.";
            }

        } catch (Exception $e) {
            // Rollback the transaction in case of error
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }

        // Close statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "No event ID provided.";
    }
?>
