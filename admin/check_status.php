<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    $attendance_date = $_POST['attendance_date'];

    // Select both status and time_out to check if time_out exists
    $sql = "SELECT status, time_out FROM attendance WHERE participant_id = ? AND event_id = ? AND attendance_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $participant_id, $event_id, $attendance_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row['status'];
        $time_out = $row['time_out'];

        if (!empty($time_out)) {
            echo json_encode(['status' => $status, 'time_out' => true]);
        } else {
            echo json_encode(['status' => $status, 'time_out' => false]);
        }
    } else {
        echo json_encode(['status' => 'notMarked']);
    }
}
?>