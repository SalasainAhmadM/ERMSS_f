<?php
require_once('../db.connection/connection.php');

if (isset($_GET['selectedDate']) && isset($_GET['eventTitle']) && isset($_GET['status'])) {
    $selectedDate = $_GET['selectedDate'];
    $eventTitle = $_GET['eventTitle'];
    $status = $_GET['status'];

    $sql = "SELECT user.FirstName, user.MI, user.LastName, user.Affiliation, 
                   user.Position, user.Email, user.ContactNo, 
                   eventParticipants.participant_id, eventParticipants.event_id,
                   attendance.status
            FROM eventParticipants
            INNER JOIN user ON eventParticipants.UserID = user.UserID
            LEFT JOIN attendance ON eventParticipants.participant_id = attendance.participant_id 
                                  AND eventParticipants.event_id = attendance.event_id 
                                  AND attendance.attendance_date = ?
            WHERE eventParticipants.event_id = 
                  (SELECT event_id FROM Events WHERE event_title = ?)
              AND attendance.status = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $selectedDate, $eventTitle, $status);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul id='participant_list'>";
        while ($row = $result->fetch_assoc()) {
            $firstName = htmlspecialchars($row['FirstName']);
            $MI = htmlspecialchars($row['MI']);
            $lastName = htmlspecialchars($row['LastName']);
            $fullName = $firstName . ' ' . $MI . ' ' . $lastName;
            $affiliation = htmlspecialchars($row['Affiliation']);
            $position = htmlspecialchars($row['Position']);
            $email = htmlspecialchars($row['Email']);
            $contactNo = htmlspecialchars($row['ContactNo']);
            $statusText = ucfirst(htmlspecialchars($row['status'])) ?: 'Not Marked';

            echo "
            <li class='participant_item'>
                <div class='item'>
                    <div class='name'>
                        <span>" . date('M j, Y', strtotime($selectedDate)) . "</span>
                    </div>
                    <div class='name'><span>$fullName</span></div>
                    <div class='department'><span>$affiliation</span></div>
                    <div class='department'><span>$position</span></div>
                    <div class='info'><span>$email</span></div>
                    <div class='phone'><span>$contactNo</span></div>
                    <div class='status'><span>$statusText</span></div>
                    <div class='status attendance-btn-container'>
                        <button type='button' onclick='resetAttendance2(\"{$row['participant_id']}\", \"{$row['event_id']}\", \"$selectedDate\")' class='attendance-btn'>
                            <i class='fa-solid fa-file-pen'></i>
                        </button>
                    </div>
                </div>
            </li>";
        }
        echo "</ul>";
    } else {
        echo "<div class='no-participants-container'>
                <p class='no-participants-message'><i class='fas fa-exclamation-circle'></i> No $status participants found for the specified event and date.</p>
              </div>";
    }
} else {
    echo "<div class='no-participants-container'>
            <p class='no-participants-message'><i class='fas fa-exclamation-circle'></i> No $status participants found for the specified event and date!</p>
          </div>";
}
?>