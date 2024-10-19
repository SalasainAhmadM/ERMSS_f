<?php
session_start();
require_once('../db.connection/connection.php');

$message = '';

function endsWith($haystack, $needle)
{
    return substr($haystack, -strlen($needle)) === $needle;
}
function showAlert($message, $redirectPath = null)
{
    echo "<script>alert('$message');";
    if ($redirectPath) {
        echo "window.location.href = '$redirectPath';";
    }
    echo "</script>";
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

function getLoggedInUserRole($conn, $AdminID) {
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

// Check if AdminID is set in the session
if (isset($_SESSION['AdminID'])) {
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
        $Role = $adminData['Role'];

        // Get the logged-in user's role
        $loggedInUserRole = getLoggedInUserRole($conn, $AdminID);
    } else {
        // Redirect or handle error if admin data is not found
        showProfileModal("Admin data not found");
        exit();
    }
} else {
    // Handle the case where AdminID is not set in the session
    showProfileModal("Admin session not found");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
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

    // Additional validation and sanitation here

    // Check if Passwords match
    if ($Password !== $ConfirmPassword) {
        showAlert("Error: Passwords do not match", "../admin/newAccount.php");
        exit();
    }

    // Check if the Email has the required domain
    if (!endsWith($Email, "@gmail.com")) {
        showAlert("Error: Email must have the domain @gmail.com", "../admin/newAccount.php");
        exit();
    }

    // Hash the Password before storing in the database
    $hashedPassword = password_hash($Password, PASSWORD_BCRYPT);

    // Check if an image is uploaded
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
        $fileType = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);

        // Check if the file type is allowed
        if (!in_array($fileType, ['jpg', 'jpeg', 'png'])) {
            showAlert("Only JPG, JPEG, or PNG files are allowed.", "../admin/newAccount.php");
            exit();
        }

        $uploadDir = '../assets/img/profilePhoto/';
        $fileName = preg_replace('/\s+/', '_', basename($_FILES['Image']['name']));
        $imagePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['Image']['tmp_name'], $imagePath)) {
            $Image = $fileName;
        } else {
            showAlert("Failed to move uploaded file.", "../admin/newAccount.php");
            exit();
        }
    }

    // Fetch the Role of the logged-in admin
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
        showAlert("Error: Unable to fetch admin role", "../admin/newAccount.php");
        exit();
    }

    $stmtRole->close();

    // Your SQL query to insert data into the appropriate table based on the role
    switch (strtolower($Role)) {
        case 'admin':
            $sql = "INSERT INTO admin (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            break;
        case 'superadmin': // Change 'Director' to 'SuperAdmin'
            if ($role === 'SuperAdmin') {
                $sql = "INSERT INTO admin (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            } else {
                showAlert("Error: Only SuperAdmin can create SuperAdmin accounts", "../admin/newAccount.php");
                exit();
            }
            break;
        default:
            $sql = "INSERT INTO User (LastName, FirstName, MI, Gender, Email, Password, ContactNo, Address, Affiliation, Position, Image, Role)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);

    if (strtolower($Role) !== 'superadmin') {
        $stmt->bind_param("ssssssssssss", $LastName, $FirstName, $MI, $Gender, $Email, $hashedPassword, $ContactNo, $Address, $Affiliation, $Position, $Image, $Role);
    } else {
        $stmt->bind_param("ssssssssssss", $LastName, $FirstName, $MI, $Gender, $Email, $hashedPassword, $ContactNo, $Address, $Affiliation, $Position, $Image, $Role);
    }

    if ($stmt->execute()) {
        showAlert("New record created successfully", "../admin/landingPage.php");
    } else {
        showAlert("Error: " . $stmt->error, "../admin/newAccount.php");
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET requests
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
        } else {
            echo "No records found";
        }
    } else {
        echo "AdminID not set in the session";
    }
}
?>
