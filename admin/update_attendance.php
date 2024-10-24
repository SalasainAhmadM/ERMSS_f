<?php
require_once('../db.connection/connection.php');
date_default_timezone_set('Asia/Manila');

// Function to update attendance record to time-out
function updateTimeOut($conn, $participant_id, $event_id, $attendance_date)
{
    $time_now = date('H:i:s');
    $update_sql = "UPDATE attendance 
                   SET time_out = ?, updated_at = NOW() 
                   WHERE participant_id = ? AND event_id = ? AND attendance_date = ? AND status = 'present' AND time_in IS NOT NULL";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('siis', $time_now, $participant_id, $event_id, $attendance_date);
    if ($update_stmt->execute()) {
        return json_encode(['status' => 'success', 'message' => 'Time-out recorded successfully']);
    } else {
        return json_encode(['status' => 'error', 'message' => 'Failed to record time-out']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];
    $time_now = date('H:i:s');

    // Check if attendance record already exists
    $check_sql = "SELECT status, time_in, time_out FROM attendance WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('iis', $participant_id, $event_id, $attendance_date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Attendance exists, so we update the record
        $attendance = $check_result->fetch_assoc();
        $existingStatus = $attendance['status'];
        $existingTimeIn = $attendance['time_in'];

        if ($existingStatus === 'present' && $status === 'timeout' && $existingTimeIn !== NULL) {
            // If participant is present and has time_in, record time_out
            echo updateTimeOut($conn, $participant_id, $event_id, $attendance_date);
        } elseif ($existingStatus === 'absent' && $status === 'absent') {
            echo json_encode(['status' => 'alreadyAbsent']);
        } elseif ($existingStatus === 'present' && $existingTimeIn !== NULL) {
            // If already present with time_in, just indicate the participant is present
            echo json_encode(['status' => 'alreadyPresent', 'message' => 'Already time-in. Please time-out.']);
        } else {
            // Other updates (like marking present or absent)
            $update_sql = "UPDATE attendance 
                           SET status = ?, 
                               time_in = IF(? = 'present' AND time_in IS NULL, ?, time_in), 
                               time_out = IF(? = 'timeout', ?, time_out), 
                               updated_at = NOW() 
                           WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param('sssssiis', $status, $status, $time_now, $status, $time_now, $participant_id, $event_id, $attendance_date);
            $update_stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Attendance updated successfully']);
        }
    } else {
        // Insert new attendance record
        $time_in = ($status === 'present') ? $time_now : NULL;
        $time_out = ($status === 'timeout') ? $time_now : NULL;

        $insert_sql = "INSERT INTO attendance (participant_id, event_id, attendance_date, status, time_in, time_out, created_at) 
                       VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param('iissss', $participant_id, $event_id, $attendance_date, $status, $time_in, $time_out);
        $insert_stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully']);
    }
}
?>