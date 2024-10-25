<?php
// Include your database connection code here
require_once('../db.connection/connection.php');

// Check if UserID is set in the session
if (isset($_SESSION['UserID'])) {
    $UserID = $_SESSION['UserID'];

    // Fetch events that the user has joined from the database, including canceled/deleted events
    $sql = "SELECT Events.*, EventParticipants.UserID
            FROM Events
            INNER JOIN EventParticipants ON Events.event_id = EventParticipants.event_id
            WHERE EventParticipants.UserID = ?
            ORDER BY Events.date_created DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();

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

        // Check if the event is canceled or deleted
        $isCanceled = !empty($row['event_cancel']);  // Check if event_cancel has a value
        $isDeleted = !empty($row['deleted_at']);  // Check if deleted_at has a value (assumes you have a deleted_at field)

        // Get current date and time in the event's timezone
        $eventTimeZone = new DateTimeZone('Asia/Manila');
        $currentDateTime = new DateTime('now', $eventTimeZone);
        $eventStartDateTime = new DateTime($row['date_start'] . ' ' . $row['time_start'], $eventTimeZone);
        $eventEndDateTime = new DateTime($row['date_end'] . ' ' . $row['time_end'], $eventTimeZone);

        // Check if the event is ongoing, upcoming, or ended (including canceled/deleted)
        if ($isCanceled) {
            $eventStatus = 'canceled';
        } elseif ($isDeleted) {
            $eventStatus = 'deleted';
        } elseif ($currentDateTime < $eventStartDateTime) {
            $eventStatus = 'upcoming';
        } elseif ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
            $eventStatus = 'ongoing';
        } else {
            $eventStatus = 'ended';
        }

        // Display all events
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
                <p><i class='bx bx-calendar'></i> <span><?php echo "$eventDateStart - $eventDateEnd"; ?></span></p>
                <p><i class='bx bxs-timer'></i> <span><?php echo $eventStatus; ?></span></p>
                <p><i class="fas fa-clock"></i> <span><?php echo "$eventTimeStart - $eventTimeEnd"; ?></span></p>
            </div>

            <div class="flex-btn">
                <?php if ($isCanceled): ?>
                    <span class="alert alert-warning">Event Canceled</span>
                <?php elseif ($isDeleted): ?>
                    <span class="alert alert-danger">Event Deleted</span>
                <?php else: ?>
                    <a href="myEvent.php?event_id=<?php echo $eventId; ?>" class="btn">view event</a>
                <?php endif; ?>
            </div>
        </div>
<?php
    }

    // Close the result set
    mysqli_free_result($result);

    // Close database connection
    mysqli_close($conn);
} else {
    // Redirect to login page or handle the case where UserID is not set in the session
    header("Location: login.php");
    exit();
}
?>
