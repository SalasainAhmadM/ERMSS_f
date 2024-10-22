<?php
require_once('../db.connection/connection.php');

// Get the input data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['event_mode_name'])) {
    $event_mode_name = $data['event_mode_name'];

    // Check if event mode already exists
    $checkQuery = $conn->prepare("SELECT * FROM event_mode WHERE event_mode_name = ?");
    $checkQuery->bind_param('s', $event_mode_name);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // Event mode already exists
        echo json_encode(['success' => false, 'duplicate' => true]);
    } else {
        // Insert into event_mode table
        $stmt = $conn->prepare("INSERT INTO event_mode (event_mode_name) VALUES (?)");
        $stmt->bind_param('s', $event_mode_name);

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
