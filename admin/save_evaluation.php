<?php
require_once('../db.connection/connection.php');

$data = json_decode(file_get_contents('php://input'), true);

$participant_id = $data['participant_id'];
$event_id = $data['event_id'];
$status = $data['status'];
$remarks = $data['remarks'];

$sql = "INSERT INTO evaluation (participant_id, event_id, status, remarks)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE status = ?, remarks = ?, updated_at = CURRENT_TIMESTAMP()";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iissss', $participant_id, $event_id, $status, $remarks, $status, $remarks);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save evaluation.']);
}

$stmt->close();
$conn->close();
?>