<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve event_id from the query string
    $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

    if ($event_id > 0) {
        $sql = "SELECT * FROM Events WHERE event_id = $event_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $eventTitle = $row['event_title'];
                $eventDesc = $row['event_description'];
                $eventLocation = $row['location'];
                $eventDateStart = $row['date_start'];
                $eventDateEnd = $row['date_end'];
                $eventTimeStart = $row['time_start'];
                $eventTimeEnd = $row['time_end'];
                $eventMode = $row['event_mode'];
                $eventType = $row['event_type'];
                
            }
        } else {
            echo "No records found for the specified event_id";
        }
    } else {
        echo "Invalid event_id";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request for updating the event
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

    if ($event_id > 0) {
        // Retrieve form data
        $eventTitle = $_POST['event_title'];
        $eventDesc = $_POST['event_description'];
        $eventType = $_POST['event_type'];
        $eventMode = $_POST['event_mode'];
        $eventLocation = $_POST['location'];
        $eventDateStart = $_POST['date_start'];
        $eventDateEnd = $_POST['date_end'];
        $eventTimeStart = $_POST['time_start'];
        $eventTimeEnd = $_POST['time_end'];

        // Update the database
        $sql = "UPDATE Events SET 
            event_title = '$eventTitle',
            event_description = '$eventDesc',
            event_type = '$eventType',
            event_mode = '$eventMode',
            location = '$eventLocation',
            date_start = '$eventDateStart',
            date_end = '$eventDateEnd',
            time_start = '$eventTimeStart',
            time_end = '$eventTimeEnd'
            WHERE event_id = $event_id";

        if (mysqli_query($conn, $sql)) {
            // Event updated successfully
            // Redirect or perform other actions after successful update
            header("Location: landingPage.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Invalid event_id";
    }
}
?>
