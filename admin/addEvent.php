<?php
    include('../function/F.addEvent.php');
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
                <!-- ang photo dapat query sa actual not path-->
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
                            <a href="allUser.php">All Users</a>
                            <!-- <a href="accountSettings.php">Account Settings</a> -->
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
                        Create New Event
                    </div>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="input_field">
                            <label>Event Title</label>
                            <input type="text" class="input" name="event_title" required>
                        </div>

                        <div class="input_field">
                            <label>Event Description</label>
                            <textarea class="textarea" name="event_description"></textarea>
                        </div>

                        <div class="input_field">
                            <label>Event Type</label>
                            <!-- <input type="text" class="input" name="event_type" required> -->
                            <div class="custom_select">
                                <select name="event_type" required>
                                    <option value="">Select</option>
                                    <option value="Training Sessions">Training Sessions</option>
                                    <option value="Specialized Seminars">Specialized Seminars</option>
                                    <option value="Cluster-specific gathering">Cluster-specific gathering</option>
                                    <option value="General Assembly">General Assembly</option>
                                    <option value="Workshop">Workshop</option>
                                </select>
                            </div>
                        </div>  

                        <div class="input_field">
                            <label>Event Mode</label>
                            <div class="custom_select">
                                <select name="event_mode" id="eventModeSelect" required onchange="toggleZoomLinkField()">
                                    <option value="">Select</option>
                                    <option value="Face-to-Face">Face-to-Face</option>
                                    <option value="Online">Online</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                        </div>

                        <div class="input_field" id="zoomLinkField">
                            <label>Event Link</label>
                            <input type="text" class="input" name="zoom_link">
                        </div>

                        <div class="input_field">
                            <label for="photoUpload">Event Photo</label>
                            <input type="file" id="photoUpload" class="input" accept="image/*" name="event_photo">
                        </div>

                        <div class="input_field" id="participantLimitField">
                            <label>Participant Limit</label>
                            <input type="number" class="input" name="participant_limit" required>
                        </div>

                        <div class="input_field" id="locationField">
                            <label>Location</label>
                            <input type="text" class="input" name="location" required>
                        </div>

                        <div class="input_field">
                            <label>Date Start</label>
                            <input type="date" class="input" name="date_start" required>
                        </div>

                        <div class="input_field">
                            <label>Date End</label>
                            <input type="date" class="input" name="date_end" required>
                        </div>

                        <div class="input_field">
                            <label>Time Start</label>
                            <input type="time" class="input" name="time_start" id="timeStart" required>
                        </div>

                        <div class="input_field">
                            <label>Time End</label>
                            <input type="time" class="input" name="time_end" id="timeEnd" required>
                        </div>  

                        <div class="input_field">
                            <input type="submit" value="Create" class="createBtn">
                        </div>

                    </form>
                </div>
            </div>            
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Function to set default time for Time Start and Time End
                function setDefaultTime() {
                    var timeStartInput = document.getElementById('timeStart');
                    var timeEndInput = document.getElementById('timeEnd');

                    // Set default values to 8 AM and 5 PM respectively
                    timeStartInput.value = '08:00';
                    timeEndInput.value = '17:00';
                }

                // Call the function to set default time when the page loads
                setDefaultTime();
            });
        </script>
        


        <!--JS -->
        <script src="js/eventscript.js"></script>


        <!--sidebar functionality-->
        <script src="js/sidebar.js"></script>

        <script>
            
            document.addEventListener('DOMContentLoaded', function () {
                // Initial check on page load
                toggleZoomLinkField();
                toggleLocationField();

                // Function to toggle Zoom Link field visibility
                function toggleZoomLinkField() {
                    var eventModeSelect = document.getElementById("eventModeSelect");
                    var zoomLinkField = document.getElementById("zoomLinkField");

                    // Show the "Zoom Link" input field only when "Hybrid" or "Online" is selected
                    zoomLinkField.style.display = (eventModeSelect.value === "Hybrid" || eventModeSelect.value === "Online") ? "block" : "none";
                }

                // Function to toggle Location field visibility and required status
                function toggleLocationField() {
                    var eventModeSelect = document.getElementById("eventModeSelect");
                    var locationField = document.getElementById("locationField");

                    // Show the "Location" input field only when "Hybrid" or "Face-to-Face" is selected
                    locationField.style.display = (eventModeSelect.value === "Hybrid" || eventModeSelect.value === "Face-to-Face") ? "block" : "none";

                    // Set the "required" attribute based on the selected event mode
                    locationField.querySelector("input").required = (eventModeSelect.value === "Hybrid" || eventModeSelect.value === "Face-to-Face");
                }

                // Attach the functions to the change event of the Event Mode select
                document.getElementById("eventModeSelect").addEventListener("change", function() {
                    toggleZoomLinkField();
                    toggleLocationField();
                });
            });

        </script>


        <!--CONFIRMATION===========-->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Function to display confirmation dialog when saving changes
                function confirmSaveChanges(event) {
                    // Prevent the default form submission behavior
                    event.preventDefault();

                    // Display confirmation dialog
                    var confirmation = confirm("Create Event?");
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
        </script>


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