<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

    // Fetch cancelled events from the database
    $sql = "SELECT * FROM Events WHERE event_cancel = 'Cancelled' ORDER BY date_created DESC";
    $result = mysqli_query($conn, $sql);

    // Loop through each cancelled event and generate a table row
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

        echo '<tr data-start-date="' . $row['date_start'] . '" data-end-date="' . $row['date_end'] . '">';
        ?>
        <td data-label="Event Title"><?php echo $eventTitle; ?></td>
        <td data-label="Event Type"><?php echo $eventType; ?></td>
        <td data-label="Event Mode"><?php echo $eventMode; ?></td>
        <td data-label="Event Location"><?php echo $eventLocation; ?></td>
        <td data-label="Event Date"><?php echo "$eventDateStart - $eventDateEnd"; ?></td>
        <td data-label="Event Time"><?php echo "$eventTimeStart - $eventTimeEnd"; ?></td>
        <td data-label="Status">Cancelled</td>
        <td data-label="View Event" class="pad">
            <a href="view_cancel.php?event_id=<?php echo $row['event_id']; ?>"><button class="btn_edit"><i class="fa-solid fa-eye"></i></i></button></a>
        </td>
        <?php
        echo '</tr>';
    }

    // Close the result set
    mysqli_free_result($result);

    // Close database connection
    mysqli_close($conn);
?>
