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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">



    <link rel="stylesheet" href="css/main.css">
</head>
<style>
    /* .sponsorRow, */
    .speakerRow {
        display: flex;
        gap: 3px;
    }

    .sponsor_Row {
        width: 100%;
    }

    .sponsor_firstName,
    .sponsor_lastName,
    .speaker_firstName,
    .speaker_lastName {
        width: 45%;
    }

    .sponsor_MI,
    .speaker_MI {
        width: 8%;
    }

    .deleteSponsorIcon,
    .deleteSpeakerIcon {
        color: #d9534f;
        cursor: pointer;
        margin-left: 10px;
        font-size: 20px;
    }

    .deleteSponsorIcon:hover,
    .deleteSpeakerIcon:hover {
        color: #c9302c;
    }
</style>

<body>
    <?php
    if (isset($_SESSION['success'])) {
        echo "<script>
        Swal.fire({
        title: 'Success!',
        text: '" . $_SESSION['success'] . "',
        icon: 'success',
        customClass: {
        popup: 'larger-swal' 
        }  
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'landingPage.php';
      }
    });
    </script>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['success2'])) {
        echo "<script>
        Swal.fire({
        title: 'Success!',
        text: '" . $_SESSION['success2'] . "',
        icon: 'success',
        customClass: {
        popup: 'larger-swal' 
        }  
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'pendingEvents.php';
      }
    });
    </script>";
        unset($_SESSION['success2']);
    }
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

            <li class="events-side2 nav-sidebar">
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
                        <a href="validation.php">User Validation <span><?php echo $pendingUsersCount; ?></span></a>
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
            <h3 class="dashboard">ADD EVENTS</h3>


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
                                <?php
                                $query = "SELECT event_type_id, event_type_name FROM event_type";
                                $result = mysqli_query($conn, $query);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['event_type_name'] . '">' . $row['event_type_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No event types found</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="input_field">
                        <label>Event Mode</label>
                        <div class="custom_select">
                            <select name="event_mode" id="eventModeSelect" required onchange="toggleZoomLinkField()">
                                <option value="">Select</option>
                                <?php
                                $query = "SELECT event_mode_id, event_mode_name FROM event_mode";
                                $result = mysqli_query($conn, $query);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['event_mode_name'] . '">' . $row['event_mode_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No event modes found</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="input_field">
                        <label>Audience Type</label>
                        <!-- <input type="text" class="input" name="audience_type" required> -->
                        <div class="custom_select">
                            <select name="audience_type" required>
                                <option value="">Select</option>
                                <?php
                                $query = "SELECT audience_type_id, audience_type_name FROM audience_type";
                                $result = mysqli_query($conn, $query);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['audience_type_name'] . '">' . $row['audience_type_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No audience types found</option>';
                                }
                                ?>
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
                        <input type="date" class="input" name="date_start" id="date_start" required>
                    </div>
                    <div class="input_field">
                        <label>Date End</label>
                        <input type="date" class="input" name="date_end" id="date_end" required>
                    </div>

                    <script>
                        const dateStart = document.getElementById('date_start');
                        const dateEnd = document.getElementById('date_end');

                        // Set the start date to tomorrow
                        const today = new Date();
                        today.setDate(today.getDate() + 1);
                        const tomorrow = today.toISOString().split('T')[0];

                        dateStart.min = tomorrow;
                        dateEnd.min = tomorrow;

                        dateStart.addEventListener('change', function () {
                            const startDate = this.value;
                            dateEnd.min = startDate;
                            if (dateEnd.value < startDate) {
                                dateEnd.value = '';
                            }
                        });
                    </script>



                    <div class="input_field">
                        <label>Time Start</label>
                        <input type="time" class="input" name="time_start" id="timeStart" required>
                    </div>

                    <div class="input_field">
                        <label>Time End</label>
                        <input type="time" class="input" name="time_end" id="timeEnd" required>
                    </div>
                    <!-- Add Speaker Section -->
                    <div class="input_field">
                        <label>Speakers</label>
                        <button type="button" class="addSpeakerBtn" id="addSpeakerBtn" onclick="addSpeakerField()">Add
                            Speaker</button>
                    </div>

                    <!-- Speaker Fields (Initially Hidden) -->
                    <div class="speaker_fields_container">
                        <div class="input_field" id="speakerField1" style="display:none;">
                            <label>Speaker 1</label>
                            <div class="speakerRow">
                                <input type="text" class="input speaker_firstName" name="speaker1_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input speaker_MI" name="speaker1_MI" placeholder="MI">
                                <input type="text" class="input speaker_lastName" name="speaker1_lastName"
                                    placeholder="Last Name">
                                <i class="fas fa-trash-alt deleteSpeakerIcon" onclick="deleteSpeakerField(1)"
                                    title="Delete Speaker"></i>
                            </div>
                        </div>

                        <!-- Repeat the above structure for speakers 2 to 5 -->
                        <div class="input_field" id="speakerField2" style="display:none;">
                            <label>Speaker 2</label>
                            <div class="speakerRow">
                                <input type="text" class="input speaker_firstName" name="speaker2_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input speaker_MI" name="speaker2_MI" placeholder="MI">
                                <input type="text" class="input speaker_lastName" name="speaker2_lastName"
                                    placeholder="Last Name">
                                <i class="fas fa-trash-alt deleteSpeakerIcon" onclick="deleteSpeakerField(2)"
                                    title="Delete Speaker"></i>
                            </div>
                        </div>

                        <div class="input_field" id="speakerField3" style="display:none;">
                            <label>Speaker 3</label>
                            <div class="speakerRow">
                                <input type="text" class="input speaker_firstName" name="speaker3_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input speaker_MI" name="speaker3_MI" placeholder="MI">
                                <input type="text" class="input speaker_lastName" name="speaker3_lastName"
                                    placeholder="Last Name">
                                <i class="fas fa-trash-alt deleteSpeakerIcon" onclick="deleteSpeakerField(3)"
                                    title="Delete Speaker"></i>
                            </div>
                        </div>

                        <div class="input_field" id="speakerField4" style="display:none;">
                            <label>Speaker 4</label>
                            <div class="speakerRow">
                                <input type="text" class="input speaker_firstName" name="speaker4_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input speaker_MI" name="speaker4_MI" placeholder="MI">
                                <input type="text" class="input speaker_lastName" name="speaker4_lastName"
                                    placeholder="Last Name">
                                <i class="fas fa-trash-alt deleteSpeakerIcon" onclick="deleteSpeakerField(4)"
                                    title="Delete Speaker"></i>
                            </div>
                        </div>

                        <div class="input_field" id="speakerField5" style="display:none;">
                            <label>Speaker 5</label>
                            <div class="speakerRow">
                                <input type="text" class="input speaker_firstName" name="speaker5_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input speaker_MI" name="speaker5_MI" placeholder="MI">
                                <input type="text" class="input speaker_lastName" name="speaker5_lastName"
                                    placeholder="Last Name">
                                <i class="fas fa-trash-alt deleteSpeakerIcon" onclick="deleteSpeakerField(5)"
                                    title="Delete Speaker"></i>
                            </div>
                        </div>
                    </div>


                    <div class="input_field">
                        <label>Sponsors</label>
                        <button type="button" class="addSponsorBtn" id="addSponsorBtn" onclick="addSponsorField()">Add
                            Sponsor</button>
                    </div>

                    <!-- Sponsor Fields (Initially Hidden) -->
                    <div class="sponsor_fields_container">
                        <div class="input_field" id="sponsorField1" style="display:none;">
                            <label>Sponsor 1</label>
                            <input type="text" class="input sponsor_Name" name="sponsor1_Name"
                                placeholder="Sponsor Name">
                            <i class="fas fa-trash-alt deleteSponsorIcon" onclick="deleteSponsorField(1)"
                                title="Delete Sponsor"></i>
                            <!-- <div class="sponsorRow">

                                <input type="text" class="input sponsor_firstName" name="sponsor1_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input sponsor_MI" name="sponsor1_MI" placeholder="MI">
                                <input type="text" class="input sponsor_lastName" name="sponsor1_lastName"
                                    placeholder="Last Name">

                              
                            </div> -->
                        </div>

                        <div class="input_field" id="sponsorField2" style="display:none;">
                            <label>Sponsor 2</label>
                            <input type="text" class="input sponsor_Name" name="sponsor2_Name"
                                placeholder="Sponsor Name">
                            <i class="fas fa-trash-alt deleteSponsorIcon" onclick="deleteSponsorField(2)"
                                title="Delete Sponsor"></i>
                            <!-- <div class="sponsorRow">

                                <input type="text" class="input sponsor_firstName" name="sponsor2_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input sponsor_MI" name="sponsor2_MI" placeholder="MI">
                                <input type="text" class="input sponsor_lastName" name="sponsor2_lastName"
                                    placeholder="Last Name">
                               
                            </div> -->
                        </div>

                        <div class="input_field" id="sponsorField3" style="display:none;">
                            <label>Sponsor 3</label>
                            <input type="text" class="input sponsor_Name" name="sponsor3_Name"
                                placeholder="Sponsor Name">
                            <i class="fas fa-trash-alt deleteSponsorIcon" onclick="deleteSponsorField(3)"
                                title="Delete Sponsor"></i>
                            <!-- <div class="sponsorRow">

                                <input type="text" class="input sponsor_firstName" name="sponsor3_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input sponsor_MI" name="sponsor3_MI" placeholder="MI">
                                <input type="text" class="input sponsor_lastName" name="sponsor3_lastName"
                                    placeholder="Last Name">
                                
                            </div> -->
                        </div>

                        <div class="input_field" id="sponsorField4" style="display:none;">
                            <label>Sponsor 4</label>
                            <input type="text" class="input sponsor_Name" name="sponsor4_Name"
                                placeholder="Sponsor Name">
                            <i class="fas fa-trash-alt deleteSponsorIcon" onclick="deleteSponsorField(4)"
                                title="Delete Sponsor"></i>
                            <!-- <div class="sponsorRow">

                                <input type="text" class="input sponsor_firstName" name="sponsor4_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input sponsor_MI" name="sponsor4_MI" placeholder="MI">
                                <input type="text" class="input sponsor_lastName" name="sponsor4_lastName"
                                    placeholder="Last Name">
                                
                            </div> -->
                        </div>

                        <div class="input_field" id="sponsorField5" style="display:none;">
                            <label>Sponsor 5</label>
                            <input type="text" class="input sponsor_Name" name="sponsor5_Name"
                                placeholder="Sponsor Name">
                            <i class="fas fa-trash-alt deleteSponsorIcon" onclick="deleteSponsorField(5)"
                                title="Delete Sponsor"></i>
                            <!-- <div class="sponsorRow">
                                <input type="text" class="input sponsor_firstName" name="sponsor5_firstName"
                                    placeholder="First Name">
                                <input type="text" class="input sponsor_MI" name="sponsor5_MI" placeholder="MI">
                                <input type="text" class="input sponsor_lastName" name="sponsor5_lastName"
                                    placeholder="Last Name">
                                
                            </div> -->
                        </div>
                    </div>

                    <script>
                        let currentSpeakerCount = 0;
                        const maxSpeakers = 5;

                        function addSpeakerField() {
                            if (currentSpeakerCount < maxSpeakers) {
                                currentSpeakerCount++;
                                document.getElementById('speakerField' + currentSpeakerCount).style.display = 'flex'; // Show the field
                            }
                            if (currentSpeakerCount >= maxSpeakers) {
                                document.getElementById('addSpeakerBtn').disabled = true; // Disable button after max speakers
                            }
                        }

                        function deleteSpeakerField(index) {
                            const speakerField = document.getElementById('speakerField' + index);
                            speakerField.style.display = 'none'; // Hide the selected field
                            speakerField.querySelectorAll('input').forEach(input => input.value = ''); // Clear input values

                            currentSpeakerCount--;
                            if (currentSpeakerCount < maxSpeakers) {
                                document.getElementById('addSpeakerBtn').disabled = false; // Enable 'Add Speaker' button again
                            }
                        }
                    </script>
                    <script>
                        let currentSponsorCount = 0;
                        const maxSponsors = 5;

                        function addSponsorField() {
                            if (currentSponsorCount < maxSponsors) {
                                currentSponsorCount++;
                                document.getElementById('sponsorField' + currentSponsorCount).style.display = 'flex';
                            }
                            if (currentSponsorCount >= maxSponsors) {
                                document.getElementById('addSponsorBtn').disabled = true;
                            }
                        }

                        function deleteSponsorField(index) {
                            const sponsorField = document.getElementById('sponsorField' + index);
                            sponsorField.style.display = 'none';
                            sponsorField.querySelectorAll('input').forEach(input => input.value = '');

                            currentSponsorCount--;
                            if (currentSponsorCount < maxSponsors) {
                                document.getElementById('addSponsorBtn').disabled = false;
                            }
                        }
                    </script>

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
            document.getElementById("eventModeSelect").addEventListener("change", function () {
                toggleZoomLinkField();
                toggleLocationField();
            });
        });

    </script>


    <!--CONFIRMATION===========-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>

        document.addEventListener('DOMContentLoaded', function () {
            function confirmSaveChanges(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Create Event?',
                    text: 'Are you sure you want to create this event?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    padding: '3rem',
                    customClass: {
                        popup: 'larger-swal'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                });
            }

            document.querySelector('form').addEventListener('submit', confirmSaveChanges);
        });
    </script>



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