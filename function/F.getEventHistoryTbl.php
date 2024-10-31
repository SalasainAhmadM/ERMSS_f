<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

    // Fetch ended events from the database
    $sql = "SELECT * FROM Events WHERE NOW() > CONCAT(date_end, ' ', time_end) ORDER BY date_created DESC";
    $result = mysqli_query($conn, $sql);

    // Loop through each event and generate a table row
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if event_cancel is empty
        if (empty($row['event_cancel'])) {
            $eventTitle = $row['event_title'];
            $eventLocation = $row['location'];
            $eventDateStart = date('F j, Y', strtotime($row['date_start'])); // Format date as Month day, Year
            $eventDateEnd = date('F j, Y', strtotime($row['date_end'])); // Format date as Month day, Year
            $eventTimeStart = date('h:ia', strtotime($row['time_start'])); // Format time as Hour:Minute AM/PM
            $eventTimeEnd = date('h:ia', strtotime($row['time_end'])); // Format time as Hour:Minute AM/PM
            $eventMode = $row['event_mode'];
            $eventType = $row['event_type'];
            $eventId = $row['event_id'];

            // Get current date and time in the event's timezone
            $eventTimeZone = new DateTimeZone('Asia/Manila');
            $currentDateTime = new DateTime('now', $eventTimeZone);
            $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

            // Check if the event has ended
            $eventStatus = '';

            if ($currentDateTime > $eventEndDateTime) {
                $eventStatus = 'ended';
            }

            // Only display ended events
            if ($eventStatus === 'ended') {
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
                    <a href="view_eventHistory.php?event_id=<?php echo $row['event_id']; ?>"><button class="btn_view"><i class="fa-solid fa-eye"></i></i></button></a>
                </td>
                <td data-label="Delete" class="pad">
                    <button class="btn_delete" onclick="confirmDeleteEvent('<?php echo $eventId; ?>')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
                <?php
                echo '</tr>';
            }
        }
    }

    // Close the result setzz
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
                window.location.href = `deleteEventHistory.php?event_id=${eventId}`;
            }
        });
    }
</script>