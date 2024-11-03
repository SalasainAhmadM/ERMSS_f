<?php
include('../function/F.editEvent2.php');
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
    .sponsorRow,
    .speakerRow {
        display: flex;
        gap: 3px;
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
    session_start();
    require_once('../db.connection/connection.php');

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


    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check if AdminID is set in the session
        if (isset($_SESSION['AdminID'])) {
            $AdminID = $_SESSION['AdminID'];

            // Prepare and execute a query to fetch the specific admin's data
            $sqlAdmin = "SELECT * FROM admin WHERE AdminID = ?";
            $stmtAdmin = $conn->prepare($sqlAdmin);
            $stmtAdmin->bind_param("i", $AdminID); // Assuming AdminID is an integer
            $stmtAdmin->execute();
            $resultAdmin = $stmtAdmin->get_result();

            if ($resultAdmin->num_rows > 0) {
                while ($row = $resultAdmin->fetch_assoc()) {
                    $LastName = $row['LastName'];
                    $FirstName = $row['FirstName'];
                    $MI = $row['MI'];
                    $Email = $row['Email'];
                    $ContactNo = $row['ContactNo'];
                    $Position = $row['Position']; // Corrected the column name
                    $Affiliation = $row['Affiliation'];
                    $Image = $row['Image'];

                    // Now, you have the specific admin's data
                }
            } else {
                echo "No records found";
            }

            $stmtAdmin->close();

            // Example usage of the countPendingUsers function
            $pendingUsersCount = countPendingUsers($conn);
            $pendingEventsCount = countPendingEvents($conn);

        }
    }


    ?>

    <!-- ====SIDEBAR==== -->
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
                <a href="#" onclick="confirmLogout(event)">
                    <i class="bx bx-log-out"></i>
                    <span class="nav-item">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>

            <script>
                function confirmLogout(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You will be logged out.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, logout'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../login.php";
                        }
                    });
                }
            </script>
        </ul>
    </div>

    <!-- ============ CONTENT ============-->
    <div class="main-content">
        <div class="containerr">
            <!-- <h3 class="dashboard">EVENTS</h3> -->


            <div class="wrapper">
                <div class="title">
                    Edit Event
                </div>
                <form method="POST" action="" enctype="multipart/form-data">


                    <div class="input_field">
                        <label>Event Title</label>
                        <input type="text" class="input" name="event_title" value="<?php echo $eventTitle; ?>" required>
                    </div>

                    <div class="input_field">
                        <label>Event Description</label>
                        <textarea class="textarea" name="event_description"><?php echo $eventDescription; ?></textarea>
                    </div>

                    <div class="input_field">
                        <label>Event Type</label>
                        <!-- <input type="text" class="input" name="event_type" value="<?php echo $eventType; ?>" required> -->
                        <div class="custom_select">
                            <select name="event_type" required>
                                <option value="">Select</option>
                                <option value="Training Sessions" <?php echo ($eventType === 'Training Sessions') ? 'selected' : ''; ?>>Training Sessions</option>
                                <option value="Specialized Seminars" <?php echo ($eventType === 'Specialized Seminars') ? 'selected' : ''; ?>>Specialized Seminars</option>
                                <option value="Cluster-specific gathering" <?php echo ($eventType === 'Cluster-specific gathering') ? 'selected' : ''; ?>>Cluster-specific gathering</option>
                                <option value="General Assembly" <?php echo ($eventType === 'General Assembly') ? 'selected' : ''; ?>>General Assembly</option>
                                <option value="Workshop" <?php echo ($eventType === 'Workshop') ? 'selected' : ''; ?>>
                                    Workshop</option>
                            </select>
                        </div>
                    </div>

                    <div class="input_field">
                        <label>Event Mode</label>
                        <div class="custom_select">
                            <select name="event_mode" required>
                                <option value="">Select</option>
                                <option value="Face-to-Face" <?php echo ($eventMode === 'Face-to-Face') ? 'selected' : ''; ?>>Face-to-Face</option>
                                <option value="Online" <?php echo ($eventMode === 'Online') ? 'selected' : ''; ?>>Online
                                </option>
                                <option value="Hybrid" <?php echo ($eventMode === 'Hybrid') ? 'selected' : ''; ?>>Hybrid
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="input_field" id="zoomLinkField">
                        <label>Event Link</label>
                        <input type="text" class="input" name="zoom_link" value="<?php echo $eventLink; ?>" required>
                    </div>


                    <div class="input_field">
                        <label for="photoUpload">Event Photo</label>
                        <input type="file" id="photoUpload" class="input" accept="image/*" name="event_photo">
                    </div>

                    <div class="input_field" id="participantLimitField">
                        <label>Participant Limit</label>
                        <input type="number" class="input" name="participant_limit"
                            value="<?php echo $eventDetails['participant_limit']; ?>">
                    </div>

                    <div class="input_field" id="locationField">
                        <label>Location</label>
                        <input type="text" class="input" name="location" value="<?php echo $eventLocation; ?>" required>
                    </div>

                    <div class="input_field">
                        <label>Date Start</label>
                        <input type="date" class="input" name="date_start" value="<?php echo $eventDateStart; ?>"
                            required>
                    </div>

                    <div class="input_field">
                        <label>Date End</label>
                        <input type="date" class="input" name="date_end" value="<?php echo $eventDateEnd; ?>" required>
                    </div>

                    <div class="input_field">
                        <label>Time Start</label>
                        <input type="time" class="input" name="time_start" value="<?php echo $eventTimeStart; ?>"
                            required>
                    </div>

                    <div class="input_field">
                        <label>Time End</label>
                        <input type="time" class="input" name="time_end" value="<?php echo $eventTimeEnd; ?>" required>
                    </div>

                    <div class="input_field">
                        <label>Speakers</label>
                        <button type="button" id="addSpeakerBtn" onclick="addSpeakerField()">Add Speaker</button>
                    </div>

                    <div class="speaker_fields_container">
                        <?php
                        $maxSpeakers = 5; // Limit to 5 Speaker fields
                        
                        // Loop through the number of Speakers and render input fields
                        for ($i = 0; $i < $maxSpeakers; $i++) {
                            // Check if there's a speaker at the current index
                            if (isset($speakers[$i])) {
                                $speakerFirstName = $speakers[$i]['speaker_firstName'];
                                $speakerMI = $speakers[$i]['speaker_MI'];
                                $speakerLastName = $speakers[$i]['speaker_lastName'];
                            } else {
                                $speakerFirstName = '';
                                $speakerMI = '';
                                $speakerLastName = '';
                            }

                            // Only show the field if there's data or if we're below the number of speakers
                            $displayStyle = ($i < count($speakers)) ? 'flex' : 'none';
                            ?>
                            <div class="input_field speaker_row" id="speakerField<?= $i + 1 ?>"
                                style="display: <?= $displayStyle ?>;">
                                <label>Speaker <?= $i + 1 ?></label>
                                <div class="speakerRow">
                                    <input type="text" class="input speaker_firstName" name="speaker<?= $i + 1 ?>_firstName"
                                        placeholder="First Name" value="<?= $speakerFirstName ?>">
                                    <input type="text" class="input speaker_MI" name="speaker<?= $i + 1 ?>_MI"
                                        placeholder="MI" value="<?= $speakerMI ?>">
                                    <input type="text" class="input speaker_lastName" name="speaker<?= $i + 1 ?>_lastName"
                                        placeholder="Last Name" value="<?= $speakerLastName ?>">
                                </div>
                                <i class="fas fa-trash-alt deleteSpeakerIcon" onclick="deleteSpeakerField(<?= $i + 1 ?>)"
                                    title="Delete Speaker"></i>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <script>let currentSpeakerCount = <?= count($speakers) ?>; // Set the initial speaker count
                        const maxSpeakers = 5; // Maximum number of speaker fields

                        function addSpeakerField() {
                            if (currentSpeakerCount < maxSpeakers) {
                                currentSpeakerCount++;
                                const speakerField = document.getElementById('speakerField' + currentSpeakerCount);
                                speakerField.style.display = 'flex'; // Show the next hidden speaker field
                            }
                            if (currentSpeakerCount >= maxSpeakers) {
                                document.getElementById('addSpeakerBtn').style.display = 'none'; // Hide 'Add Speaker' button when limit reached
                            }
                        }

                        function deleteSpeakerField(index) {
                            const speakerField = document.getElementById('speakerField' + index);
                            speakerField.style.display = 'none'; // Hide the selected speaker field
                            speakerField.querySelectorAll('input').forEach(input => input.value = ''); // Clear the input values

                            currentSpeakerCount--;
                            if (currentSpeakerCount < maxSpeakers) {
                                document.getElementById('addSpeakerBtn').style.display = 'inline-block'; // Show 'Add Speaker' button
                            }
                        }
                    </script>
                    <div class="input_field">
                        <label>Sponsors</label>
                        <button type="button" id="addSponsorBtn" onclick="addSponsorField()">Add Sponsor</button>
                    </div>

                    <div class="sponsor_fields_container">
                        <?php
                        $maxSponsors = 5; // Limit to 5 sponsor fields
                        
                        // Loop through the number of sponsors and render input fields
                        for ($i = 0; $i < $maxSponsors; $i++) {
                            // Check if there's a sponsor at the current index
                            if (isset($sponsors[$i])) {
                                $sponsorName = $sponsors[$i]['sponsor_Name'];
                                // $sponsorFirstName = $sponsors[$i]['sponsor_firstName'];
                                // $sponsorMI = $sponsors[$i]['sponsor_MI'];
                                // $sponsorLastName = $sponsors[$i]['sponsor_lastName'];
                            } else {
                                $sponsorName = '';
                                // $sponsorFirstName = '';
                                // $sponsorMI = '';
                                // $sponsorLastName = '';
                            }

                            // Only show the field if there's data or if we're below the number of sponsors
                            $displayStyle = ($i < count($sponsors)) ? 'flex' : 'none';
                            ?>
                            <div class="input_field sponsor_row" id="sponsorField<?= $i + 1 ?>"
                                style="display: <?= $displayStyle ?>;">
                                <label>Sponsor <?= $i + 1 ?></label>
                                <input type="text" class="input sponsor_Name" name="sponsor<?= $i + 1 ?>_Name"
                                    placeholder="Sponsor Name" value="<?= $sponsorName ?>">
                                <!-- <div class="sponsorRow">
                                    <input type="text" class="input sponsor_firstName" name="sponsor<?= $i + 1 ?>_firstName"
                                        placeholder="First Name" value="<?= $sponsorFirstName ?>">
                                    <input type="text" class="input sponsor_MI" name="sponsor<?= $i + 1 ?>_MI"
                                        placeholder="MI" value="<?= $sponsorMI ?>">
                                    <input type="text" class="input sponsor_lastName" name="sponsor<?= $i + 1 ?>_lastName"
                                        placeholder="Last Name" value="<?= $sponsorLastName ?>">
                                </div> -->
                                <i class="fas fa-trash-alt deleteSponsorIcon" onclick="deleteSponsorField(<?= $i + 1 ?>)"
                                    title="Delete Sponsor"></i>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <script>let currentSponsorCount = <?= count($sponsors) ?>; // Set the initial sponsor count
                        const maxSponsors = 5; // Maximum number of sponsor fields

                        function addSponsorField() {
                            if (currentSponsorCount < maxSponsors) {
                                currentSponsorCount++;
                                const sponsorField = document.getElementById('sponsorField' + currentSponsorCount);
                                sponsorField.style.display = 'flex'; // Show the next hidden sponsor field
                            }
                            if (currentSponsorCount >= maxSponsors) {
                                document.getElementById('addSponsorBtn').style.display = 'none'; // Hide 'Add Sponsor' button when limit reached
                            }
                        }

                        function deleteSponsorField(index) {
                            const sponsorField = document.getElementById('sponsorField' + index);
                            sponsorField.style.display = 'none'; // Hide the selected sponsor field
                            sponsorField.querySelectorAll('input').forEach(input => input.value = ''); // Clear the input values

                            currentSponsorCount--;
                            if (currentSponsorCount < maxSponsors) {
                                document.getElementById('addSponsorBtn').style.display = 'inline-block'; // Show 'Add Sponsor' button
                            }
                        }
                    </script>

                    <div class="input_field">
                        <input type="submit" value="Save" class="createBtn" id="saveEventButton">
                        <!-- <input type="button" value="Cancel Event" class="createBtn cancel" id="cancelEventButton"> -->
                    </div>

                </form>
            </div>
        </div>
    </div>




    <!--CONFIRMATION===========-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function confirmSaveChanges(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Save Changes?',
                    text: 'Are you sure you want to save the changes to this event?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!',
                    cancelButtonText: 'No, cancel',
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

            function confirmCancelEvent(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Cancel Event?',
                    text: 'Are you sure you want to cancel this event?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'No, keep it',
                    padding: '3rem',
                    customClass: {
                        popup: 'larger-swal'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'landingPage.php';
                    }
                });
            }

            document.querySelector('form').addEventListener('submit', confirmSaveChanges);

            document.getElementById('cancelEventButton').addEventListener('click', confirmCancelEvent);
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
                var eventModeSelect = document.querySelector("select[name='event_mode']");
                var zoomLinkField = document.getElementById("zoomLinkField");

                // Show the "Zoom Link" input field only when "Hybrid" or "Online" is selected
                zoomLinkField.style.display = (eventModeSelect.value === "Hybrid" || eventModeSelect.value === "Online") ? "block" : "none";

                // Set required attribute based on selection
                document.querySelector("input[name='zoom_link']").required = (eventModeSelect.value === "Hybrid" || eventModeSelect.value === "Online");
            }

            // Function to toggle Location field visibility
            function toggleLocationField() {
                var eventModeSelect = document.querySelector("select[name='event_mode']");
                var locationField = document.getElementById("locationField");

                // Show the "Location" input field only when "Face-to-Face" or "Hybrid" is selected
                locationField.style.display = (eventModeSelect.value === "Face-to-Face" || eventModeSelect.value === "Hybrid") ? "block" : "none";

                // Set required attribute based on selection
                document.querySelector("input[name='location']").required = (eventModeSelect.value === "Face-to-Face" || eventModeSelect.value === "Hybrid");
            }

            // Attach the functions to the input event of the Event Mode select
            document.querySelector("select[name='event_mode']").addEventListener("input", function () {
                toggleZoomLinkField();
                toggleLocationField();
            });

            // Trigger the initial state check
            toggleZoomLinkField();
            toggleLocationField();


            // Function to handle cancel event button click
            document.getElementById('cancelEventButton').addEventListener('click', function () {
                // Toggle visibility of the cancel event field
                var cancelEventField = document.getElementById('cancelEventField');
                cancelEventField.style.display = (cancelEventField.style.display === 'none') ? 'block' : 'none';

                // Check if the field is being displayed and add/remove the required attribute accordingly
                var eventCancelInput = document.querySelector("input[name='event_cancel']");
                if (cancelEventField.style.display === 'block') {
                    eventCancelInput.required = true;


                } else {
                    eventCancelInput.required = false;


                }
            });
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