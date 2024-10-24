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
              AND attendance.status = ?";  // Filter by status (present or absent)

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $selectedDate, $eventTitle, $status);  // Bind the date, event title, and status
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
            $statusText = $status === 'present' ? 'Present' : 'Absent';  // Status label

            echo "
                <form action='' method='POST'>
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
                            <div class='status'>
                                <span>$statusText</span>
                            </div>
                            <div class='status'>
                                <input type='hidden' name='participant_id' value='{$row['participant_id']}'>
                                <input type='hidden' name='event_id' value='{$row['event_id']}'>
                                <button type='button' onclick='triggerAttendance(\"{$row['participant_id']}\")' class='attendance-btn'>
                                    <i class='fas fa-user-check'></i> Attendance
                                </button>
                            </div>
                        </div>
                    </li>
                </form>";
        }
        echo "</ul>";
    } else {
        echo "<div class='no-participants-container'>
                <p class='no-participants-message'><i class='fas fa-exclamation-circle'></i> No '$status' participants found for the specified event and date.</p>
              </div>";
    }
}
?>