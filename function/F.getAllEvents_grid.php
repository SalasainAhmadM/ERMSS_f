<?php
// Include database connection
require_once('../db.connection/connection.php');

// Get selected year, month, and sponsor from form submission
$sponsorFilter = isset($_POST['sponsorEventId']) && $_POST['sponsorEventId'] !== 'All Sponsors' ? $_POST['sponsorEventId'] : null;
$yearFilter = isset($_POST['selectedYear']) && $_POST['selectedYear'] !== 'All Years' ? $_POST['selectedYear'] : null;
$monthFilter = isset($_POST['selectedMonth']) && $_POST['selectedMonth'] !== 'All Months' ? $_POST['selectedMonth'] : null;

// Construct SQL query with filters
$sql = "SELECT Events.*, sponsor.sponsor_Name 
        FROM Events 
        LEFT JOIN sponsor ON Events.event_id = sponsor.event_id 
        WHERE (event_cancel IS NULL OR event_cancel = '')";
// Apply sponsor filter if selected
if ($sponsorFilter) {
    $sql .= " AND sponsor.sponsor_Name = '" . mysqli_real_escape_string($conn, $sponsorFilter) . "'";
}
// Apply year filter
if ($yearFilter) {
    $sql .= " AND YEAR(date_start) = '" . mysqli_real_escape_string($conn, $yearFilter) . "'";
}

// Apply month filter
if ($monthFilter) {
    $sql .= " AND MONTHNAME(date_start) = '" . mysqli_real_escape_string($conn, $monthFilter) . "'";
}

// Sort results by creation date
$sql .= " ORDER BY date_created DESC";

// Execute the query and display events
$result = mysqli_query($conn, $sql);

// Display events
while ($row = mysqli_fetch_assoc($result)) {
    // Extract event details
    $eventTitle = htmlspecialchars($row['event_title']);
    $eventLocation = htmlspecialchars($row['location']);
    $eventDateStart = date('F j, Y', strtotime($row['date_start']));
    $eventDateEnd = date('F j, Y', strtotime($row['date_end']));
    $eventTimeStart = date('h:ia', strtotime($row['time_start']));
    $eventTimeEnd = date('h:ia', strtotime($row['time_end']));
    $eventMode = htmlspecialchars($row['event_mode']);
    $eventType = htmlspecialchars($row['event_type']);
    $eventId = htmlspecialchars($row['event_id']);

    $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], new DateTimeZone('Asia/Manila'));
    $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], new DateTimeZone('Asia/Manila'));

    // Determine event status
    $eventStatus = '';
    if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
        $eventStatus = 'ongoing';
    } elseif ($currentDateTime < $eventStartDateTime) {
        $eventStatus = 'upcoming';
    }

    // Generate HTML for each event
    ?>
    <div class="box">
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
            <p><i class='bx bx-calendar'></i> <span><?php echo "$eventDateStart - $eventDateEnd"; ?></span></p>
            <p><i class='bx bxs-timer'></i> <span><?php echo $eventStatus; ?></span></p>
            <p><i class="fas fa-clock"></i> <span><?php echo "$eventTimeStart - $eventTimeEnd"; ?></span></p>
        </div>
        <div class="flex-btn">
            <a href="view_eventHistory.php?event_id=<?php echo $eventId; ?>" class="btn">View Event</a>
        </div>
    </div>
    <?php
}

// Free result and close connection
mysqli_free_result($result);
mysqli_close($conn);
?>