<?php
require_once('../db.connection/connection.php');

// Get participant_id and event_id from query parameters
$participantId = isset($_GET['participant_id']) ? intval($_GET['participant_id']) : 0;
$eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($participantId > 0 && $eventId > 0) {
    $sql = "SELECT status FROM evaluation WHERE participant_id = ? AND event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $participantId, $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => $row['status']]);
    } else {
        echo json_encode(['error' => 'No record found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid participant or event ID']);
}

$conn->close();
?>