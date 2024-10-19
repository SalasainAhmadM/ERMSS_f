<?php
session_start();
require_once('../db.connection/connection.php');

function showProfileModal($message) {
    echo "<script>
              showModal('$message');
          </script>";
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

if (isset($_SESSION['UserID'])) {
    $UserID = $_SESSION['UserID']; // Retrieve UserID from the session
    $userData = getUserData($conn, $UserID);

    if ($userData) {
        // Retrieve existing user data
        $LastName = $userData['LastName'];
        $FirstName = $userData['FirstName'];
        $MI = $userData['MI'];
        $Gender = $userData['Gender'];
        $Age = $userData['Age'];
        $Email = $userData['Email'];
        $ContactNo = $userData['ContactNo'];
        $Address = $userData['Address'];
        $Affiliation = $userData['Affiliation'];
        $Position = $userData['Position'];
        $EducationalAttainment = $userData['EducationalAttainment'];
        $Image = isset($userData['Image']) ? $userData['Image'] : null;
    } else {
        // Redirect or handle error if user data is not found
        showProfileModal("User data not found");
        exit();
    }
} else {
    // Redirect or handle error if UserID is not set in the session
    showProfileModal("UserID not found in session");
    exit();
}

// Function to update user profile
function updateUserProfile($userID, $firstName, $lastName, $mi, $gender, $age, $email, $contactNo, $address, $affiliation, $position, $educationalAttainment, $image)
{
    global $conn;

    // Prepare SQL statement to update user data
    $sql = "UPDATE user SET 
            FirstName = ?, 
            LastName = ?, 
            MI = ?, 
            Gender = ?, 
            Age = ?, 
            Email = ?, 
            ContactNo = ?, 
            Address = ?, 
            Affiliation = ?, 
            Position = ?, 
            EducationalAttainment = ?";

    // Check if a new image is uploaded
    if (!empty($image)) {
        $sql .= ", Image = ?";
    }

    $sql .= " WHERE UserID = ?";
    
    $stmt = $conn->prepare($sql);

    // Bind parameters
    if (!empty($image)) {
        $stmt->bind_param("ssssisssssssi", $firstName, $lastName, $mi, $gender, $age, $email, $contactNo, $address, $affiliation, $position, $educationalAttainment, $image, $userID);
    } else {
        $stmt->bind_param("ssssissssssi", $firstName, $lastName, $mi, $gender, $age, $email, $contactNo, $address, $affiliation, $position, $educationalAttainment, $userID);
    }

    if ($stmt->execute()) {
        // If the update is successful, redirect to the profile page or display a success message
        header("Location: profile.php");
        exit();
    } else {
        // If an error occurs, display an error message
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

// Function to change user password
function changeUserPassword($userID, $newPassword)
{
    global $conn;

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare SQL statement to update user password
    $sql = "UPDATE user SET Password = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $userID);

    // Execute the SQL statement
    if ($stmt->execute()) {
        // If the update is successful, redirect to the profile page or display a success message
        header("Location: profile.php");
        exit();
    } else {
        // If an error occurs, display an error message
        echo "Error updating password: " . $conn->error;
    }

    $stmt->close();
}

// Check if the form is submitted
if (isset($_POST['Submit'])) {
    // Retrieve form data
    $userID = $_SESSION['UserID']; // Assuming you have a session for user ID
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $mi = $_POST['MI'];
    $gender = $_POST['Gender'];
    $age = $_POST['Age'];
    $email = $_POST['Email'];
    $contactNo = $_POST['ContactNo'];
    $address = $_POST['Address'];
    $affiliation = $_POST['Affiliation'];
    $position = $_POST['Position'];
    $educationalAttainment = $_POST['EducationalAttainment'];

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

    // Call the function to update user profile
    updateUserProfile($userID, $firstName, $lastName, $mi, $gender, $age, $email, $contactNo, $address, $affiliation, $position, $educationalAttainment, $image);
}

// Check if the password change form is submitted
if (isset($_POST['submitp'])) {
    // Retrieve form data
    $userID = $_SESSION['UserID']; // Assuming you have a session for user ID
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Check if the new password and confirm new password match
    if ($newPassword === $confirmNewPassword) {
        // Verify the current password
        if (password_verify($currentPassword, $userData['Password'])) {
            // Call the function to change the user password
            changeUserPassword($userID, $newPassword);
        } else {
            // Display an error message if the current password is incorrect
            echo "Current password is incorrect.";
        }
    } else {
        // Display an error message if the new password and confirm new password do not match
        echo "New password and confirm password do not match.";
    }
}
?>