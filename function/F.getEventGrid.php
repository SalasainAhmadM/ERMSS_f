<?php
// Include your database connection
require_once('../db.connection/connection.php');

// Get selected year and month from form submission, default to 'All'
$selectedYear = $_POST['selectedYear'] ?? 'All Years';
$selectedMonth = $_POST['selectedMonth'] ?? 'All Months';
$sponsorName = $_POST['sponsorName'] ?? 'All Sponsors';

// Base SQL query for upcoming and ongoing events
$sql = "SELECT E.* FROM Events E
        LEFT JOIN sponsor S ON E.event_id = S.event_id
        WHERE NOW() < CONCAT(E.date_end, ' ', E.time_end)
        AND (E.event_cancel IS NULL OR E.event_cancel = '')";

// Filter by sponsor name if specified
if ($sponsorName !== 'All Sponsors') {
    $sql .= " AND S.sponsor_Name = ?";
}

// Filter by year if specified
if ($selectedYear !== 'All Years') {
    $sql .= " AND YEAR(E.date_start) = ?";
}

// Filter by month if specified
if ($selectedMonth !== 'All Months') {
    $sql .= " AND MONTHNAME(E.date_start) = ?";
}

// Order by creation date
$sql .= " ORDER BY E.date_created DESC";

// Prepare the statement with dynamic parameters
$stmt = $conn->prepare($sql);

// Bind parameters dynamically based on selected filters
$params = [];
if ($sponsorName !== 'All Sponsors')
    $params[] = $sponsorName;
if ($selectedYear !== 'All Years')
    $params[] = $selectedYear;
if ($selectedMonth !== 'All Months')
    $params[] = $selectedMonth;

// Only call bind_param if there are parameters
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Generate HTML for each event
while ($row = $result->fetch_assoc()) {
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

    $eventStatus = '';
    if ($currentDateTime >= $eventStartDateTime && $currentDateTime <= $eventEndDateTime) {
        $eventStatus = 'ongoing';
    } elseif ($currentDateTime < $eventStartDateTime) {
        $eventStatus = 'upcoming';
    }

    if ($eventStatus) {
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
                <a href="view_event.php?event_id=<?php echo $eventId; ?>" class="btn">View Event</a>
                <a href="editEventGrid.php?event_id=<?php echo $eventId; ?>" class="fa-solid fa-pen-to-square"></a>
            </div>
        </div>
        <?php
    }
}

// Free result and close the connection
$result->free();
$conn->close();
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
                window.location.href = `deleteEvent2.php?event_id=${eventId}`;
            }
        });
    }

</script>