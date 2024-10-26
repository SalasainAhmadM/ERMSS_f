<?php
require_once('../db.connection/connection.php');

$participantId = isset($_GET['participant_id']) ? $_GET['participant_id'] : '';
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : '';

if (empty($participantId) || empty($eventId)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT attendance.attendance_date, attendance.status, attendance.time_in, attendance.time_out,
               events.date_start, events.date_end
        FROM attendance
        JOIN events ON attendance.event_id = events.event_id
        WHERE attendance.participant_id = ? AND attendance.event_id = ?
        ORDER BY attendance.attendance_date ASC";

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