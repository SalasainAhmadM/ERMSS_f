<?php
require_once('../db.connection/connection.php');

if (isset($_POST['participant_id'], $_POST['event_id'], $_POST['selectedDate'])) {

    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    $selectedDate = $_POST['selectedDate'];

    // Check if an attendance record exists for the participant, event, and date
    $check_sql = "SELECT * FROM attendance 
                  WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iis", $participant_id, $event_id, $selectedDate);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // If the record exists, proceed with deletion
        $sql = "DELETE FROM attendance 
                WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $participant_id, $event_id, $selectedDate);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance has been reset.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to reset attendance.']);
        }
        $stmt->close();
    } else {
        // If no matching record is found, return an error
        echo json_encode(['status' => 'error', 'message' => 'No attendance yet.']);
    }
    $check_stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}