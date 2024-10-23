<?php
session_start();
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_type_id = $_POST['event_type_id'];

    // Fetch event type name by ID
    $fetchEventTypeQuery = "SELECT event_type_name FROM event_type WHERE event_type_id = ?";
    $stmt = $conn->prepare($fetchEventTypeQuery);
    $stmt->bind_param("i", $event_type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $eventType = $result->fetch_assoc();

    if ($eventType) {
        // List of event types that cannot be deleted
        $protectedEventTypes = [
            'Event Title',
            'Training Sessions',
            'Specialized Seminars',
            'Cluster-specific gathering',
            'General Assembly',
            'Workshop'
        ];

        // Check if the event type is protected
        if (in_array($eventType['event_type_name'], $protectedEventTypes)) {
            $_SESSION['error'] = 'Can\'t delete origin event type: ' . $eventType['event_type_name'];
            echo json_encode(['success' => false, 'message' => $_SESSION['error']]);
        } else {
            // Proceed with deletion if not protected
            $deleteQuery = "DELETE FROM event_type WHERE event_type_id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $event_type_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Event type deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete event type.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Event type not found.']);
    }

    $stmt->close();
}
?>