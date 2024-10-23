<?php
require_once('../db.connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if UserID is set in the session
    if (isset($_SESSION['UserID'])) {
        $UserID = $_SESSION['UserID'];

        // Query to fetch cancelled events linked to the current user
        $sql = "SELECT e.event_id, e.event_title, e.event_type, e.event_mode, e.location, e.date_start, e.date_end, 
                       e.time_start, e.time_end, c.description AS cancel_reason
                FROM events e
                JOIN cancel_reason c ON e.event_id = c.event_id
                WHERE c.UserID = ? AND e.event_cancel IS NOT NULL AND e.event_cancel <> ''";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $UserID); // Bind UserID
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display each row of cancelled event data in the table
                echo "<tr data-start-date='" . $row['date_start'] . "' data-end-date='" . $row['date_end'] . "'>";
                echo "<td data-label='Event Title'>" . htmlspecialchars($row['event_title']) . "</td>";
                echo "<td data-label='Event Type'>" . htmlspecialchars($row['event_type']) . "</td>";
                echo "<td data-label='Event Mode'>" . htmlspecialchars($row['event_mode']) . "</td>";
                echo "<td data-label='Event Location'>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td data-label='Event Date'>" . date("F j, Y", strtotime($row['date_start'])) . " - " . date("F j, Y", strtotime($row['date_end'])) . "</td>";
                echo "<td data-label='Event Time'>" . date("h:i a", strtotime($row['time_start'])) . " - " . date("h:i a", strtotime($row['time_end'])) . "</td>";
                echo "<td data-label='Reason'>" . htmlspecialchars($row['cancel_reason']) . "</td>";
                echo "<td data-label='View Event' class='pad'>
                        <a href='view_cancel.php?event_id=" . $row['event_id'] . "'><button class='btn_edit'><i class='fa-solid fa-eye'></i></button></a>
                      </td>";
                echo "</tr>";
            }
        } else {
            // If no cancelled events are found for the user
            echo "<tr><td colspan='8'>No cancelled events found</td></tr>";
        }

        $stmt->close();
    } else {
        // Redirect to login page if UserID is not set in the session
        header("Location: login.php");
        exit();
    }
}
?>