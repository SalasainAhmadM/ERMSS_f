<?php
session_start();
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_mode_id = $_POST['event_mode_id'];

    // Fetch event mode name by ID
    $fetchEventmodeQuery = "SELECT event_mode_name FROM event_mode WHERE event_mode_id = ?";
    $stmt = $conn->prepare($fetchEventmodeQuery);
    $stmt->bind_param("i", $event_mode_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $eventmode = $result->fetch_assoc();

    if ($eventmode) {
        // List of event modes that cannot be deleted
        $protectedEventmodes = [
            'Face-to-Face',
            'Online',
            'Hybrid'
        ];
        // Check if the event mode is protected
        if (in_array($eventmode['event_mode_name'], $protectedEventmodes)) {
            $_SESSION['error'] = 'Can\'t delete origin event mode: ' . $eventmode['event_mode_name'];
            echo json_encode(['success' => false, 'message' => $_SESSION['error']]);
        } else {
            // Proceed with deletion if not protected
            $deleteQuery = "DELETE FROM event_mode WHERE event_mode_id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $event_mode_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Event mode deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete event mode.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Event mode not found.']);
    }

    $stmt->close();
}
?>