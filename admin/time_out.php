<?php
require_once('../db.connection/connection.php');
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POSTed data
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    $attendance_date = $_POST['attendance_date'];

    // Set the current time for time_out
    $current_time_out = date('H:i:s');  // This formats the time as HH:MM:SS

    // Check if attendance already exists for the participant on the given date
    $sql = "SELECT * FROM attendance WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $participant_id, $event_id, $attendance_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a record exists, update the time_out field
        $update_sql = "UPDATE attendance SET time_out = ? WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("siis", $current_time_out, $participant_id, $event_id, $attendance_date);

        if ($update_stmt->execute()) {
            // Success response
            echo json_encode(['status' => 'success', 'message' => 'Time out successfully recorded.']);
        } else {
            // Error response
            echo json_encode(['status' => 'error', 'message' => 'Failed to update time out.']);
        }
    } else {
        // If no record found, return an error
        echo json_encode(['status' => 'error', 'message' => 'No attendance record found for this participant on the specified date.']);
    }

    // Close the statements and connection
    $stmt->close();
    $update_stmt->close();
    $conn->close();
} else {
    // If the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>