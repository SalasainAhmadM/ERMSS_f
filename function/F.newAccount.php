<?php
session_start();
require_once('../db.connection/connection.php');
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$message = '';

function endsWith($haystack, $needle)
{
    return substr($haystack, -strlen($needle)) === $needle;
}

function countPendingUsers($conn)
{
    $sqls = "SELECT COUNT(*) AS totalPendingUsers FROM pendinguser";
    $result = $conn->query($sqls);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['totalPendingUsers'];
    } else {
        return 0;
    }
}
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
            
            
function getAdminData($conn, $AdminID)
{
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

function getLoggedInUserRole($conn, $AdminID)
{
    $sql = "SELECT Role FROM admin WHERE AdminID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $AdminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['Role'];
    } else {
        $stmt->close();
        return false;
    }
}

function checkDuplicateEmail($conn, $email)
{
    $sql = "
    SELECT Email FROM admin WHERE Email = ?
    UNION
    SELECT Email FROM user WHERE Email = ?
";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false; 
    }
}

if (isset($_SESSION['AdminID'])) {
    $AdminID = $_SESSION['AdminID']; 
    $adminData = getAdminData($conn, $AdminID);

    if ($adminData) {
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
        $Role = $adminData['Role'];

        $loggedInUserRole = getLoggedInUserRole($conn, $AdminID);
    } else {
        $_SESSION['error'] = "Admin data not found";
        header("Location: ../admin/newAccount.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Admin session not found";
    header("Location: ../admin/newAccount.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $LastName = $_POST["LastName"];
    $FirstName = $_POST["FirstName"];
    $MI = $_POST["MI"];
    $Image = '';
    $ContactNo = $_POST["ContactNo"];
    $Address = $_POST["Address"];
    $Affiliation = $_POST["Affiliation"];
    $Password = $_POST["Password"];
    $ConfirmPassword = $_POST["ConfirmPassword"];
    $Role = $_POST["Role"];
    $Position = isset($_POST["Position"]) ? $_POST["Position"] : '';
    $Gender = $_POST["Gender"];

    // Check if Passwords match
    if ($Password !== $ConfirmPassword) {
        $_SESSION['error'] = "Error: Passwords do not match";
        header("Location: ../admin/newAccount.php");
        exit();
    }

    // Check if the Email has the required domain
    if (!endsWith($Email, "@gmail.com")) {
        $_SESSION['error'] = "Error: Email must have the domain @gmail.com";
        header("Location: ../admin/newAccount.php");
        exit();
    }

    // Check for duplicate email
    if (checkDuplicateEmail($conn, $Email)) {
        $_SESSION['error'] = "Error: Email already exists in the system";
        header("Location: ../admin/newAccount.php");
        exit();
    }

    $hashedPassword = password_hash($Password, PASSWORD_BCRYPT);

    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
        $fileType = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);

        if (!in_array($fileType, ['jpg', 'jpeg', 'png'])) {
            $_SESSION['error'] = "Only JPG, JPEG, or PNG files are allowed.";
            header("Location: ../admin/newAccount.php");
            exit();
        }

        $uploadDir = '../assets/img/profilePhoto/';
        $fileName = preg_replace('/\s+/', '_', basename($_FILES['Image']['name']));
        $imagePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['Image']['tmp_name'], $imagePath)) {
            $Image = $fileName;
        } else {
            $_SESSION['error'] = "Failed to move uploaded file.";
            header("Location: ../admin/newAccount.php");
            exit();
        }
    }

    $AdminID = $_SESSION['AdminID'];
    $sqlRole = "SELECT Role FROM admin WHERE AdminID = ?";
    $stmtRole = $conn->prepare($sqlRole);
    $stmtRole->bind_param("i", $AdminID);
    $stmtRole->execute();
    $resultRole = $stmtRole->get_result();

    if ($resultRole->num_rows > 0) {
        $rowRole = $resultRole->fetch_assoc();
        $role = $rowRole['Role'];
    } else {
        $_SESSION['error'] = "Error: Unable to fetch admin role";
        header("Location: ../admin/newAccount.php");
        exit();
    }

    $stmtRole->close();

    switch (strtolower($Role)) {
        case 'admin':
            $sql = "INSERT INTO admin (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            break;
        case 'superadmin':
            if ($role === 'SuperAdmin') {
                $sql = "INSERT INTO admin (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            } else {
                $_SESSION['error'] = "Error: Only SuperAdmin can create SuperAdmin accounts";
                header("Location: ../admin/newAccount.php");
                exit();
            }
            break;
        default:
            $sql = "INSERT INTO User (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $LastName, $FirstName, $MI, $Gender, $Email, $hashedPassword, $ContactNo, $Address, $Affiliation, $Position, $Image, $Role);

    if ($stmt->execute()) {

        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'eventmanagement917@gmail.com';  // Your email
            $mail->Password = 'meapvvmlkmiccnjx';  // Your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
    
            $mail->setFrom('eventmanagement917@gmail.com', 'Event Management System');
            $mail->addAddress($Email);  // Send email to the user's email
    
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation Link for Your Account';
            $mail->Body    = 'Your account has been created: <a href="http://localhost/ERMSS_f/index.php?email=' . urlencode($Email) . '">Confirm Registration</a>';
            $mail->AltBody = 'Click on the following link to confirm your registration: http://localhost/ERMSS_f/index.php?email=' . urlencode($Email);
    
            $mail->send();
            $_SESSION['success'] = 'New record created successfully. A confirmation email has been sent to the user.';
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    
        header("Location: ../admin/newAccount.php");
        exit();
    } else {
        $_SESSION['error'] = 'Error: ' . $stmt->error;
        header("Location: ../admin/newAccount.php");
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['AdminID'])) {
        $AdminID = $_SESSION['AdminID'];
        $adminData = getAdminData($conn, $AdminID);

        if ($adminData) {
            $LastName = $adminData['LastName'];
            $FirstName = $adminData['FirstName'];
            $MI = $adminData['MI'];
            $Position = $adminData['Position'];
            $Image = isset($adminData['Image']) ? $adminData['Image'] : null;

            $pendingUsersCount = countPendingUsers($conn);
            $pendingEventsCount = countPendingEvents($conn);
        } else {
            echo "No records found";
        }
    } else {
        echo "No admin session";
    }
}
