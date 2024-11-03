<?php
require_once('../db.connection/connection.php');

// Get the input data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['audience_type_name'])) {
    $audience_type_name = $data['audience_type_name'];

    // Check if audience type already exists
    $checkQuery = $conn->prepare("SELECT * FROM audience_type WHERE audience_type_name = ?");
    $checkQuery->bind_param('s', $audience_type_name);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // audience type already exists
        echo json_encode(['success' => false, 'duplicate' => true]);
    } else {
        // Insert into audience_type table
        $stmt = $conn->prepare("INSERT INTO audience_type (audience_type_name) VALUES (?)");
        $stmt->bind_param('s', $audience_type_name);

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