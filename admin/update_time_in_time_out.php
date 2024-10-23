<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the POST request
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    $timeIn = $_POST['timeIn'];
    $timeOut = $_POST['timeOut'];

    // SQL update statement to update only time_in and time_out
    $sql = "UPDATE attendance 
            SET time_in = ?, time_out = ? 
            WHERE participant_id = ? AND event_id = ?";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $timeIn, $timeOut, $participant_id, $event_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Attendance updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating time: ' . $stmt->error]);
    }

    $stmt->close();
    exit; // Ensure no further output after this
}
?>