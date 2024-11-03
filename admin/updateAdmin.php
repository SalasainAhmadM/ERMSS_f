<?php
include('../function/F.allUser.php'); // Ensure this includes the database connection

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

    // Split name into first and last names
    $nameParts = explode(' ', $name, 2);
    $firstName = mysqli_real_escape_string($conn, $nameParts[0]);
    $lastName = mysqli_real_escape_string($conn, isset($nameParts[1]) ? $nameParts[1] : '');

    // Retrieve current image if no new one is uploaded
    $currentImageQuery = "SELECT Image FROM admin WHERE AdminID = ?";
    if ($stmt = mysqli_prepare($conn, $currentImageQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $currentImageName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to retrieve current image: ' . mysqli_error($conn)]);
        exit;
    }

    $imageName = $currentImageName;

    // Handle image upload if a new file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/profilePhoto/';
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageName = uniqid() . '.' . $imageFileType;
        $uploadFilePath = $uploadDir . $imageName;

        // Validate image type and size
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'error' => 'Only JPG, JPEG, PNG, and GIF files are allowed.']);
            exit;
        }
        if ($_FILES['image']['size'] > 2000000) {
            echo json_encode(['success' => false, 'error' => 'Image size must be less than 2MB.']);
            exit;
        }

        // Attempt to move the file to the upload directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
            echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
            exit;
        }
    }

    // Prepare update query
    $updateQuery = "UPDATE admin SET FirstName = ?, LastName = ?, Email = ?, Affiliation = ?, Gender = ?, ContactNo = ?, Position = ?, Image = ? WHERE AdminID = ?";
    if ($stmt = mysqli_prepare($conn, $updateQuery)) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $email, $affiliation, $gender, $contact, $position, $imageName, $userId);

        // Execute and provide feedback
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Update failed: ' . mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare update query: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>