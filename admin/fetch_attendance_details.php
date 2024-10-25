<?php
require_once('../db.connection/connection.php');

$participantId = isset($_GET['participant_id']) ? $_GET['participant_id'] : '';
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : '';

if (empty($participantId) || empty($eventId)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT day, attendance_date, status, time_in, time_out
        FROM attendance
        WHERE participant_id = ? AND event_id = ?
        ORDER BY attendance_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $participantId, $eventId);
$stmt->execute();
$result = $stmt->get_result();

$attendanceDetails = [];
while ($row = $result->fetch_assoc()) {
    $attendanceDetails[] = $row;
}

echo json_encode($attendanceDetails);
?>