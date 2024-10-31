<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

    // Fetch ended events from the database
    $sql = "SELECT * FROM Events WHERE NOW() > CONCAT(date_end, ' ', time_end) ORDER BY date_created DESC";
    $result = mysqli_query($conn, $sql);

    // Loop through each event and generate a box
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

        // Get current date and time in the event's timezone
        $eventTimeZone = new DateTimeZone('Asia/Manila');
        $currentDateTime = new DateTime('now', $eventTimeZone);
        $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
        $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

        // Check if the event is ended
        $eventStatus = '';

        if ($currentDateTime > $eventEndDateTime) {
            $eventStatus = 'ended';
        }

        // Only display ended events
        if ($eventStatus === 'ended') {
?>
            <div class="box" data-start-date="<?php echo $eventStartDateTime->format('Y-m-d H:i:s'); ?>" data-end-date="<?php echo $eventEndDateTime->format('Y-m-d H:i:s'); ?>">
                <div class="company">
                    <img src="img/wesmaarrdec-removebg-preview.png" alt="">
                    <div>
                        <h3><?php echo $eventTitle; ?></h3>
                        <span><?php echo $eventMode; ?></span>
                    </div>
                </div>

                <h3 class="event-title"><?php echo $eventType; ?></h3>

                <p class="location"><i class="fas fa-map-marker-alt"></i> <span><?php echo $eventLocation; ?></span></p>
                <div class="tags">
                    <p><i class='bx bx-calendar' ></i> <span><?php echo "$eventDateStart - $eventDateEnd"; ?></span></p>
                    <p><i class='bx bxs-timer'></i> <span><?php echo $eventStatus; ?></span></p>
                    <p><i class="fas fa-clock"></i> <span><?php echo "$eventTimeStart - $eventTimeEnd"; ?></span></p>
                </div>

                <div class="flex-btn">
                    <a href="view_eventHistory.php?event_id=<?php echo $row['event_id']; ?>" class="btn">view event</a>

                    <button class="btn_delete" onclick="confirmDeleteEvent('<?php echo $eventId; ?>')">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>

            </div>
<?php
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
                window.location.href = `deleteEventHistory.php?event_id=${eventId}`;
            }
        });
    }
</script>