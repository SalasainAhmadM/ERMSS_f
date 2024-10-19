<?php

session_start();
require_once('../db.connection/connection.php');

function showAlert($message, $redirectPath = null) {
    echo "<script>alert('$message');";
    if ($redirectPath) {
        echo "window.location.href = '$redirectPath';";
    }
    echo "</script>";
}

function getUserData($conn, $UserID) {
    $sql = "SELECT * FROM user WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve UserID and event_id from the URL parameters (if they exist)
    $UserID = isset($_POST['UserID']) ? $_POST['UserID'] : null;
    $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;

    if ($UserID && $eventId) {
        // Open a new database connection
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user data
        $userData = getUserData($conn, $UserID);

        if ($userData) {
            // User data found, proceed with insertion
            $sqlInsert = "INSERT INTO EventParticipants (event_id, UserID) VALUES (?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ii", $eventId, $UserID);

            if ($stmtInsert->execute()) {
                showAlert("Successfully joined the event!", "view_event.php?event_id=$eventId");
            } else {
                showAlert("Error joining the event. Please try again.");
            }

            $stmtInsert->close();
        } else {
            showAlert("User not found.");
        }

        // Close the database connection
        $conn->close();
    } else {
        showAlert("Invalid UserID or event_id.");
    }
}
?>
