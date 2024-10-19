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

function getAdminData($conn, $AdminID) {
    $sql = "SELECT * FROM admin WHERE AdminID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $AdminID);
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

function countPendingUsers($conn)
{
    $sqls = "SELECT COUNT(*) AS totalPendingUsers FROM pendinguser";
    $result = $conn->query($sqls);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['totalPendingUsers'];
    } else {
        return 0; // Return 0 if there is an error or no pending users
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['AdminID'])) {
        $AdminID = $_SESSION['AdminID'];
        $adminData = getAdminData($conn, $AdminID);

        if ($adminData) {
            $LastName = $adminData['LastName'];
            $FirstName = $adminData['FirstName'];
            $MI = $adminData['MI'];
            $Email = $adminData['Email'];
            $Password = $adminData['Password'];
            $ContactNo = $adminData['ContactNo'];
            $Position = $adminData['Position'];
            $Affiliation = $adminData['Affiliation'];
            $Image = isset($adminData['Image']) ? $adminData['Image'] : null; // Initialize to null if not set

            $pendingUsersCount = countPendingUsers($conn);
        } else {
            echo "No records found";
        }
    } else {
        echo "AdminID not set in the session";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Form submission logic
    $AdminID = $_POST["AdminID"];
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
        showAlert("Error: New passwords do not match", "../admin/accountSettings.php");
        exit();
    }

    // Check the current password before updating
    $checkPasswordSql = "SELECT Password FROM admin WHERE AdminID = ?";
    $stmtCheckPassword = $conn->prepare($checkPasswordSql);
    $stmtCheckPassword->bind_param("i", $AdminID);
    $stmtCheckPassword->execute();
    $stmtCheckPassword->bind_result($hashedCurrentPassword);
    $stmtCheckPassword->fetch();
    $stmtCheckPassword->close();

    // Verify the current password
    if (!password_verify($Password, $hashedCurrentPassword)) {
        showAlert("Error: Incorrect current password", "../admin/accountSettings.php");
        exit();
    }

    // Hash the new password if provided
    $hashedNewPassword = !empty($NewPassword) ? password_hash($NewPassword, PASSWORD_BCRYPT) : $hashedCurrentPassword;

    // Check if an image is provided
    if ($Image && $Image['error'] === UPLOAD_ERR_OK) {
        $fileType = pathinfo($Image['name'], PATHINFO_EXTENSION);

        // Check if the file type is allowed
        if (!in_array(strtolower($fileType), ['jpg', 'jpeg', 'png'])) {
            showAlert("Only JPG, JPEG, or PNG files are allowed.", "../admin/accountSettings.php");
            exit();
        }

        $uploadDir = '../assets/img/profilePhoto/';
        $fileName = preg_replace('/\s+/', '_', basename($Image['name']));
        $imagePath = $uploadDir . $fileName;

        if (move_uploaded_file($Image['tmp_name'], $imagePath)) {
            $Image = $fileName;
        } else {
            showAlert("Error uploading image", "../admin/accountSettings.php");
            exit();
        }
    }

    // Update the admin details in the database
    $sql = "UPDATE admin SET LastName=?, FirstName=?, MI=?, Email=?, Password=?, ContactNo=?, Affiliation=?, Position=?, Image=? WHERE AdminID=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    $stmt->bind_param("sssssssssi", $LastName, $FirstName, $MI, $Email, $hashedNewPassword, $ContactNo, $Affiliation, $Position, $Image, $AdminID);

    if ($stmt->execute()) {
        showAlert("Admin profile updated successfully!", "../admin/landingPage.php");
    } else {
        showAlert("Error updating admin profile: " . $stmt->error, "../admin/accountSettings.php");
    }

    $stmt->close();
}

$conn->close();
?>
