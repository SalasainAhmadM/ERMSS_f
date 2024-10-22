<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $event_mode_id = $_POST['event_mode_id'];

    $deleteQuery = "DELETE FROM event_mode WHERE event_mode_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $event_mode_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event mode deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete event mode.']);
    }

    $stmt->close();
}
?>