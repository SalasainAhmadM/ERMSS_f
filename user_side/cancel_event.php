<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure session is started
    session_start();

    // Check if UserID is set in the session
    if (isset($_SESSION['UserID'])) {
        $UserID = $_SESSION['UserID'];
        $event_id = $_POST['event_id'];
        $cancel_reason = $_POST['event_cancel_reason'];

        // Insert into cancel_reason table
        $insert_sql = "INSERT INTO cancel_reason (event_id, UserID, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iis", $event_id, $UserID, $cancel_reason);

        if ($stmt->execute()) {
            // Delete matching data in eventparticipants table
            $delete_sql = "DELETE FROM eventparticipants WHERE event_id = ? AND UserID = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $event_id, $UserID);

            if ($delete_stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to remove event participant']);
            }
            $delete_stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to insert cancellation reason']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'User not logged in']);
    }
}
?>