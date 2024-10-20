<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetAlert2 Notification</title>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Ensures the toast appears in the top right corner */
        .swal2-toast {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
        }
    </style>
</head>
<body>

<?php
// Example PHP to trigger SweetAlert as a notification
$alertType = "success"; // You can change this to "error", "warning", or "info"
$alertMessage = "This is a SweetAlert2 notification!";
$alertTitle = "Success!";

echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js'></script>
    <script>
        Swal.fire({
            title: '$alertTitle',
            text: '$alertMessage',
            icon: '$alertType',
            toast: true,                  // Makes it a toast notification
            position: 'top-right',        // Sets the position to top-right
            showConfirmButton: false,      // Hides the confirm button
            timer: 3000,                  // Sets how long the toast will stay visible (in ms)
            timerProgressBar: true,       // Adds a progress bar to the toast
        });
    </script>
";
?>

</body>
</html>
