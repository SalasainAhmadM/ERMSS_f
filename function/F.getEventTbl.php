<?php
require_once('../db.connection/connection.php');

// Check if a sponsor filter is set
$selectedSponsor = isset($_POST['sponsorEventId']) ? $_POST['sponsorEventId'] : 'All Sponsors';

// Adjust the SQL query based on the selected sponsor
$sql = "SELECT e.*, IF(e.event_cancel IS NULL OR e.event_cancel = '', '', e.event_cancel) AS event_status 
        FROM Events e 
        LEFT JOIN sponsor s ON e.event_id = s.event_id";

// Apply sponsor filter if it's not 'All Sponsors'
if ($selectedSponsor !== 'All Sponsors') {
    $sql .= " WHERE s.sponsor_Name = ?";
}

$sql .= " AND NOW() < CONCAT(e.date_end, ' ', e.time_end) ORDER BY e.date_created DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($selectedSponsor !== 'All Sponsors') {
    $stmt->bind_param("s", $selectedSponsor);
}
$stmt->execute();
$result = $stmt->get_result();

while ($row = mysqli_fetch_assoc($result)) {
    $eventTitle = $row['event_title'];
    $eventLocation = $row['location'];
    $eventDateStart = date('F j, Y', strtotime($row['date_start'])); // Format date as Month day, Year
    $eventDateEnd = date('F j, Y', strtotime($row['date_end'])); // Format date as Month day, Year
    $eventTimeStart = date('h:ia', strtotime($row['time_start'])); // Format time as Hour:Minute AM/PM
    $eventTimeEnd = date('h:ia', strtotime($row['time_end'])); // Format time as Hour:Minute AM/PM
    $eventMode = $row['event_mode'];
    $eventType = $row['event_type'];
    $eventId = $row['event_id'];
    $eventStatus = $row['event_status']; // Use the event_status column as event status

    // Get current date and time in the event's timezone
    $eventTimeZone = new DateTimeZone('Asia/Manila');
    $currentDateTime = new DateTime('now', $eventTimeZone);
    $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
    $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

    // Check if the event is ongoing or upcoming
    if ($eventStatus === '') {
        if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
            $eventStatus = 'ongoing';
        } elseif ($currentDateTime < $eventStartDateTime) {
            $eventStatus = 'upcoming';
        }
    }

    // Only display upcoming and ongoing events
    if ($eventStatus === 'upcoming' || $eventStatus === 'ongoing') {
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
            <a href="view_event.php?event_id=<?php echo $row['event_id']; ?>">
                <button class="btn_view"><i class="fa-solid fa-eye"></i></button>
            </a>
        </td>
        <td data-label="Edit" class="pad">
            <a href="editEvent.php?event_id=<?php echo $eventId; ?>">
                <button class="btn_edit"><i class="fa fa-pencil"></i></button>
            </a>
        </td>

        <!-- <?php if ($eventStatus === 'upcoming' || $eventStatus === 'ongoing'): ?>
            <td data-label="Delete" class="pad">
            <button class="btn_delete" onclick="confirmDeleteEvent_Warning('<?php echo $eventId; ?>')">
                <i class="fa fa-trash"></i>
            </button>
            </td>
        <?php endif; ?> -->

        <?php
        echo '</tr>';
    }
}

// Close the result set
mysqli_free_result($result);

// Close database connection
mysqli_close($conn);
?>
<script>
    function confirmDeleteEvent(eventId) {
        Swal.fire({
            title: 'Delete Event?',
            text: 'Are you sure you want to delete this event?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            padding: '3rem',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `deleteEvent.php?event_id=${eventId}`;
            }
        });
    }

    function confirmDeleteEvent_Warning(eventId) {
        Swal.fire({
            title: 'Event is Ongoing/Upcoming!',
            text: 'The event is already ongoing/upcoming! failed to delete.',
            icon: 'warning',
            padding: '3rem',
            customClass: {
                popup: 'larger-swal'
            }
        })
    }
</script>