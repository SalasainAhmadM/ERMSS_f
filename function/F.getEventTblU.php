<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

    // Fetch events from the database, excluding canceled events
    $sql = "SELECT * FROM Events WHERE event_cancel IS NULL OR event_cancel = '' ORDER BY date_created DESC";
    $result = mysqli_query($conn, $sql);

    // Loop through each event and generate a table row for ongoing and upcoming events
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

        // Check if the event is ongoing, upcoming, or ended
        $eventStatus = '';

        if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
            $eventStatus = 'ongoing';
        } elseif ($currentDateTime < $eventStartDateTime) {
            $eventStatus = 'upcoming';
        } elseif ($currentDateTime > $eventEndDateTime) {
            $eventStatus = 'ended';
        }

        // Only display ongoing and upcoming events
        if ($eventStatus === 'ongoing' || $eventStatus === 'upcoming') {
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
                <a href="view_event.php?event_id=<?php echo $row['event_id']; ?>"><button class="btn_edit"><i class="fa-solid fa-eye"></i></i></button></a>
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
