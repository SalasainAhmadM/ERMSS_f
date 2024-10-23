<?php
// Include your database connection code here
require_once('../db.connection/connection.php');


// Fetch upcoming and ongoing events from the database
$sql = "SELECT *, IF(event_cancel IS NULL OR event_cancel = '', '', event_cancel) AS event_status FROM pendingevents WHERE NOW() < CONCAT(date_end, ' ', time_end) ORDER BY date_created DESC";
$result = mysqli_query($conn, $sql);

// Get the role of the logged-in user
$userRole = $_SESSION['Role'];

// Loop through each event and generate a table row
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
            <a href="view_eventpending.php?event_id=<?php echo $row['event_id']; ?>">
                <button class="btn_view"><i class="fa-solid fa-eye"></i></button>
            </a>
        </td>
        <?php
        if ($userRole === 'Admin') {
            ?>
            <td data-label="Edit" class="pad">
                <a href="editEventpending.php?event_id=<?php echo $eventId; ?>">
                    <button class="btn_edit"><i class="fa fa-pencil"></i></button>
                </a>
            </td>
            <td data-label="Delete" class="pad">
                <button class="btn_delete" onclick="confirmDeleteEvent('<?php echo $eventId; ?>')"><i
                        class="fa fa-trash"></i></button>
            </td>
            <?php
        } elseif ($userRole === 'superadmin') {
            ?>
            <td data-label="Approve" class="pad">
                <button class="btn_approve" onclick="confirmApproveEvent('<?php echo $eventId; ?>')"><i
                        class="fa-solid fa-circle-check"></i></button>
            </td>
            <td data-label="Delete" class="pad">
                <button class="btn_delete" onclick="confirmDeleteEvent('<?php echo $eventId; ?>')"><i
                        class="fa fa-trash"></i></button>
            </td>
            <?php
        }

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
                window.location.href = `deleteEventpending.php?event_id=${eventId}`;
            }
        });
    }

    function confirmApproveEvent(eventId) {
        Swal.fire({
            title: 'Approve Event?',
            text: 'Are you sure you want to approve this event?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'No, cancel!',
            padding: '3rem',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `approveEvents.php?event_id=${eventId}`;
            }
        });
    }
</script>