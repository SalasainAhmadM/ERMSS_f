<?php
require_once('../db.connection/connection.php');

// Read input JSON data
$data = json_decode(file_get_contents("php://input"), true);
$participantId = isset($data['participant_id']) ? intval($data['participant_id']) : 0;
$eventId = isset($data['event_id']) ? intval($data['event_id']) : 0;
$status = isset($data['status']) ? $data['status'] : '';

if ($participantId > 0 && $eventId > 0 && in_array($status, ['approved', 'declined', 'no_record'])) {
    $sql = "UPDATE evaluation SET status = ?, updated_at = NOW() WHERE participant_id = ? AND event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $status, $participantId, $eventId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update status']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data provided']);
}

$conn->close();
?>