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

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['UserID'])) {
        $UserID = $_SESSION['UserID'];
        $userData = getUserData($conn, $UserID);

        if ($userData) {
            $LastName = $userData['LastName'];
            $FirstName = $userData['FirstName'];
            $MI = $userData['MI'];
            $Email = $userData['Email'];
            $Password = $userData['Password'];
            $ContactNo = $userData['ContactNo'];
            $Position = $userData['Position'];
            $Affiliation = $userData['Affiliation'];
            $Image = isset($userData['Image']) ? $userData['Image'] : null; // Initialize to null if not set
        } else {
            echo "No records found";
        }
    } else {
        echo "UserID not set in the session";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Form submission logic
    $UserID = $_POST["UserID"];
    $LastName = $_POST["LastName"];
    $FirstName = $_POST["FirstName"];
    $MI = $_POST["MI"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $NewPassword = $_POST["NewPassword"];
    $ConfirmNewPassword = $_POST["ConfirmNewPassword"];
    $ContactNo = $_POST["ContactNo"];
    $Affiliation = $_POST["Affiliation"];
    $Position = $_POST["Position"];
    $Image = isset($_FILES['Image']) ? $_FILES['Image'] : null; // Initialize to null if not set

    // Check if Passwords match
    if (!empty($NewPassword) && $NewPassword !== $ConfirmNewPassword) {
        showAlert("Error: New passwords do not match", "../user_side/accountSettings.php");
        exit();
    }

    // Check the current password before updating
    $checkPasswordSql = "SELECT Password FROM user WHERE UserID = ?";
    $stmtCheckPassword = $conn->prepare($checkPasswordSql);
    $stmtCheckPassword->bind_param("i", $UserID);
    $stmtCheckPassword->execute();
    $stmtCheckPassword->bind_result($hashedCurrentPassword);
    $stmtCheckPassword->fetch();
    $stmtCheckPassword->close();

    // Verify the current password
    if (!password_verify($Password, $hashedCurrentPassword)) {
        showAlert("Error: Incorrect current password", "../user_side/accountSettings.php");
        exit();
    }

    // Hash the new password if provided
    $hashedNewPassword = !empty($NewPassword) ? password_hash($NewPassword, PASSWORD_BCRYPT) : $hashedCurrentPassword;

    // Check if an image is provided
    if ($Image && $Image['error'] === UPLOAD_ERR_OK) {
        $fileType = pathinfo($Image['name'], PATHINFO_EXTENSION);

        // Check if the file type is allowed
        if ($fileType != 'jpg' && $fileType != 'jpeg' && $fileType != 'png') {
            showAlert("Only JPG, JPEG, or PNG files are allowed.", "../user_side/accountSettings.php");
            exit();
        }

        $uploadDir = '../assets/img/profilePhoto/';
        $fileName = basename($Image['name']);
        $imagePath = $uploadDir . $fileName;

        // Remove spaces and special characters from file name
        $fileName = preg_replace('/\s+/', '_', $fileName);

        if (move_uploaded_file($Image['tmp_name'], $imagePath)) {
            $Image = $fileName;
        } else {
            showAlert("Error uploading image", "../user_side/accountSettings.php");
            exit();
        }
    }

    // Update the user details in the database
    $sql = "UPDATE user SET LastName=?, FirstName=?, MI=?, Email=?, Password=?, ContactNo=?, Affiliation=?, Position=?, Image=? WHERE UserID=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    $stmt->bind_param("sssssssssi", $LastName, $FirstName, $MI, $Email, $hashedNewPassword, $ContactNo, $Affiliation, $Position, $Image, $UserID);

    if ($stmt->execute()) {
        showAlert("user profile updated successfully!", "../user_side/landingPageU.php");
    } else {
        showAlert("Error updating user profile: " . $stmt->error, "../user_side/accountSettings.php");
    }

    $stmt->close();
}

$conn->close();
?>
