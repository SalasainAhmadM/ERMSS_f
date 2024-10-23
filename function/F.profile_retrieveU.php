<?php
session_start();
require_once('../db.connection/connection.php');

function showProfileModal($message)
{
    echo "<script>
              showModal('$message');
          </script>";
}

function getUserData($conn, $UserID)
{
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
        $EducationalAttainment = $userData['EducationalAttainment'] ?: "N/A"; // Set "N/A" if empty
        $Image = isset($userData['Image']) ? $userData['Image'] : null;
    } else {
        showProfileModal("User data not found");
        exit();
    }
} else {
    showProfileModal("UserID not found in session");
    exit();
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
        echo "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your password has been successfully reset.',
                        icon: 'success',
                        customClass: {
                            popup: 'larger-swal' 
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'profile.php';
                        }
                    });
                </script>
            ";
    } else {
        // If an error occurs, display an error message
        echo "Error updating password: " . $conn->error;
    }

    $stmt->close();
}

$alertMessage = '';
// Check if the password change form is submitted
if (isset($_POST['submitp'])) {
    // Retrieve form data
    $userID = $_SESSION['UserID']; // Assuming you have a session for user ID
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Check if new password matches the confirmation password
    if ($newPassword === $confirmNewPassword) {
        // Verify the current password
        if (password_verify($currentPassword, $userData['Password'])) {
            // Change user password
            changeUserPassword($userID, $newPassword);

            // Set success SweetAlert message
            $alertMessage = "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your password has been successfully reset.',
                        icon: 'success',
                        customClass: {
                            popup: 'larger-swal' 
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'profile.php';
                        }
                    });
                </script>
            ";
        } else {
            // Trigger SweetAlert error for incorrect current password
            $alertMessage = "
                <script>
                    Swal.fire({
                        title: 'Incorrect Password!',
                        text: 'The current password you entered is incorrect.',
                        icon: 'error',
                        customClass: {
                            popup: 'larger-swal' 
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'profile.php';
                        }
                    });
                </script>
            ";
        }
    } else {
        // Trigger SweetAlert error for password mismatch
        $alertMessage = "
            <script>
                Swal.fire({
                    title: 'Password Mismatch!',
                    text: 'New password and confirmation do not match.',
                    icon: 'error',
                    customClass: {
                        popup: 'larger-swal' 
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'profile.php';
                    }
                });
            </script>
        ";
    }
}
?>