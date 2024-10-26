<?php
require_once('../db.connection/connection.php');

if (isset($_GET['participant_id']) && isset($_GET['event_id'])) {
    $participantId = $_GET['participant_id'];
    $eventId = $_GET['event_id'];

    // Prepare the SQL query to check if an evaluation record exists
    $query = "SELECT COUNT(*) AS count FROM evaluation WHERE participant_id = ? AND event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $participantId, $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if any record was found
    if ($row['count'] > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    $stmt->close();
} else {
    // Return an error if the required parameters are missing
    echo json_encode(['error' => 'Missing participant_id or event_id']);
}

$conn->close();
?>