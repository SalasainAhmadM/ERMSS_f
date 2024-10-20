<?php
session_start();
header('Content-Type: application/json');
require_once('../db.connection/connection.php');

$response = [];

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

    if ($Password !== $ConfirmPassword) {
        $response = ['status' => 'error', 'message' => 'Passwords do not match!'];
        echo json_encode($response);
        exit();
    }

    // Check if the email has the required domain
    if (!endsWith($Email, "@gmail.com")) {
        $response = ['status' => 'error', 'message' => 'Email must have the domain @gmail.com'];
        echo json_encode($response);
        exit();
    }

    $hashedPassword = password_hash($Password, PASSWORD_BCRYPT); 


    $checkEmailQuery = "SELECT COUNT(*) as count FROM pendinguser WHERE Email = '$Email'";
    $checkResult = $conn->query($checkEmailQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        $emailCount = $row['count'];

        if ($emailCount > 0) {
            $response = ['status' => 'error', 'message' => 'Email is already registered!'];
        } else {
            $sql = "INSERT INTO pendinguser (LastName, FirstName, MI, Gender, Email, ContactNo, Address, Affiliation, Position, Password, Role)
                    VALUES ('$LastName', '$FirstName', '$MI', '$Gender', '$Email', '$ContactNo', '$Address', '$Affiliation', '$Position', '$hashedPassword', 'User')";

            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Account successfully created. Wait for validation.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Database error: ' . $conn->error];
            }
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Error checking email: ' . $conn->error];
    }

    $checkResult->close();
    $conn->close();
    
    echo json_encode($response);
}

function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}
?>
