<?php
require_once('../db.connection/connection.php');

if (isset($_GET['year'])) {
    $selectedYear = $_GET['year'];

    $monthlyQuery = "SELECT MONTH(date_start) AS month, COUNT(*) AS total_events 
                    FROM Events 
                    WHERE YEAR(date_start) = $selectedYear 
                    GROUP BY MONTH(date_start)";
    $monthlyResult = mysqli_query($conn, $monthlyQuery);
    $monthlyData = mysqli_fetch_all($monthlyResult, MYSQLI_ASSOC);

    // Fill in missing months with zero events
    $filledData = array_fill(0, 12, ['month' => 0, 'total_events' => 0]);
    foreach ($monthlyData as $data) {
        $filledData[$data['month'] - 1] = $data;
    }

    echo json_encode(['events' => $filledData]);
} else {
    echo json_encode(['error' => 'Year parameter not provided']);
}
?>
