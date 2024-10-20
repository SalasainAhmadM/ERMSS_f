<?php
// Check if a session is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve event_id from the query string
    $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

    if ($event_id > 0) {
        $sql = "SELECT *, IF(event_cancel IS NULL OR event_cancel = '', '', event_cancel) AS cancel_status, cancelReason FROM pendingevents WHERE event_id = $event_id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $eventTitle = $row['event_title'];
                $eventDesc = $row['event_description'];
                $eventLocation = $row['location'];
                $eventDateStart = date('F j, Y', strtotime($row['date_start'])); 
                $eventDateEnd = date('F j, Y', strtotime($row['date_end'])); 
                $eventTimeStart = date('h:ia', strtotime($row['time_start'])); 
                $eventTimeEnd = date('h:ia', strtotime($row['time_end'])); 
                $eventMode = $row['event_mode'];
                $eventLink = $row['event_link'];
                $eventType = $row['event_type'];
                $eventPhoto = $row['event_photo_path'];
                $eventCancel = $row['cancel_status']; // Use the cancel_status as event status
                $cancelReason = $row['cancelReason'];

                // Get current date and time in the event's timezone
                $eventTimeZone = new DateTimeZone('Asia/Manila');
                $currentDateTime = new DateTime('now', $eventTimeZone);
                $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
                $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

                // Check if the event is ongoing, upcoming, ended, or cancelled
                $eventStatus = '';

                if (!empty($eventCancel)) {
                    // If event is cancelled, set event status to cancellation status
                    $eventStatus = $eventCancel;
                } elseif ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
                    $eventStatus = 'Ongoing';
                } elseif ($currentDateTime < $eventStartDateTime) {
                    $eventStatus = 'Upcoming';
                } elseif ($currentDateTime > $eventEndDateTime) {
                    $eventStatus = 'Ended';
                }

                // Store retrieved data in session variables
                $_SESSION['event_data'] = array(
                    'eventTitle' => $eventTitle,
                    'eventDesc' => $eventDesc,
                    'eventLocation' => $eventLocation,
                    'eventDateStart' => $eventDateStart,
                    'eventDateEnd' => $eventDateEnd,
                    'eventTimeStart' => $eventTimeStart,
                    'eventTimeEnd' => $eventTimeEnd,
                    'eventMode' => $eventMode,
                    'eventLink' => $eventLink,
                    'eventType' => $eventType,
                    'eventPhoto' => $eventPhoto,
                    'eventStatus' => $eventStatus,
                    'cancelReason' => $cancelReason, // Include cancel reason in session data
                );
            }
        } else {
            echo "No records found for the specified event_id";
        }
    } else {
        echo "Invalid event_id";
    }
}
?>
