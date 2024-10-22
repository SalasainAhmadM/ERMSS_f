<?php
require_once('../db.connection/connection.php');

// Get the input data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['event_type_name'])) {
    $event_type_name = $data['event_type_name'];

    // Check if event type already exists
    $checkQuery = $conn->prepare("SELECT * FROM event_type WHERE event_type_name = ?");
    $checkQuery->bind_param('s', $event_type_name);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // Event type already exists
        echo json_encode(['success' => false, 'duplicate' => true]);
    } else {
        // Insert into event_type table
        $stmt = $conn->prepare("INSERT INTO event_type (event_type_name) VALUES (?)");
        $stmt->bind_param('s', $event_type_name);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'duplicate' => false]);
        }
        $stmt->close();
    }

    $checkQuery->close();
}

$conn->close();
?>
