<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

// Initialize the response array
$response = array();

// Check if eventId is set and not empty
if (isset($_GET['eventId']) && !empty($_GET['eventId'])) {
    $eventId = $_GET['eventId'];

    // Perform deletion from the database
    $deleteQuery = "DELETE FROM Events WHERE event_id = $eventId";

    if (mysqli_query($conn, $deleteQuery)) {
        // Set success message in response
        $response['success'] = true;
    } else {
        // Set error message in response
        $response['success'] = false;
        $response['error'] = "Error deleting event: " . mysqli_error($conn);
    }
} else {
    // Set error message if eventId is not set
    $response['success'] = false;
    $response['error'] = "Event ID not provided";
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Exit to prevent any additional output
exit();
?>
