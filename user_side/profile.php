<?php
include('../function/F.profile_retrieveU.php');
// Fetch total number of cancelled events
$countCancelledEventsSql = "SELECT COUNT(*) AS totalCancelledEvents FROM Events WHERE event_cancel IS NOT NULL AND event_cancel <> ''";
$countCancelledEventsResult = mysqli_query($conn, $countCancelledEventsSql);
$countCancelledEventsRow = mysqli_fetch_assoc($countCancelledEventsResult);
$totalCancelledEvents = $countCancelledEventsRow['totalCancelledEvents'];

// Include your database connection code here
require_once('../db.connection/connection.php');

// Fetch total number of events joined by the user
$eventsJoinedSql = "SELECT COUNT(*) AS totalEventsJoined 
                    FROM EventParticipants 
                    WHERE UserID = ?";
$eventsJoinedStmt = $conn->prepare($eventsJoinedSql);
$eventsJoinedStmt->bind_param("i", $UserID);
$eventsJoinedStmt->execute();
$eventsJoinedResult = $eventsJoinedStmt->get_result();
$eventsJoinedRow = mysqli_fetch_assoc($eventsJoinedResult);
$totalEventsJoined = $eventsJoinedRow['totalEventsJoined'];
function countCanceledEvents($conn, $UserID)
{
    $sql = "
        SELECT COUNT(DISTINCT e.event_id) AS totalCanceledEvents 
        FROM events e
        INNER JOIN cancel_reason cr ON e.event_id = cr.event_id
        WHERE cr.UserID = ?"; // Ensure that the UserID matches

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $UserID); // Bind the UserID as a parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['totalCanceledEvents'];
    } else {
        return 0;
    }
}

// Call the function to get the number of canceled events
$canceledEventsCount = countCanceledEvents($conn, $UserID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>

    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <?php include('sidebar.php'); ?>


    <div class="main-content">
        <section class="accounts section--lg">
            <div class="accounts__container container grid">
                <div class="account__tabs">
                    <p class="account__tab active-tab" data-target="#dashboard">
                        <i class="fa-regular fa-user"></i> My Profile
                    </p>

                    <p class="account__tab" data-target="#update-profile">
                        <i class="fa-solid fa-sliders"></i> Update Profile
                    </p>

                    <p class="account__tab" data-target="#change-password">
                        <i class="fa-solid fa-lock"></i> Change Password
                    </p>

                </div>

                <div class="tabs__content">
                    <div class="tab__content active-tab" content id="dashboard">
                        <h3 class="tab__header"><?php echo $FirstName . ' ' . $MI . ' ' . $LastName; ?></h3>

                        <div class="tab__body">
                            <?php if (!empty($Image)): ?>
                                <img src="../assets/img/profilePhoto/<?php echo $Image; ?>" alt="user" width="100"
                                    class="profile-icon">
                            <?php else: ?>
                                <img src="../assets/img/profile.jpg" alt="default user" width="100" class="profile-icon">
                            <?php endif; ?>

                            <p class="tab__description">
                                <span>Gender: </span> <?php echo $Gender; ?>
                            </p>

                            <p class="tab__description">
                                <span>Age: </span> <?php echo $Age; ?>
                            </p>

                            <p class="tab__description">
                                <span>Email: </span> <?php echo $Email; ?>
                            </p>
                            <p class="tab__description">
                                <span>Phone: </span> <?php echo $ContactNo; ?>
                            </p>

                            <p class="tab__description">
                                <span>Address: </span> <?php echo $Address; ?>
                            </p>

                            <p class="tab__description">
                                <span>Occupation: </span> <?php echo $Position; ?>
                            </p>

                            <p class="tab__description">
                                <span>Affiliation: </span> <?php echo $Affiliation; ?>
                            </p>

                            <p class="tab__description">
                                <span>Educational Attainment: </span> <?php echo $EducationalAttainment; ?>
                            </p>

                            <p class="tab__description">
                                <span>Total Events Joined: </span> <?php echo $totalEventsJoined; ?>
                            </p>

                        </div>
                    </div>


                    <div class="tab__content" content id="update-profile">
                        <h3 class="tab__header">Update Profile</h3>
                        <div class="tab__body">
                            <form id="profileForm" action="update_profile.php" method="POST"
                                enctype="multipart/form-data" class="form grid">
                                <label for="photoUpload">Change Photo</label>
                                <?php if (!empty($Image)): ?>
                                    <img src="../assets/img/profilePhoto/<?php echo $Image; ?>" alt="user" width="100"
                                        class="profile-icon" id="profilePreview">
                                <?php else: ?>
                                    <img src="../assets/img/profile.jpg" alt="default user" width="100" class="profile-icon"
                                        id="profilePreview">
                                <?php endif; ?>
                                <input type="file" name="Image" id="Image" accept="image/*" class="form__input"
                                    onchange="previewImage(event)">

                                <input type="text" name="FirstName" style="display: none;"
                                    value="<?php echo $FirstName; ?>">
                                <input type="text" name="LastName" style="display: none;"
                                    value="<?php echo $LastName; ?>">
                                <input type="text" name="MI" style="display: none;" value="<?php echo $MI; ?>">
                                <input type="text" name="Gender" style="display: none;" value="<?php echo $Gender; ?>">
                                <input type="text" name="Email" style="display: none;" value="<?php echo $Email; ?>">

                                <input type="text" name="ContactNo" placeholder="Phone" class="form__input"
                                    value="<?php echo $ContactNo; ?>">
                                <input type="text" name="Address" placeholder="Address" class="form__input"
                                    value="<?php echo $Address; ?>">
                                <input type="number" name="Age" placeholder="Age" class="form__input"
                                    value="<?php echo $Age; ?>" required>
                                <input type="text" name="Position" placeholder="Occupation" class="form__input"
                                    value="<?php echo $Position; ?>">
                                <input type="text" name="Affiliation" placeholder="Affiliation" class="form__input"
                                    value="<?php echo $Affiliation; ?>">

                                <script>
                                    window.onload = function () {
                                        // Select all input fields in the form
                                        const inputs = document.querySelectorAll('input[type="text"], input[type="number"]');

                                        // Iterate over each input field and check if the value is "N/A"
                                        inputs.forEach(input => {
                                            if (input.value === "N/A") {
                                                input.value = ""; // Set the value to empty so the placeholder is displayed
                                            }
                                        });
                                    };
                                </script>
                                <label for="EducationalAttainment">Educational Attainment</label>
                                <select name="EducationalAttainment" class="form__input">
                                    <option value="" disabled selected>Select your educational attainment</option>
                                    <option value="High School" <?php echo ($EducationalAttainment == 'High School') ? 'selected' : ''; ?>>High School</option>
                                    <option value="Associate Degree" <?php echo ($EducationalAttainment == 'Associate Degree') ? 'selected' : ''; ?>>Associate Degree</option>
                                    <option value="Bachelors Degree" <?php echo ($EducationalAttainment == 'Bachelors Degree') ? 'selected' : ''; ?>>Bachelors Degree</option>
                                    <option value="Masters Degree" <?php echo ($EducationalAttainment == 'Masters Degree') ? 'selected' : ''; ?>>Masters Degree</option>
                                    <option value="Doctorate" <?php echo ($EducationalAttainment == 'Doctorate') ? 'selected' : ''; ?>>Doctorate</option>
                                </select>

                                <div class="form__btn">
                                    <button class="btn btn--md" id="submitBtn" name="Submit">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>


                    <div class="tab__content" content id="change-password">
                        <h3 class="tab__header">Change Password</h3>

                        <div class="tab__body">
                            <form action="" method="POST" enctype="multipart/form-data" class="form grid">
                                <input type="password" name="currentPassword" placeholder="Current Password"
                                    class="form__input" required>

                                <input type="password" name="newPassword" placeholder="New Password" class="form__input"
                                    required>

                                <input type="password" name="confirmNewPassword" placeholder="Confirm New Password"
                                    class="form__input" required>

                                <div class="form__btn">
                                    <button type="submit" name="submitp" class="btn btn--md">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>


    <!--profile-->
    <!--SWIPER JS CDN-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const tabs = document.querySelectorAll('[data-target]'),
            tabContents = document.querySelectorAll('[content]');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = document.querySelector(tab.dataset.target);
                tabContents.forEach((tabContent) => {
                    tabContent.classList.remove('active-tab');
                });

                target.classList.add('active-tab');

                tabs.forEach((tab) => {
                    tab.classList.remove('active-tab');
                });

                tab.classList.add('active-tab');
            });
        });
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('profilePreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <!-- Include SweetAlert2 -->
    <script src="assets/js/sweetalert2.js"></script>

    <?php
    // Display success message
    if (isset($_SESSION['success'])) {
        echo "<script>
    Swal.fire({
        title: 'Success!',
        text: '" . $_SESSION['success'] . "',
        icon: 'success',
        customClass: {
            popup: 'larger-swal' 
        }
    });
    </script>";
        unset($_SESSION['success']);  // Clear the message
    }

    // Display error message
    if (isset($_SESSION['error'])) {
        echo "<script>
    Swal.fire({
        title: 'Error!',
        text: '" . $_SESSION['error'] . "',
        icon: 'error',
        customClass: {
            popup: 'larger-swal' 
        }
    });
    </script>";
        unset($_SESSION['error']);  // Clear the message
    }
    ?>
    <!--JS -->
    <script src="js/eventscript.js"></script>
    <?php echo $alertMessage; ?>
    <!--sidebar functionality-->
    <script src="js/sidebar.js"></script>

    <script>
        let dropdown_items = document.querySelectorAll('.job-filter form .dropdown-container .dropdown .lists .items');

        dropdown_items.forEach(items => {
            items.onclick = () => {
                items_parent = items.parentElement.parentElement;
                let output = items_parent.querySelector('.output');
                output.value = items.innerText;
            }
        });
    </script>
</body>

</html>