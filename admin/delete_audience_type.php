<?php
session_start();
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $audience_type_id = $_POST['audience_type_id'];

    // Fetch audience type name by ID
    $fetchAudienceTypeQuery = "SELECT audience_type_name FROM audience_type WHERE audience_type_id = ?";
    $stmt = $conn->prepare($fetchAudienceTypeQuery);
    $stmt->bind_param("i", $audience_type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $audienceType = $result->fetch_assoc();

    if ($audienceType) {
        // List of audience types that cannot be deleted
        $protectedAudienceTypes = [
            'Audience Title',
            'Training Sessions',
            'Specialized Seminars',
            'Cluster-specific gathering',
            'General Assembly',
            'Workshop'
        ];

        // Check if the audience type is protected
        if (in_array($audienceType['audience_type_name'], $protectedAudienceTypes)) {
            $_SESSION['error'] = 'Can\'t delete origin audience type: ' . $audienceType['audience_type_name'];
            echo json_encode(['success' => false, 'message' => $_SESSION['error']]);
        } else {
            // Proceed with deletion if not protected
            $deleteQuery = "DELETE FROM audience_type WHERE audience_type_id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $audience_type_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Audience type deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete audience type.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Audience type not found.']);
    }

    $stmt->close();
}
?>