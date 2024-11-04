<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

// Fetch the selected sponsor filter, if any
$sponsorFilter = isset($_POST['sponsorEventId']) && $_POST['sponsorEventId'] !== 'All Sponsors' ? $_POST['sponsorEventId'] : null;

// Build the SQL query with the sponsor filter
$sql = "SELECT Events.*, sponsor.sponsor_Name 
        FROM Events 
        LEFT JOIN sponsor ON Events.event_id = sponsor.event_id 
        WHERE (event_cancel IS NULL OR event_cancel = '')";

if ($sponsorFilter) {
    $sql .= " AND sponsor.sponsor_Name = '" . mysqli_real_escape_string($conn, $sponsorFilter) . "'";
}

$sql .= " ORDER BY date_created DESC";
$result = mysqli_query($conn, $sql);

// Loop through each event and generate a table row for ongoing events
while ($row = mysqli_fetch_assoc($result)) {
    $eventTitle = $row['event_title'];
    $eventLocation = $row['location'];
    $eventDateStart = date('F j, Y', strtotime($row['date_start']));
    $eventDateEnd = date('F j, Y', strtotime($row['date_end']));
    $eventTimeStart = date('h:ia', strtotime($row['time_start']));
    $eventTimeEnd = date('h:ia', strtotime($row['time_end']));
    $eventMode = $row['event_mode'];
    $eventType = $row['event_type'];
    $eventId = $row['event_id'];

    // Get current date and time in the event's timezone
    $eventTimeZone = new DateTimeZone('Asia/Manila');
    $currentDateTime = new DateTime('now', $eventTimeZone);
    $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
    $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

    // Check if the event is ongoing
    $eventStatus = '';

    if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
        $eventStatus = 'ongoing';
    }

    // Only display ongoing events
    if ($eventStatus === 'ongoing') {
        echo '<tr data-start-date="' . $row['date_start'] . '" data-end-date="' . $row['date_end'] . '">';
        ?>
        <td data-label="Event Title"><?php echo $eventTitle; ?></td>
        <td data-label="Event Type"><?php echo $eventType; ?></td>
        <td data-label="Event Mode"><?php echo $eventMode; ?></td>
        <td data-label="Event Location"><?php echo $eventLocation; ?></td>
        <td data-label="Event Date"><?php echo "$eventDateStart - $eventDateEnd"; ?></td>
        <td data-label="Event Time"><?php echo "$eventTimeStart - $eventTimeEnd"; ?></td>
        <td data-label="Status"><?php echo $eventStatus; ?></td>
        <td data-label="View Event" class="pad">
            <a href="view_event.php?event_id=<?php echo $row['event_id']; ?>"><button class="btn_view"><i
                        class="fa-solid fa-eye"></i></i></button></a>
        </td>
        <?php
        echo '</tr>';
    }
}

// Close the result set
mysqli_free_result($result);

// Close database connection
mysqli_close($conn);
?>