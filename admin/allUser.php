<?php
    include('../function/F.allUser.php');

    // Fetch distinct years from the Events table
    $yearQuery = "SELECT DISTINCT YEAR(date_start) AS event_year FROM Events ORDER BY event_year DESC";
    $yearResult = mysqli_query($conn, $yearQuery);
    $years = mysqli_fetch_all($yearResult, MYSQLI_ASSOC);


    // Fetch total events
    $totalEventsQuery = "SELECT COUNT(*) AS totalEvents FROM Events";
    $totalEventsResult = mysqli_query($conn, $totalEventsQuery);
    $totalEvents = mysqli_fetch_assoc($totalEventsResult)['totalEvents'];

    // Fetch total upcoming events (excluding cancelled events)
    $totalUpcomingQuery = "SELECT COUNT(*) AS totalUpcoming FROM Events WHERE (NOW() < CONCAT(date_start, ' ', time_start)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalUpcomingResult = mysqli_query($conn, $totalUpcomingQuery);
    $totalUpcoming = mysqli_fetch_assoc($totalUpcomingResult)['totalUpcoming'];

    // Fetch total ongoing events (excluding cancelled events)
    $totalOngoingQuery = "SELECT COUNT(*) AS totalOngoing FROM Events WHERE (NOW() BETWEEN CONCAT(date_start, ' ', time_start) AND CONCAT(date_end, ' ', time_end)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalOngoingResult = mysqli_query($conn, $totalOngoingQuery);
    $totalOngoing = mysqli_fetch_assoc($totalOngoingResult)['totalOngoing'];

    // Fetch total ended events (excluding cancelled events)
    $totalEndedQuery = "SELECT COUNT(*) AS totalEnded FROM Events WHERE (NOW() > CONCAT(date_end, ' ', time_end)) AND (event_cancel IS NULL OR event_cancel = '')";
    $totalEndedResult = mysqli_query($conn, $totalEndedQuery);
    $totalEnded = mysqli_fetch_assoc($totalEndedResult)['totalEnded'];
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
        <div class="tables container">
            <div class="table_header">
                <p>All Users</p>
                <div>
                    <input class="tb_input" placeholder="Search..." oninput="filterTable()">
                    <button class="add_new"> <?php echo $totalUsersCount; ?> Users</button>
                </div>
            </div>
            <div class="table_section">
                <table id="userTable" class="tb_eco">
                    <thead class="tb_head">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Affiliation</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td><?php echo $user['UserID']; ?></td>
                                <td><?php echo $user['FirstName'] . ' ' . $user['LastName']; ?></td>
                                <td><?php echo $user['Email']; ?></td>
                                <td><?php echo $user['Affiliation']; ?></td>
                                <!-- <td>
                                    <button class="action-button" data-userid="<?php echo $user['UserID']; ?>"><i class="fa-solid fa-plus"></i></button>
                                </td> -->

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><tbody>
                
</div>
        


        <!--FILTER TABLE ROW-->
        <script>
            function filterTable() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.querySelector('.tb_input');
                filter = input.value.toUpperCase();
                console.log("Filter text:", filter); 

                table = document.getElementById("userTable");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1]; 
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        console.log("Text content:", txtValue);
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }

        </script>

   

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
                    th.style.left = -${scrollLeft}px;
                }
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const actionButtons = document.querySelectorAll('.action-button');

        actionButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.userid; // Get the user ID from the button's data attribute
                const eventId = <?php echo $eventId; ?>; // Get the event ID from PHP variable

                // Send the user ID and event ID to the server
                fetch('addParticipant.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        userId: userId,
                        eventId: eventId,
                    }),
                })
                .then(response => {
                    if (response.ok) {
                        // Reload the page after successful addition of participant
                        window.location.reload();
                    } else {
                        console.error('Failed to add participant');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>


</html>