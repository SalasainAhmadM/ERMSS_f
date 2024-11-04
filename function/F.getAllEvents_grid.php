<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

$sponsorFilter = isset($_POST['sponsorEventId']) && $_POST['sponsorEventId'] !== 'All Sponsors' ? $_POST['sponsorEventId'] : null;
$yearFilter = isset($_POST['selectedYear']) && $_POST['selectedYear'] !== '' ? $_POST['selectedYear'] : null;
$monthFilter = isset($_POST['selectedMonth']) && $_POST['selectedMonth'] !== '' ? $_POST['selectedMonth'] : null;

// Modify the SQL query to include sponsor, year, and month filtering
$sql = "SELECT Events.*, sponsor.sponsor_Name FROM Events 
        LEFT JOIN sponsor ON Events.event_id = sponsor.event_id 
        WHERE (event_cancel IS NULL OR event_cancel = '')";

if ($sponsorFilter) {
    $sql .= " AND sponsor.sponsor_Name = '" . mysqli_real_escape_string($conn, $sponsorFilter) . "'";
}
if ($yearFilter) {
    $sql .= " AND YEAR(date_start) = '" . mysqli_real_escape_string($conn, $yearFilter) . "'";
}
if ($monthFilter) {
    $sql .= " AND MONTHNAME(date_start) = '" . mysqli_real_escape_string($conn, $monthFilter) . "'";
}

$sql .= " ORDER BY date_created DESC";
$result = mysqli_query($conn, $sql);

// Loop through each event and generate a box
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

    $eventTimeZone = new DateTimeZone('Asia/Manila');
    $currentDateTime = new DateTime('now', $eventTimeZone);
    $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
    $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

    $eventStatus = '';
    if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
        $eventStatus = 'ongoing';
    } elseif ($currentDateTime < $eventStartDateTime) {
        $eventStatus = 'upcoming';
    } else {
        $eventStatus = 'ended';
    }

    ?>
    <div class="box" data-start-date="<?php echo $eventStartDateTime->format('Y-m-d H:i:s'); ?>"
        data-end-date="<?php echo $eventEndDateTime->format('Y-m-d H:i:s'); ?>">
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
            <a href="view_eventHistory.php?event_id=<?php echo $row['event_id']; ?>" class="btn">view event</a>
        </div>
    </div>
    <?php
}

mysqli_free_result($result);
mysqli_close($conn);
?>