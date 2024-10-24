<?php
require_once('../db.connection/connection.php');

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Get current time in Asia/Manila time zone if status is 'present'
    $time_in = ($status === 'present') ? date('H:i:s') : NULL;

    // Check if attendance already exists for this participant, event, and date
    $check_sql = "SELECT * FROM attendance WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('iis', $participant_id, $event_id, $attendance_date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing attendance record
        $update_sql = "UPDATE attendance 
                       SET status = ?, time_in = IF(? = 'present', ?, NULL), updated_at = NOW() 
                       WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('sssiis', $status, $status, $time_in, $participant_id, $event_id, $attendance_date);
        if ($update_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update attendance']);
        }
    } else {
        // Insert new attendance record
        $insert_sql = "INSERT INTO attendance (participant_id, event_id, attendance_date, status, time_in, created_at) 
                       VALUES (?, ?, ?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param('iisss', $participant_id, $event_id, $attendance_date, $status, $time_in);
        if ($insert_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to record attendance']);
        }
    }
}
?>