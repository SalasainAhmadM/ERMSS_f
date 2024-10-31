<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

// Fetch upcoming events from the database based on the current date and time
$sql = "SELECT * FROM Events WHERE (date_start > CURDATE() OR (date_start = CURDATE() AND time_start > CURTIME())) AND (event_cancel IS NULL OR event_cancel = '') ORDER BY date_created DESC";
$result = mysqli_query($conn, $sql);

// Check if there are no results
if (mysqli_num_rows($result) === 0) {
    echo "<tr><td colspan='8' style='text-align: center;'>No Joined Events!</td></tr>";
} else {
    // Loop through each upcoming event and generate a table row
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

        // Display upcoming events
        echo '<tr data-start-date="' . $row['date_start'] . '" data-end-date="' . $row['date_end'] . '">';
        ?>
        <td data-label="Event Title"><?php echo $eventTitle; ?></td>
        <td data-label="Event Type"><?php echo $eventType; ?></td>
        <td data-label="Event Mode"><?php echo $eventMode; ?></td>
        <td data-label="Event Location"><?php echo $eventLocation; ?></td>
        <td data-label="Event Date"><?php echo "$eventDateStart - $eventDateEnd"; ?></td>
        <td data-label="Event Time"><?php echo "$eventTimeStart - $eventTimeEnd"; ?></td>
        <td data-label="Status">Upcoming</td>
        <td data-label="View Event" class="pad">
            <a href="view_eventHistory.php?event_id=<?php echo $eventId; ?>"><button class="btn_view"><i
                        class="fa-solid fa-eye"></i></button></a>
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