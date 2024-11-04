<?php
// Include your database connection
require_once('../db.connection/connection.php');

// Get filter values from JSON request
$data = json_decode(file_get_contents('php://input'), true);
$sponsor = $data['sponsor'];
$year = $data['year'];
$month = $data['month'];

$whereClauses = [];

// Apply sponsor filter if not "All"
if ($sponsor !== "All") {
    $whereClauses[] = "CONCAT(sponsor_Name) = '$sponsor'";
}

// Apply year filter if not "All"
if ($year !== "All") {
    $whereClauses[] = "YEAR(events.date_start) = '$year'";
}

// Apply month filter if not "All"
if ($month !== "All") {
    $monthNumber = date("m", strtotime($month));
    $whereClauses[] = "MONTH(events.date_start) = '$monthNumber'";
}

$whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
$sql = "SELECT events.*, CONCAT(sponsor_Name) AS sponsor_name
        FROM events 
        JOIN sponsor ON events.event_id = sponsor.event_id
        $whereSQL";

$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'event_id' => $row['event_id'],
        'event_title' => $row['event_title'],
        'event_type' => $row['event_type'],
        'event_mode' => $row['event_mode'],
        'location' => $row['location'],
        'date_display' => date('F j, Y', strtotime($row['date_start'])) . ' - ' . date('F j, Y', strtotime($row['date_end'])),
        'time_display' => date('h:ia', strtotime($row['time_start'])) . ' - ' . date('h:ia', strtotime($row['time_end'])),
        'status' => $row['status'] ?? 'upcoming'
    ];
}

echo json_encode($events);
$conn->close();
?>