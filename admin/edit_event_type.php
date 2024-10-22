<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $event_type_id = $_POST['event_type_id'];
    $event_type_name = $_POST['event_type_name'];

    $checkQuery = "SELECT * FROM event_type WHERE event_type_name = ? AND event_type_id != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $event_type_name, $event_type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo json_encode(['success' => false, 'message' => 'Event type already exists.']);
    } else {
        
        $updateQuery = "UPDATE event_type SET event_type_name = ? WHERE event_type_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $event_type_name, $event_type_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Event type updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update event type.']);
        }
    }

    $stmt->close();
}
?>