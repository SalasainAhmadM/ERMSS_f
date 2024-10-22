<?php
include('../function/F.profile_retrieve.php');
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

    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <!-- font awesome cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="css/main.css">
</head>
<body>

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
                            <a href="eventsValidation.php">Events Validation <span><?php echo $pendingEventsCount; ?></span></a>
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
                    <a href="validation.php">User Validation <span><?php echo $pendingUsersCount;  ?></span></a>
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
    <section class="accounts section--lg">
        <div class="accounts__container container grid">
            <div class="account__tabs">
                <p class="account__tab active-tab" data-target="#dashboard">
                    <i class="fa-regular fa-user"></i>  My Profile
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
                    <img src="../assets/img/profilePhoto/<?php echo $Image; ?>" alt="user" width="100" class="profile-icon">
                <?php else: ?>
                    <img src="../assets/img/profile.jpg" alt="default user" width="100" class="profile-icon">
                <?php endif; ?>

                        <p class="tab__description">
                            <span>Gender: </span> <?php echo $Gender; ?>
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

                                    
                    </div>
                </div>                
                <div class="tab__content" content id="update-profile">
                    <h3 class="tab__header">Update Profile</h3>
                    <div class="tab__body">
                        <form id="profileForm" action="" method="POST" enctype="multipart/form-data" class="form grid">
                            <label for="photoUpload">Change Photo</label>
                            <?php if (!empty($Image)): ?>
                                <img src="../assets/img/profilePhoto/<?php echo $Image; ?>" alt="user" width="100" class="profile-icon" id="profilePreview">
                            <?php else: ?>
                                <img src="../assets/img/profile.jpg" alt="default user" width="100" class="profile-icon" id="profilePreview">
                            <?php endif; ?>
                            <input type="file" name="Image" id="Image" accept="image/*" class="form__input" onchange="previewImage(event)">
                            <input type="text" name="FirstName" style="display: none;" value="<?php echo $FirstName; ?>">
                            <input type="text" name="LastName" style="display: none;"value="<?php echo $LastName; ?>">
                            <input type="text" name="MI" style="display: none;"value="<?php echo $MI; ?>">
                            <input type="text" name="Gender" style="display: none;"value="<?php echo $Gender; ?>">
                            <input type="text" name="Email" style="display: none;"value="<?php echo $Email; ?>">
                            <input type="password" name="Password" style="display: none;"value="<?php echo $Password; ?>">
                            <input type="text" name="ContactNo" placeholder="Phone" class="form__input" value="<?php echo $ContactNo; ?>">
                            <input type="text" name="Address" placeholder="Address" class="form__input" value="<?php echo $Address; ?>">
                            <input type="text" name="Position" placeholder="Position" class="form__input" value="<?php echo $Position; ?>">
                            <input type="text" name="Affiliation" placeholder="Affiliation" class="form__input" value="<?php echo $Affiliation; ?>">
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
                            <input type="password" name="currentPassword" placeholder="Current Password" class="form__input" require>
                            <input type="password" name="newPassword" placeholder="New Password" class="form__input">
                            <input type="password" name="confirmNewPassword" placeholder="Confirm New Password" class="form__input">
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

<!--JS -->
<script src="js/eventscript.js"></script>
<!--sidebar functionality-->
<script src="js/sidebar.js"></script>
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
        reader.onload = function() {
            var output = document.getElementById('profilePreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>
