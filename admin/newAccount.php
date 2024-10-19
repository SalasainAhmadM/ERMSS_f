<?php
include('../function/F.newAccount.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>

    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

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
                            <a href="landingPage.php">Events</a>
                            <a href="addEvent.php">Add Event</a>
                            <a href="history.php">History</a>
                            <a href="cancelEvent.php">Cancelled</a>
                        </ul>
                    </div>
                </li>

        <li class="events-side nav-sidebar">
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

    <!-- ============ CONTENT ============-->
    <div class="main-content">
        <div class="containerr">
            <!-- <h3 class="dashboard">EVENTS</h3> -->
            

            <div class="wrapper">
                <div class="title">
                    Create New Account
                </div>
                <form action="../function/F.newAccount.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="AdminID" value="<?php echo $AdminID; ?>">
                


                    <div class="input_field">
                        <label>Email</label>
                        <input type="email" name="Email" class="input" required>
                    </div>
                    
                    <div class="input_field">
                        <label>Password</label>
                        <input type="password" name="Password" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>Confirm Password</label>
                        <input type="password" name="ConfirmPassword" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>Last Name</label>
                        <input type="text" name="LastName" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>First Name</label>
                        <input type="text" name="FirstName" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>Middle Initial</label>
                        <input type="text" name="MI" class="input">
                    </div>

                    <div class="input_field">
                        <label>Gender</label>
                        <select id="genderSelect" name="Gender" class="input" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>


                    <div class="input_field">
                        <label for="photoUpload">Insert Photo</label>
                        <input type="file"  name="Image" id="Image" class="input" accept="image/*">
                    </div>

                    <div class="input_field">
                        <label>Contact Number</label>
                        <input type="number"  name="ContactNo" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>Address</label>
                        <input type="text" name="Address" class="input" required>
                    </div>


                    <div class="input_field">
                        <label>Affiliation</label>
                        <input type="Affiliation" name="Affiliation" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>Position</label>
                        <input type="text"  name="Position" class="input" required>
                    </div>

                    <div class="input_field">
                        <label>User Type</label>
                        <div class="custom_select">
                        
                            <input type="hidden" name="Role" value="<?php echo $Role; ?>">
                            
                            <select name="Role" required>
                                <option value="">Select</option>
                                <?php if ($Role === 'SuperAdmin'): ?>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                    <option value="SuperAdmin">Director</option>
                                    <?php else: ?>
                                    
                                    <option value="User">User</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                                    
                
                    <div class="input_field">
                        <input type="submit" name="submit" value="Create" class="createBtn">
                    </div>

                </form>
            </div>


        </div>

        <!--back button-->
        <!-- <section class="category">
            <div class="box-container">
              <a href="landingPage.php" class="box">
                <i class="fa-solid fa-arrow-left"></i>
                <div>
                  <h3>Go Back</h3>
                  <span>Click to go back</span>
                </div>
              </a>
      
            </div>
          </section> -->


        
    </div>


    


    <!--JS -->
    <script src="js/eventscript.js"></script>


    <!--sidebar functionality-->
    <script src="js/sidebar.js"></script>

    <!--CONFIRMATION===========-->
    
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to display confirmation dialog when saving changes
            function confirmSaveChanges(event) {
                // Prevent the default form submission behavior
                event.preventDefault();

                // Display confirmation dialog
                var confirmation = confirm("Confirm Create?");
                if (confirmation) {
                    // If user confirms, submit the form
                    event.target.submit();
                } else {
                    // If user cancels, do nothing
                    return false;
                }
            }

            // Attach the confirmation function to the form submit event
            document.querySelector('form').addEventListener('submit', confirmSaveChanges);
        });
    </script> -->


    <script>

        let dropdown_items = document.querySelectorAll('.job-filter form .dropdown-container .dropdown .lists .items');

        dropdown_items.forEach(items =>{
            items.onclick = () =>{
                items_parent = items.parentElement.parentElement;
                let output = items_parent.querySelector('.output');
                output.value = items.innerText;
            }
        });

    </script>
</body>


</html>