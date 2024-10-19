<?php
require_once('../db.connection/connection.php');

$yearlyQuery = "SELECT YEAR(date_start) AS year, COUNT(*) AS total_events 
                FROM Events 
                GROUP BY YEAR(date_start)";
$yearlyResult = mysqli_query($conn, $yearlyQuery);
$yearlyData = mysqli_fetch_all($yearlyResult, MYSQLI_ASSOC);

echo json_encode(['events' => $yearlyData]);
?>
