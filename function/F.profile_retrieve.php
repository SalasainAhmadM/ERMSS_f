<?php
session_start();
require_once('../db.connection/connection.php');

function showProfileModal($message) {
    echo "<script>
              showModal('$message');
          </script>";
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

$pendingUsersCount = countPendingUsers($conn);
function countPendingEvents($conn)
            {
                $sqls = "SELECT COUNT(*) AS totalPendingEvents FROM pendingevents";
                $result = $conn->query($sqls);
            
                if ($result) {
                    $row = $result->fetch_assoc();
                    return $row['totalPendingEvents'];
                } else {
                    return 0; 
                }
            }
            
            $pendingEventsCount = countPendingEvents($conn);
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

$AdminID = $_SESSION['AdminID']; // Retrieve AdminID from the session
$adminData = getAdminData($conn, $AdminID);

if ($adminData) {
    // Retrieve existing admin data
    $LastName = $adminData['LastName'];
    $FirstName = $adminData['FirstName'];
    $MI = $adminData['MI'];
    $Gender = $adminData['Gender'];
    $Email = $adminData['Email'];
    $ContactNo = $adminData['ContactNo'];
    $Address = $adminData['Address'];
    $Affiliation = $adminData['Affiliation'];
    $Position = $adminData['Position'];
    $Image = isset($adminData['Image']) ? $adminData['Image'] : null;
} else {
    // Redirect or handle error if admin data is not found
    showProfileModal("Admin data not found");
    exit();
}



// Function to update admin data
function updateAdminProfile($adminID, $firstName, $lastName, $mi, $email, $contactNo, $address, $affiliation, $position, $image)
{
    global $conn;

    // Prepare SQL statement to update admin data
    $sql = "UPDATE `admin` SET 
            `FirstName` = '$firstName', 
            `LastName` = '$lastName', 
            `MI` = '$mi', 
            `Email` = '$email', 
            `ContactNo` = '$contactNo', 
            `Address` = '$address', 
            `Affiliation` = '$affiliation', 
            `Position` = '$position'";

    // Check if a new image is uploaded
    if (!empty($image)) {
        $sql .= ", `Image` = '$image'";
    }

    $sql .= " WHERE `AdminID` = $adminID";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // If the update is successful, redirect to the profile page or display a success message
        header("Location: profile.php");
        exit();
    } else {
        // If an error occurs, display an error message
        echo "Error updating record: " . $conn->error;
    }
}

// Check if the form is submitted
if (isset($_POST['Submit'])) {
    // Retrieve form data
    $adminID = $_SESSION['AdminID']; // Assuming you have a session for admin ID
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $mi = $_POST['MI'];
    $email = $_POST['Email'];
    $contactNo = $_POST['ContactNo'];
    $address = $_POST['Address'];
    $affiliation = $_POST['Affiliation'];
    $position = $_POST['Position'];

    // Check if a new image is uploaded
    if (!empty($_FILES['Image']['name'])) {
        // Upload image file
        $targetDir = "../assets/img/profilePhoto/";
        $fileName = basename($_FILES['Image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'jpeg', 'png');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server
            if (move_uploaded_file($_FILES['Image']['tmp_name'], $targetFilePath)) {
                $image = $fileName;
            } else {
                echo "Sorry, there was an error uploading your file.";
                $image = ''; // Set image to empty string if upload fails
            }
        } else {
            echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
            $image = ''; // Set image to empty string if file format is not allowed
        }
    } else {
        // If no new image is uploaded, set image to NULL to keep the existing image
        $image = NULL;
    }

    // Call the function to update admin profile
    updateAdminProfile($adminID, $firstName, $lastName, $mi, $email, $contactNo, $address, $affiliation, $position, $image);
}
// Function to change admin password
function changeAdminPassword($adminID, $newPassword)
{
    global $conn;

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare SQL statement to update admin password
    $sql = "UPDATE `admin` SET `Password` = '$hashedPassword' WHERE `AdminID` = $adminID";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // If the update is successful, redirect to the profile page or display a success message
        header("Location: profile.php");
        exit();
    } else {
        // If an error occurs, display an error message
        echo "Error updating password: " . $conn->error;
    }
}

// Check if the password change form is submitted
if (isset($_POST['submitp'])) {
    // Retrieve form data
    $adminID = $_SESSION['AdminID']; // Assuming you have a session for admin ID
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Check if the new password and confirm new password match
    if ($newPassword === $confirmNewPassword) {
        // Verify the current password
        if (password_verify($currentPassword, $adminData['Password'])) {
            // Call the function to change the admin password
            changeAdminPassword($adminID, $newPassword);
        } else {
            // Display an error message if the current password is incorrect
            echo "Current password is incorrect.";
        }
    } else {
        // Display an error message if the new password and confirm new password do not match
        echo "New password and confirm password do not match.";
    }
}

$conn->close();
?>