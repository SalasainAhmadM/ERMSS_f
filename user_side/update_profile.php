<?php
// Start the session and connect to the database
session_start();
require_once('../db.connection/connection.php');

// Fetch the session UserID
$userID = $_SESSION['UserID'];

// Check if form is submitted
if (isset($_POST['Submit'])) {
    // Fetch form data
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $MI = $_POST['MI'];
    $gender = $_POST['Gender'];
    $age = $_POST['Age'];
    $email = $_POST['Email'];
    $contactNo = $_POST['ContactNo'];
    $address = $_POST['Address'];
    $position = $_POST['Position'];
    $affiliation = $_POST['Affiliation'];
    $educationalAttainment = !empty($_POST['EducationalAttainment']) ? $_POST['EducationalAttainment'] : 'N/A';

    // Handle file upload
    $image = $_FILES['Image']['name'];
    $imageTmpName = $_FILES['Image']['tmp_name'];
    $imagePath = "../assets/img/profilePhoto/";

    if (!empty($image)) {
        // Generate a unique name for the uploaded image
        $newImageName = uniqid() . "-" . basename($image);
        $targetFilePath = $imagePath . $newImageName;

        // Move the uploaded file
        if (move_uploaded_file($imageTmpName, $targetFilePath)) {
            // Update image path in the database
            $imageUpdateQuery = "UPDATE user SET Image = '$newImageName' WHERE UserID = $userID";
            if (!mysqli_query($conn, $imageUpdateQuery)) {
                $_SESSION['error'] = 'There was an error uploading the image.';
                header('Location: profile.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 'There was an error uploading the image.';
            header('Location: profile.php');
            exit();
        }
    }

    // Update the other profile fields in the database
    $updateQuery = "UPDATE user SET 
                    FirstName = '$firstName', 
                    LastName = '$lastName', 
                    MI = '$MI', 
                    Gender = '$gender', 
                    Age = '$age', 
                    Email = '$email', 
                    ContactNo = '$contactNo', 
                    Address = '$address', 
                    Affiliation = '$affiliation', 
                    Position = '$position', 
                    EducationalAttainment = '$educationalAttainment' 
                    WHERE UserID = $userID";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = 'Your profile has been successfully updated.';
    } else {
        $_SESSION['error'] = 'There was an error updating your profile.';
    }

    header('Location: profile.php');  // Redirect after success or error
    exit();
}
?>