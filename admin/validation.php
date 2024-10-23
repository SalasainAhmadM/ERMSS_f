<?php
include('../function/F.userValidation.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>

    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <!-- font awesome cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="css/table.css">
</head>

<body>
    <style>
        .loader {
            width: 90px;
            height: 14px;
            box-shadow: 0 3px 0 #fff;
            display: none;
            /* Initially hidden */
            margin: 10px auto;
            grid-area: 1/1;
        }

        .loader:before,
        .loader:after {
            content: "";
            grid-area: 1/1;
            background: radial-gradient(circle closest-side, var(--c, red) 92%, #0000) 0 0/calc(100%/4) 100%;
            animation: l4 1s infinite linear;
        }

        .loader:after {
            --c: #000;
            background-color: #fff;
            box-shadow: 0 -2px 0 0 #fff;
            clip-path: inset(-2px calc(50% - 10px));
        }

        @keyframes l4 {
            100% {
                background-position: calc(100%/3) 0;
            }
        }
    </style>
    <?php
    if (isset($_SESSION['success'])) {
        echo "<script>
    Swal.fire({
      title: 'Approved!',
      text: 'Email Confirmation has been sent successfully!',
      icon: 'success'
    });
    </script>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['decline'])) {
        echo "<script>
    Swal.fire({
      title: 'Declined!',
      text: 'The account has been declined.',
      icon: 'info' 
    });
    </script>";
        unset($_SESSION['decline']);
    }

    if (isset($_SESSION['error'])) {
        echo "<script>
    Swal.fire({
      title: 'Error!',
      text: '" . $_SESSION['error'] . "',
      icon: 'error'
    });
    </script>";
        unset($_SESSION['error']);
    }
    ?>
    <!--=========== SIDEBAR =============-->
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <img src="img/wesmaarrdec-removebg-preview.png" alt="">
                <span>WESMAARRDEC</span>
            </div>
            <i class="bx bx-menu" id="btnn"></i>
        </div>
        <div class="user">
            <?php if (!empty($Image)): ?>
                <img src="../assets/img/profilePhoto/<?php echo $Image; ?>" alt="user" class="user-img">
            <?php else: ?>
                <img src="../assets/img/profile.jpg" alt="default user" class="user-img">
            <?php endif; ?>
            <div>
                <p class="bold"><?php echo $FirstName . ' ' . $MI . ' ' . $LastName; ?></p>
                <p><?php echo $Position; ?></p>
            </div>
        </div>

        <ul>
            <li class="nav-sidebar">
                <a href="adminDashboard.php">
                    <i class="bx bxs-grid-alt"></i>
                    <span class="nav-item">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>

            <li class="events-side nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-archive'></i>
                    <span class="nav-item">Events</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Events</span>
                <div class="uno">
                    <ul>
                        <?php if ($_SESSION['Role'] === 'superadmin') { ?>
                            <a href="eventsValidation.php">Events Validation
                                <span><?php echo $pendingEventsCount; ?></span></a>
                        <?php } elseif ($_SESSION['Role'] === 'Admin') { ?>
                            <a href="pendingEvents.php">Pending Events <span><?php echo $pendingEventsCount; ?></span></a>
                        <?php } ?>
                        <a href="landingPage.php">Events</a>
                        <a href="addEvent.php">Add Event</a>
                        <a href="addEventTypeMode.php">Event Settings</a>
                        <a href="history.php">History</a>
                        <a href="cancelEvent.php">Cancelled</a>
                    </ul>
                </div>
            </li>

            <li class="events-side2 nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-user'></i>
                    <span class="nav-item">Account</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Account</span>
                <div class="uno">
                    <ul>
                        <a href="profile.php">My Profile</a>
                        <a href="validation.php">User Validation <span><?php echo $pendingUsersCount; ?></span></a>
                        <a href="newAccount.php">Create Account</a>
                        <a href="allUser.php">All User</a>
                    </ul>
                </div>
            </li>

            <li class="nav-sidebar">
                <a href="../login.php">
                    <i class="bx bx-log-out"></i>
                    <span class="nav-item">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="attendance">
            <div class="attendance_header">
                <div class="attendance_title">
                    User Account Validation
                </div>

                <div class="search_attendance">
                    <input type="text" id="search_input" placeholder="Filter using Name">
                </div>
            </div>

            <div class="table_wrap">
                <div class="table_header">
                    <ul>
                        <li>
                            <div class="item">
                                <div class="name">
                                    <span>Full Name</span>
                                </div>
                                <div class="department">
                                    <span>Affiliation</span>
                                </div>
                                <div class="department">
                                    <span>POSITION</span>
                                </div>
                                <div class="info">
                                    <span>EMAIL</span>
                                </div>
                                <div class="phone">
                                    <span>PHONE#</span>
                                </div>
                                <div class="status">
                                    <span>ACTION</span>
                                </div>
                                <div class="status">
                                    <span></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table_body">
                    <?php
                    // Fetch and display pending users
                    $sql = "SELECT * FROM pendinguser";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $FirstName = $row['FirstName'];
                            $MI = $row['MI'];
                            $LastName = $row['LastName'];
                            $Affiliation = $row['Affiliation'];
                            $Position = $row['Position'];
                            $Email = $row['Email'];
                            $ContactNo = $row['ContactNo'];
                            $PendingUserID = $row['PendingUserID'];
                            ?>
                            <form action='' method='POST'>
                                <ul>
                                    <li>
                                        <div class="item">
                                            <div class="name">
                                                <span><?php echo $FirstName . ' ' . $MI . ' ' . $LastName; ?></span>
                                            </div>

                                            <div class="department">
                                                <span><?php echo $Affiliation . ' ' ?></span>
                                            </div>

                                            <div class="department">
                                                <span><?php echo $Position . ' ' ?></span>
                                            </div>

                                            <div class="info">
                                                <span><?php echo $Email . ' ' ?></span>
                                            </div>

                                            <div class="phone">
                                                <span><?php echo $ContactNo . ' ' ?></span>
                                            </div>

                                            <div class="status">
                                                <button type="submit" name="submitAccept" class="approve"
                                                    value="<?php echo $Email; ?>">Approve</button>
                                                <input type="hidden" name="Email" value="<?php echo $Email; ?>">
                                                <input type="hidden" name="PendingUserID" value="<?php echo $PendingUserID; ?>">
                                                <input type="hidden" name="Role" value="User">
                                            </div>

                                            <div class="status">
                                                <button type="submit" name="submitDecline" class="approve">Decline</button>
                                                <input type="hidden" name="PendingUserID" value="<?php echo $PendingUserID; ?>">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                            <?php
                        }
                    } else {
                        echo "No records found";
                    }
                    ?>
                    <?php
                    // Check if status is set in the URL
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];

                        // Generate SweetAlert based on the status
                        if ($status == 'success') {
                            echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Account has been approved and confirmation email sent.',
                icon: 'success'
            });
        </script>";
                        } elseif ($status == 'declined') {
                            echo "<script>
            Swal.fire({
                title: 'Declined!',
                text: 'Account request has been declined and email sent.',
                icon: 'info'
            });
        </script>";
                        } elseif ($status == 'error') {
                            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while sending the email. Please try again.',
                icon: 'error'
            });
        </script>";
                        }
                    }
                    ?>
                </div>
            </div>
            <script src="js/attendanceScript.js"></script>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--sidebar functionality-->
    <script src="js/sidebar.js"></script>



</body>

</html>