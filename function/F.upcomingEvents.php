<?php
// Include your database connection
require_once('../db.connection/connection.php');

// Get the sponsor filter value from the POST request
$sponsorFilter = $_POST['sponsorEventId'] ?? 'All Sponsors';

// Create the base query to fetch events
$sql = "SELECT e.* FROM Events e";

// Modify the query if a specific sponsor is selected
if ($sponsorFilter !== 'All Sponsors') {
    $sql .= " JOIN sponsor s ON e.event_id = s.event_id WHERE s.sponsor_Name = ? AND (e.event_cancel IS NULL OR e.event_cancel = '')";
} else {
    $sql .= " WHERE e.event_cancel IS NULL OR e.event_cancel = ''";
}

$sql .= " ORDER BY e.date_created DESC";

$stmt = $conn->prepare($sql);

if ($sponsorFilter !== 'All Sponsors') {
    $stmt->bind_param('s', $sponsorFilter);
}

$stmt->execute();
$result = $stmt->get_result();

// Check if there are no results
if ($result->num_rows === 0) {
    echo "<tr><td colspan='8' style='text-align: center;'>No Upcoming Events!</td></tr>";
} else {
    // Loop through each event and output a table row
    while ($row = $result->fetch_assoc()) {
        $eventTitle = $row['event_title'];
        $eventLocation = $row['location'];
        $eventDateStart = date('F j, Y', strtotime($row['date_start']));
        $eventDateEnd = date('F j, Y', strtotime($row['date_end']));
        $eventTimeStart = date('h:ia', strtotime($row['time_start']));
        $eventTimeEnd = date('h:ia', strtotime($row['time_end']));
        $eventMode = $row['event_mode'];
        $eventType = $row['event_type'];
        $eventId = $row['event_id'];

        // Determine event status
        $eventStatus = '';
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], new DateTimeZone('Asia/Manila'));
        $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], new DateTimeZone('Asia/Manila'));

        if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
            $eventStatus = 'ongoing';
        } elseif ($currentDateTime < $eventStartDateTime) {
            $eventStatus = 'upcoming';
        } elseif ($currentDateTime > $eventEndDateTime) {
            $eventStatus = 'ended';
        }

          // Skip the event if its status is "ended"
        if ($eventStatus === 'ended') {
            continue;
        }

        // Display the event row
        echo "<tr data-start-date='{$row['date_start']}' data-end-date='{$row['date_end']}'>";
        echo "<td data-label='Event Title'>{$eventTitle}</td>";
        echo "<td data-label='Event Type'>{$eventType}</td>";
        echo "<td data-label='Event Mode'>{$eventMode}</td>";
        echo "<td data-label='Event Location'>{$eventLocation}</td>";
        echo "<td data-label='Event Date'>{$eventDateStart} - {$eventDateEnd}</td>";
        echo "<td data-label='Event Time'>{$eventTimeStart} - {$eventTimeEnd}</td>";
        echo "<td data-label='Status'>{$eventStatus}</td>";
        echo "<td data-label='View Event' class='pad'><a href='view_eventHistory.php?event_id={$eventId}'><button class='btn_view'><i class='fa-solid fa-eye'></i></button></a></td>";
        echo "</tr>";
    }
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>