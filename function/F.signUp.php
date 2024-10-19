<?php
session_start();
require_once('../db.connection/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $LastName = $_POST["LastName"];
    $FirstName = $_POST["FirstName"];
    $MI = $_POST["MI"];
    $Gender = $_POST["Gender"];
    $Email = $_POST["Email"];
    $ContactNo = !empty($_POST["ContactNo"]) ? $_POST["ContactNo"] : "N/A";
    $Address = !empty($_POST["Address"]) ? $_POST["Address"] : "N/A";
    $Affiliation = !empty($_POST["Affiliation"]) ? $_POST["Affiliation"] : "N/A";
    $Position = !empty($_POST["Position"]) ? $_POST["Position"] : "N/A";
    $Password = $_POST["Password"];
    $ConfirmPassword = $_POST["ConfirmPassword"];

    // You might want to perform additional validation and sanitation here

    // Check if passwords match
    if ($Password !== $ConfirmPassword) {
        echo "<script>alert('Error: Passwords do not match'); window.location.href = '../login.php';</script>";
        exit();
    }

    // Check if the email has the required domain
    if (!endsWith($Email, "@gmail.com")) {
        echo "<script>alert('Error: Email must have the domain @gmail.com'); window.location.href = '../login.php';</script>";
        exit();
    }

    // Hash the Password before storing in the database
    $hashedPassword = password_hash($Password, PASSWORD_BCRYPT); //for encrypting the password

    // Your SQL query to check if the email already exists in the Student table
    $checkEmailQuery = "SELECT COUNT(*) as count FROM pendinguser WHERE Email = '$Email'";
    $checkResult = $conn->query($checkEmailQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        $emailCount = $row['count'];

        if ($emailCount > 0) {
            echo "<script>alert('Error: Email is already registered'); window.location.href = '../login.php';</script>";
        } else {
            // Your SQL query to insert data into the PendingStudent table
            $sql = "INSERT INTO pendinguser (LastName, FirstName, MI, Gender, Email, ContactNo, Address, Affiliation, Position, Password, Role)
                    VALUES ('$LastName', '$FirstName', '$MI', '$Gender', '$Email', '$ContactNo', '$Address', '$Affiliation', '$Position', '$hashedPassword', 'User')"; // Updated line

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Account successfully created. Wait for validation.'); window.location.href = '../login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "'); window.location.href = '../login.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Error checking email: " . $conn->error . "'); window.location.href = '../login.php';</script>";
    }

    // Close the check result
    $checkResult->close();
    $conn->close();
}

// Helper function to check if a string ends with another string
function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}
?>
