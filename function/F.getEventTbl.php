<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

    // Fetch upcoming and ongoing events from the database
    $sql = "SELECT *, IF(event_cancel IS NULL OR event_cancel = '', '', event_cancel) AS event_status FROM Events WHERE NOW() < CONCAT(date_end, ' ', time_end) ORDER BY date_created DESC";
    $result = mysqli_query($conn, $sql);

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
                <a href="view_event.php?event_id=<?php echo $row['event_id']; ?>"><button class="btn_view"><i class="fa-solid fa-eye"></i></button></a>
            </td>
            <td data-label="Edit" class="pad">
                <a href="editEvent.php?event_id=<?php echo $eventId; ?>"><button class="btn_edit"><i class="fa fa-pencil"></i></button></a>
            </td>
            <td data-label="Delete" class="pad">
                <a href="deleteEvent.php?event_id=<?php echo $eventId; ?>" onclick="return confirm('Are you sure you want to delete this event?');">
                    <button class="btn_delete"><i class="fa fa-trash"></i></button>
                </a>
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
