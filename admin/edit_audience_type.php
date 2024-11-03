<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $audience_type_id = $_POST['audience_type_id'];
    $audience_type_name = $_POST['audience_type_name'];

    $checkQuery = "SELECT * FROM audience_type WHERE audience_type_name = ? AND audience_type_id != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $audience_type_name, $audience_type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo json_encode(['success' => false, 'message' => 'audience type already exists.']);
    } else {

        $updateQuery = "UPDATE audience_type SET audience_type_name = ? WHERE audience_type_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $audience_type_name, $audience_type_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'audience type updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update audience type.']);
        }
    }

    $stmt->close();
}
?>