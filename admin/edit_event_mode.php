<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $event_mode_id = $_POST['event_mode_id'];
    $event_mode_name = $_POST['event_mode_name'];

    $checkQuery = "SELECT * FROM event_mode WHERE event_mode_name = ? AND event_mode_id != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $event_mode_name, $event_mode_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo json_encode(['success' => false, 'message' => 'Event mode already exists.']);
    } else {
        
        $updateQuery = "UPDATE event_mode SET event_mode_name = ? WHERE event_mode_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $event_mode_name, $event_mode_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Event mode updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update event mode.']);
        }
    }

    $stmt->close();
}
?>