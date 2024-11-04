<?php
require_once('../db.connection/connection.php');


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if AdminID is set in the session
    if (isset($_SESSION['AdminID'])) {
        $AdminID = $_SESSION['AdminID'];

        // Prepare and execute a query to fetch the specific admin's data
        $sqlAdmin = "SELECT * FROM admin WHERE AdminID = ?";
        $stmtAdmin = $conn->prepare($sqlAdmin);
        $stmtAdmin->bind_param("i", $AdminID); // Assuming AdminID is an integer
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        // Populate variables with fetched data if the admin record exists
        if ($resultAdmin->num_rows > 0) {
            $row = $resultAdmin->fetch_assoc();

            // Assign variables only if values are found in the database row
            $FirstName = isset($row['FirstName']) ? $row['FirstName'] : null;
            $MI = isset($row['MI']) ? $row['MI'] : null;
            $LastName = isset($row['LastName']) ? $row['LastName'] : null;
            $Position = isset($row['Position']) ? $row['Position'] : null;
            $Email = isset($row['Email']) ? $row['Email'] : null;
            $ContactNo = isset($row['ContactNo']) ? $row['ContactNo'] : null;
            $Affiliation = isset($row['Affiliation']) ? $row['Affiliation'] : null;
            $Image = isset($row['Image']) ? $row['Image'] : null;
        } else {
            echo "No records found for the specified admin.";
        }

        $stmtAdmin->close();

    }
}
$sponsorFilter = $_POST['sponsorEventId'] ?? 'All Sponsors';

$sql = "SELECT e.* FROM Events e";

// Join with the sponsor table if a specific sponsor is selected
if ($sponsorFilter !== 'All Sponsors') {
    $sql .= " JOIN sponsor s ON e.event_id = s.event_id WHERE s.sponsor_Name = ?";
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
    $eventTimeZone = new DateTimeZone('Asia/Manila');
    $currentDateTime = new DateTime('now', $eventTimeZone);
    $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
    $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

    if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
        $eventStatus = 'ongoing';
    } elseif ($currentDateTime < $eventStartDateTime) {
        $eventStatus = 'upcoming';
    } elseif ($currentDateTime > $eventEndDateTime) {
        $eventStatus = 'ended';
    }

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

?>