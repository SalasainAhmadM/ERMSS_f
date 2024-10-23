<?php
require_once('../db.connection/connection.php');

if (isset($_SESSION['UserID'])) {
    $UserID = $_SESSION['UserID'];

    $sql = "SELECT Events.*, EventParticipants.UserID
            FROM Events
            INNER JOIN EventParticipants ON Events.event_id = EventParticipants.event_id
            WHERE EventParticipants.UserID = ? AND (Events.event_cancel IS NULL OR Events.event_cancel = '')
            ORDER BY Events.date_created DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();

    $eventsJoined = mysqli_num_rows($result);

    if (!isset($_SESSION['eventsJoined'])) {
        $_SESSION['eventsJoined'] = $eventsJoined;
    }

    if ($_SESSION['eventsJoined'] < $eventsJoined) {
        // Event was added
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js'></script>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'An event has been added!',
                icon: 'success',
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
            });
        </script>";
    } elseif ($_SESSION['eventsJoined'] > $eventsJoined) {
        // Event was cancelled
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js'></script>
        <script>
            Swal.fire({
                title: 'Warning!',
                text: 'An event has been cancelled!',
                icon: 'warning',
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
            });
        </script>";
    }

    $_SESSION['eventsJoined'] = $eventsJoined;

    echo "<h2>You have joined $eventsJoined event(s)</h2>";

    while ($row = mysqli_fetch_assoc($result)) {
        $eventTitle = htmlspecialchars($row['event_title']);
        $eventLocation = htmlspecialchars($row['location']);
        $eventDateStart = $row['date_start'];
        $eventDateEnd = $row['date_end'];
        $eventTimeStart = $row['time_start'];
        $eventTimeEnd = $row['time_end'];
        $eventMode = htmlspecialchars($row['event_mode']);
        $eventType = htmlspecialchars($row['event_type']);
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
        } elseif ($currentDateTime > $eventEndDateTime) {
            $eventStatus = 'ended';
        }

        if ($eventStatus === 'ongoing' || $eventStatus === 'upcoming') {
            echo '<tr data-start-date="' . $row['date_start'] . '" data-end-date="' . $row['date_end'] . '">';
            ?>
            <td data-label="Event Title"><?php echo $eventTitle; ?></td>
            <td data-label="Event Type"><?php echo $eventType; ?></td>
            <td data-label="Event Mode"><?php echo $eventMode; ?></td>
            <td data-label="Event Location"><?php echo $eventLocation; ?></td>
            <td data-label="Event Date">
                <?php echo date('F j, Y', strtotime($eventDateStart)) . ' - ' . date('F j, Y', strtotime($eventDateEnd)); ?>
            </td>
            <td data-label="Event Time">
                <?php echo date('h:ia', strtotime($eventTimeStart)) . ' - ' . date('h:ia', strtotime($eventTimeEnd)); ?>
            </td>
            <td data-label="Status"><?php echo $eventStatus; ?></td>
            <td data-label="Attendance" class="pad">
                <a href="myEvent.php?event_id=<?php echo $eventId; ?>"><button class="btn_edit"><i
                            class="fa-solid fa-eye"></i></button></a>
            </td>
            <?php
            echo '</tr>';
        }
    }

    mysqli_free_result($result);

    // Close database connection
    mysqli_close($conn);
} else {
    // Redirect to login page if UserID is not set in the session
    header("Location: login.php");
    exit();
}

?>