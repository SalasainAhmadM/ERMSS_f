<?php
session_start();
require_once('../db.connection/connection.php');

$eventId = $_GET['event_id'];

if (isset($eventId)) {
    mysqli_begin_transaction($conn);

    try {
        $sqlFetch = "SELECT * FROM pendingevents WHERE event_id = ?";
        $stmt = mysqli_prepare($conn, $sqlFetch);
        mysqli_stmt_bind_param($stmt, "i", $eventId); 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $sqlInsert = "INSERT INTO events (event_title, event_description, event_type, event_mode, event_photo_path, location, date_start, date_end, time_start, time_end, date_created, event_link, cancelReason, event_cancel, participant_limit)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = mysqli_prepare($conn, $sqlInsert);
            
            mysqli_stmt_bind_param($stmtInsert, "ssssssssssssssi", 
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
            
            mysqli_stmt_execute($stmtInsert);

            $sqlDelete = "DELETE FROM pendingevents WHERE event_id = ?";
            $stmtDelete = mysqli_prepare($conn, $sqlDelete);
            mysqli_stmt_bind_param($stmtDelete, "i", $eventId);
            mysqli_stmt_execute($stmtDelete);

            mysqli_commit($conn);

            $_SESSION['success'] = "Event approved successfully.";
        } else {
            $_SESSION['error'] = "Event not found.";
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

header('Location: landingPage.php');
exit;
?>
