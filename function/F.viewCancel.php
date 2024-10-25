<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

$sql = "SELECT * FROM Events WHERE event_cancel = 'Cancelled' ORDER BY date_created DESC";
$result = mysqli_query($conn, $sql);

// Check if any events are found
if (mysqli_num_rows($result) > 0) {
    // Loop through each cancelled event and generate a table row
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

        echo '<tr data-start-date="' . $row['date_start'] . '" data-end-date="' . $row['date_end'] . '">';
        ?>
        <td data-label="Event Title"><?php echo $eventTitle; ?></td>
        <td data-label="Event Type"><?php echo $eventType; ?></td>
        <td data-label="Event Mode"><?php echo $eventMode; ?></td>
        <td data-label="Event Location"><?php echo $eventLocation; ?></td>
        <td data-label="Event Date"><?php echo "$eventDateStart - $eventDateEnd"; ?></td>
        <td data-label="Event Time"><?php echo "$eventTimeStart - $eventTimeEnd"; ?></td>
        <td data-label="Status">Cancelled</td>
        <td data-label="ID"><?php echo $eventId; ?></td>
        <td data-label="View Event" class="pad">
            <a href="view_cancel.php?event_id=<?php echo $eventId; ?>">
                <button class="btn_view"><i class="fa-solid fa-eye"></i></button>
            </a>
        </td>
        <td data-label="Delete" class="pad">
            <button class="btn_delete" onclick="confirmDeleteEvent('<?php echo $eventId; ?>')">
                <i class="fa fa-trash"></i>
            </button>
        </td>
        <?php
        echo '</tr>';
    }
} else {
    // Display message if no records are found
    echo "<tr><td colspan='8' style='text-align: center;'>No Events Currently Cancelled!</td></tr>";
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
                window.location.href = `../function/F.deleteEvent.php?event_id=${eventId}`;
            }
        });
    }
</script>