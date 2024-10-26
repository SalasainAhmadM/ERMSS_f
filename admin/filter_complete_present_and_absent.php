<?php
require_once('../db.connection/connection.php');

$eventTitle = isset($_GET['eventTitle']) ? $_GET['eventTitle'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if (empty($eventTitle) || empty($status)) {
    echo "Invalid request.";
    exit;
}

// Fetch all event dates for the specified event
$sqlDates = "SELECT date_start, date_end FROM events WHERE event_title = ?";
$stmtDates = $conn->prepare($sqlDates);
$stmtDates->bind_param("s", $eventTitle);
$stmtDates->execute();
$resultDates = $stmtDates->get_result();

if ($resultDates->num_rows == 0) {
    echo "Event not found.";
    exit;
}

$rowDates = $resultDates->fetch_assoc();
$startDate = $rowDates['date_start'];
$endDate = $rowDates['date_end'];

// Format the dates as "Oct 20, 2023"
$formattedStartDate = date('M d, Y', strtotime($startDate));
$formattedEndDate = date('M d, Y', strtotime($endDate));

// Fetch participants who have either perfect attendance or complete absences for all event dates
$sqlParticipants = "
    SELECT u.FirstName, u.MI, u.LastName, u.Affiliation, u.Position, u.Email, u.ContactNo, ep.participant_id, ep.event_id
    FROM eventParticipants ep
    INNER JOIN user u ON ep.UserID = u.UserID
    LEFT JOIN attendance a ON ep.participant_id = a.participant_id
                           AND ep.event_id = a.event_id
                           AND a.attendance_date BETWEEN ? AND ?
    WHERE ep.event_id = (SELECT event_id FROM events WHERE event_title = ?)
    GROUP BY u.UserID
    HAVING SUM(CASE WHEN a.status = ? THEN 1 ELSE 0 END) = DATEDIFF(?, ?) + 1
";

$stmtParticipants = $conn->prepare($sqlParticipants);
$stmtParticipants->bind_param("ssssss", $startDate, $endDate, $eventTitle, $status, $endDate, $startDate);
$stmtParticipants->execute();
$resultParticipants = $stmtParticipants->get_result();

if ($resultParticipants->num_rows > 0) {
    echo "<ul id='participant_list'>";
    while ($row = $resultParticipants->fetch_assoc()) {
        $firstName = htmlspecialchars($row['FirstName']);
        $MI = htmlspecialchars($row['MI']);
        $lastName = htmlspecialchars($row['LastName']);
        $fullName = $firstName . ' ' . $MI . ' ' . $lastName;
        $affiliation = htmlspecialchars($row['Affiliation']);
        $position = htmlspecialchars($row['Position']);
        $email = htmlspecialchars($row['Email']);
        $contactNo = htmlspecialchars($row['ContactNo']);
        $statusText = $status === 'present' ? 'For Evaluation' : ($status === 'absent' ? "Didn't Attend Event" : '');

        $evaluateButton = $status === 'present' ?
            "<button type='button' onclick=\"fetchAttendance('{$row['participant_id']}', '{$row['event_id']}', '{$fullName}', '{$eventTitle}')\" class='attendance-btn'>
        <i class='fa-solid fa-clipboard-check'></i>
    </button>"
            : ($status === 'absent' ? "<span>Not Approved</span>" : '');

        echo "<li class='participant_item'>
                <div class='item'>
                    <div class='name'><span>$formattedStartDate - $formattedEndDate</span></div>
                    <div class='name'><span>$fullName</span></div>
                    <div class='department'><span>$affiliation</span></div>
                    <div class='department'><span>$position</span></div>
                    <div class='info'><span>$email</span></div>
                    <div class='phone'><span>$contactNo</span></div>
                    <div class='status'><span>$statusText</span></div>
                    <div class='status'>$evaluateButton</div>
                </div>
              </li>";
    }
    echo "</ul>";
} else {
    $message = $status === 'present'
        ? "No participant has perfect attendance!"
        : "No participant has complete absences!";

    echo "<div class='no-participants-container'>
            <p class='no-participants-message'><i class='fas fa-exclamation-circle'></i> $message</p>
          </div>";
}
?>