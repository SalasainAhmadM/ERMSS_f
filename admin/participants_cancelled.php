<?php
session_start();
require_once('../db.connection/connection.php');
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

$pendingEventsCount = countPendingEvents($conn);
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
    }
}

$eventTitle = isset($_GET['eventTitle']) ? urldecode($_GET['eventTitle']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['eventTitle'])) {
        $eventTitle = urldecode($_GET['eventTitle']);

        // Fetch the event_id from the event title
        $sqlEvent = "SELECT event_id FROM events WHERE event_title = ?";
        $stmtEvent = $conn->prepare($sqlEvent);
        $stmtEvent->bind_param("s", $eventTitle);
        $stmtEvent->execute();
        $resultEvent = $stmtEvent->get_result();

        if ($resultEvent->num_rows > 0) {
            $event = $resultEvent->fetch_assoc();
            $event_id = $event['event_id'];

            // Fetch cancelled participants with their cancel_reason
            $sqlCancelledParticipants = "
            SELECT ep.UserID, u.FirstName, u.LastName, u.Age, u.Gender, u.Position, u.ContactNo, 
                   u.Email, u.Affiliation, u.EducationalAttainment, u.Image, cr.description AS cancelReason
            FROM eventparticipants ep
            JOIN user u ON ep.UserID = u.UserID
            JOIN cancel_reason cr ON ep.event_id = cr.event_id AND ep.UserID = cr.UserID
            WHERE ep.event_id = ?";


            $stmtCancelledParticipants = $conn->prepare($sqlCancelledParticipants);
            $stmtCancelledParticipants->bind_param("i", $event_id);
            $stmtCancelledParticipants->execute();
            $cancelledParticipantsResult = $stmtCancelledParticipants->get_result();
        } else {
            echo "Event not found.";
        }

        $stmtEvent->close();
        $stmtCancelledParticipants->close();
    }
}

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <link rel="stylesheet" href="css/main.css">


</head>

<body>


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
                        <!-- <a href="accountSettings.php">Account Settings</a> -->
                        <a href="allUser.php">All Users</a>
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
            <!-- <h3 class="dashboard apply">Day 1</h3> -->

            <style>
                .download-button {
                    background-color: #ffd60a;
                }

                .download-button:hover {
                    background-color: #1d3557;
                }
            </style>
            <div class="tables container">
                <div class="table_header">
                    <p>
                        <?php echo isset($eventTitle) ? htmlspecialchars($eventTitle) . ' Cancelled Participants' : 'Event Title Here'; ?>


                    </p>
                    <div>
                        <input class="tb_input" placeholder="Search..." oninput="filterTable()">
                        <!-- <a
                            href="event_participants.php?eventTitle=<?php echo urlencode($_SESSION['event_data']['eventTitle']); ?>"><button
                                class="add_new">View Attendance</button></a> -->
                        <!-- <button class="add_new"> <span><?php echo $totalParticipants; ?></span> Participants</button> -->
                        <!-- <select class="add_new" name="event_day" id="event_day">
                                <?php for ($i = 1; $i <= $numDays; $i++): ?>
                                    <option value="<?php echo $i; ?>">Day <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select> -->
                    </div>
                </div>

                <div class="table_section">
                    <div class="table_section">
                        <table id="userTable" class="tb_eco">
                            <thead class="tb_head">
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Occupation</th>
                                    <th>Contact No.</th>
                                    <th>Cancel Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($cancelledParticipantsResult->num_rows > 0) {
                                    while ($row = $cancelledParticipantsResult->fetch_assoc()) {
                                        $fullName = htmlspecialchars($row['FirstName']) . ' ' . htmlspecialchars($row['LastName']);
                                        $age = htmlspecialchars($row['Age']);
                                        $gender = htmlspecialchars($row['Gender']);
                                        $position = htmlspecialchars($row['Position']);
                                        $contact = htmlspecialchars($row['ContactNo']);
                                        $cancelReason = htmlspecialchars($row['cancelReason']);
                                        $email = htmlspecialchars($row['Email']);
                                        $affiliation = htmlspecialchars($row['Affiliation']);
                                        $educationalAttainment = htmlspecialchars($row['EducationalAttainment']);
                                        $image = htmlspecialchars($row['Image']);

                                        // Add an onclick event to trigger the SweetAlert with the participant's data including cancel reason
                                        ?>
                                        <tr onclick="showProfile(
                        '<?php echo $fullName; ?>', 
                        '<?php echo $age; ?>', 
                        '<?php echo $gender; ?>', 
                        '<?php echo $email; ?>', 
                        '<?php echo $affiliation; ?>', 
                        '<?php echo $position; ?>', 
                        '<?php echo $image; ?>', 
                        '<?php echo $contact; ?>', 
                        '<?php echo $educationalAttainment; ?>',
                        '<?php echo $cancelReason; ?>')">
                                            <td><?php echo $fullName; ?></td>
                                            <td><?php echo $age; ?></td>
                                            <td><?php echo $gender; ?></td>
                                            <td><?php echo $position; ?></td>
                                            <td><?php echo $contact; ?></td>
                                            <td><?php echo $cancelReason; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' style='text-align: center;'>No cancelled participants found for this event.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        function showProfile(fullName, age, gender, email, affiliation, position, image, contact, educationalAttainment, cancelReason) {
            Swal.fire({
                title: 'Participant Profile',
                html: `
                <div style="text-align: left; padding:2.5rem;">
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <img src="${image ? '../assets/img/profilePhoto/' + image : '../assets/img/profile.jpg'}" 
                            alt="Profile Image" 
                            style="width: 100px; height: 100px; border-radius: 50%;">
                    </div>

                    <strong><h3 style="margin-bottom: 0.25rem; margin-top: 4rem">Personal Info:</h3></strong>
                    <strong>Name:</strong> ${fullName}
                    <strong style="margin-left:5rem;">Age:</strong> ${age} <br/>
                    <strong>Gender:</strong> ${gender} <br/>
                    <strong>Educational Attainment:</strong> ${educationalAttainment} <br/>
                    <br/>

                    <strong><h3 style="margin-bottom: 0.25rem;">Contact Info:</h3></strong>
                    <strong>Email:</strong> ${email} <br/>
                    <strong>Contact:</strong> ${contact} <br/>
                    <br/>

                    <strong><h3 style="margin-bottom: 0.25rem;">Profile Details:</h3></strong>
                    <strong>Affiliation:</strong> ${affiliation} <br/>
                    <strong>Occupation:</strong> ${position} <br/>
                    <br/>

                    <strong><h3 style="margin-bottom: 0.25rem;">Cancel Reason:</h3></strong>
                    <span style="color: red;"><strong>${cancelReason}</strong></span> <!-- Red text for cancel reason -->
                </div>
            `,
                customClass: {
                    popup: 'larger-swal'
                },
                confirmButtonText: 'Close'
            });
        }
    </script>


    <!--FILTER TABLE ROW-->
    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.querySelector('.tb_input');
            filter = input.value.toUpperCase().trim(); // Trim leading and trailing spaces
            console.log("Filter text:", filter);

            table = document.getElementById("userTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Change index to [0] for the name column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    console.log("Text content:", txtValue);
                    // Check if the text content contains the filter text as a substring
                    if (txtValue.toUpperCase().includes(filter)) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>



    <!-- CONFIRM DELETE -->
    <script src=js/deleteEvent.js></script>


    <!--JS -->
    <script src="js/eventscript.js"></script>


    <!--sidebar functionality-->
    <script src="js/sidebar.js"></script>

    <!--chart js-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="js/myChart.js"></script>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tblWrapper = document.querySelector('.tbl-wrapper');
        const tblHead = document.querySelector('.tbl thead');

        tblWrapper.addEventListener('scroll', function () {
            const scrollLeft = tblWrapper.scrollLeft;
            const thElements = tblHead.getElementsByTagName('th');

            for (let th of thElements) {
                th.style.left = `-${scrollLeft}px`;
            }
        });
    });
</script>




<!--real-time update-->
<script src="js/realTimeUpdate.js"></script>

</html>