<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

// Fetch filter selection if provided
$sponsorFilter = isset($_POST['sponsorEventId']) && $_POST['sponsorEventId'] != "All Sponsors"
    ? $_POST['sponsorEventId']
    : null;

// Prepare SQL query based on the selected sponsor
if ($sponsorFilter) {
    $sql = "SELECT E.* FROM Events E 
                JOIN sponsor S ON E.event_id = S.event_id 
                WHERE NOW() > CONCAT(E.date_end, ' ', E.time_end) 
                AND S.sponsor_Name = ? 
                ORDER BY E.date_created DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sponsorFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query if no sponsor filter is applied
    $sql = "SELECT * FROM Events WHERE NOW() > CONCAT(date_end, ' ', time_end) ORDER BY date_created DESC";
    $result = mysqli_query($conn, $sql);
}

// Loop through each event and generate a table row
while ($row = mysqli_fetch_assoc($result)) {
    if (empty($row['event_cancel'])) {
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
        $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

        $eventStatus = ($currentDateTime > $eventEndDateTime) ? 'ended' : '';

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
                <a href="view_eventHistory.php?event_id=<?php echo $row['event_id']; ?>"><button class="btn_view"><i
                            class="fa-solid fa-eye"></i></button></a>
                <!-- <button class="btn_delete" onclick="confirmDeleteEvent(<?php echo $eventId; ?>)">
                    <i class="fa-solid fa-trash"></i>
                </button> -->
            </td>

            <?php
            echo '</tr>';
        }
    }
}

// Close the result set
mysqli_free_result($result);
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