<?php
require('fpdf.php'); // Include the FPDF library

if (isset($_GET['download'])) {
    // Create instance of FPDF class
    $pdf = new FPDF();
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->AddPage();

    // Title
    $pdf->SetFont("Arial", 'B', 16);
    $pdf->Cell(0, 10, 'Event Details', 0, 1, 'C');

    // Adding Event Information
    $pdf->SetFont("Arial", 'B', 12);
    $pdf->Cell(0, 10, 'Event Information', 0, 1);
    $pdf->SetFont("Arial", '', 12);

    $event_info = [
        "Event Title:",
        "Date:",
        "Event Mode:",
        "Time:",
        "Event Type:",
        "Location:",
    ];

    foreach ($event_info as $item) {
        $pdf->Cell(0, 10, $item, 1, 1); // Only one column with item name
    }

    // Adding Participants
    $pdf->SetFont("Arial", 'B', 12);
    $pdf->Cell(0, 10, 'Participants', 0, 1);
    $pdf->SetFont("Arial", '', 12);

    // Table header for participants
    $headers = ["#", "Participants", "Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"];
    foreach ($headers as $header) {
        $pdf->Cell(22, 10, $header, 1);
    }
    $pdf->Ln();

    // Sample participants data with meaning of symbols
    $participants_data = [
        [1, "Name1", "/", "/", "/", "/", "/", "/", "/"], // Present
        [2, "Name2", "X", "X", "X", "X", "X", "X", "/"],     // Absent
        [3, "Name3", "/", "/", "/", "/", "/", "X", "X"],     // Present and Absent
        [4, "Name4", "/", "/", "/", "/", "/", "X", "X"],     // Present and Absent
    ];

    foreach ($participants_data as $participant) {
        foreach ($participant as $data) {
            $pdf->Cell(22, 10, strval($data), 1);
        }
        $pdf->Ln();
    }

    // Adding a note about attendance symbols
    $pdf->SetFont("Arial", 'I', 10);
    $pdf->Cell(0, 10, '* X = Absent, / = Present', 0, 1);

    // Adding Sponsors
    $pdf->SetFont("Arial", 'B', 12);
    $pdf->Cell(0, 10, 'Sponsors', 0, 1);
    $pdf->SetFont("Arial", '', 12);

    // Table header for sponsors
    $pdf->Cell(22, 10, "#", 1);
    $pdf->Cell(0, 10, "Sponsors", 1, 1);

    // Sample sponsors data
    $sponsors_data = [
        [1, "Name1"],
        [2, "Name2"],
        [3, "Name3"],
    ];

    foreach ($sponsors_data as $sponsor) {
        $pdf->Cell(22, 10, strval($sponsor[0]), 1);
        $pdf->Cell(0, 10, $sponsor[1], 1, 1);
    }

    // Output the PDF to a string
    $pdf_output = $pdf->Output('event_details.pdf', 'S');

    // Prepare to download the PDF
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="event_details.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($pdf_output));

    // Clear output buffer and flush
    ob_clean();
    flush();

    // Output the PDF file
    echo $pdf_output;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Event Details PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f9f9f9;
        }
        .download-button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .download-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="?download=true" class="download-button">Download Event Details PDF</a>
</body>
</html>
