<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_type_id = $_POST['event_type_id'];

    $deleteQuery = "DELETE FROM event_type WHERE event_type_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $event_type_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event type deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete event type.']);
    }

    $stmt->close();
}
?>