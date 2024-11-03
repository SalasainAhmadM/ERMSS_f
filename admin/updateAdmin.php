<?php
include('../function/F.allUser.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_POST['userId'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $affiliation = trim($_POST['affiliation']);
    $gender = trim($_POST['gender']);
    $contact = trim($_POST['contact']);
    $position = trim($_POST['position']);
    
    if (empty($userId) || empty($name) || empty($email) || empty($affiliation) || empty($gender) || empty($contact) || empty($position)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }

    $nameParts = explode(' ', $name);
    $firstName = mysqli_real_escape_string($conn, $nameParts[0]);
    $lastName = mysqli_real_escape_string($conn, isset($nameParts[1]) ? $nameParts[1] : '');

    $currentImageQuery = "SELECT Image FROM admin WHERE AdminID = ?";
    if ($stmt = mysqli_prepare($conn, $currentImageQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $currentImageName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database query failed: ' . mysqli_error($conn)]);
        exit;
    }

    $imageName = $currentImageName; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/profilePhoto/';
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $uploadFilePath = $uploadDir . $imageName;

        // Validate image type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'error' => 'Only JPG, JPEG, PNG, and GIF files are allowed.']);
            exit;
        }

        // Validate image size
        if ($_FILES['image']['size'] > 2000000) {
            echo json_encode(['success' => false, 'error' => 'Image size must be less than 2MB.']);
            exit;
        }

         // Move the uploaded file to the designated directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
            echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
            exit;
        }
    }

    $updateQuery = "UPDATE admin SET FirstName = ?, LastName = ?, Email = ?, Affiliation = ?, Gender = ?, Age = ?, ContactNo = ?, Position = ?, Image = ? WHERE AdminID = ?";

    if ($stmt = mysqli_prepare($conn, $updateQuery)) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $email, $affiliation, $gender, $contact, $position, $imageName, $userId);
        
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database preparation failed: ' . mysqli_error($conn)]);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
