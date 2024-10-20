<?php
require_once('../db.connection/connection.php');

if (isset($_POST['event_id'])) {
    $eventId = mysqli_real_escape_string($conn, $_POST['event_id']);

    // SQL query to delete the event
    $sql = "DELETE FROM Events WHERE event_id = '$eventId'";

    if (mysqli_query($conn, $sql)) {
        echo "Event deleted successfully.";
    } else {
        echo "Error deleting event: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Event ID is required.";
}
?>
