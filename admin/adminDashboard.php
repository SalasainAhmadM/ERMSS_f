<?php
    // Include your database connection code here
    require_once('../db.connection/connection.php');

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

        <?php
            session_start();
            require_once('../db.connection/connection.php');

            function showProfileModal($message) {
                echo "<script>
                          showModal('$message');
                      </script>";
            }
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
            
            $pendingUsersCount = countPendingUsers($conn); 
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

            
            function getAdminData($conn, $AdminID) {
                $sql = "SELECT * FROM admin WHERE AdminID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $AdminID);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $stmt->close();
                    return $row;
                } else {
                    $stmt->close();
                    return false;
                }
            }
            
            $AdminID = $_SESSION['AdminID']; // Retrieve AdminID from the session
            $adminData = getAdminData($conn, $AdminID);
            
            if ($adminData) {
                // Retrieve existing admin data
                $LastName = $adminData['LastName'];
                $FirstName = $adminData['FirstName'];
                $MI = $adminData['MI'];
                $Gender = $adminData['Gender'];
                $Email = $adminData['Email'];
                $ContactNo = $adminData['ContactNo'];
                $Address = $adminData['Address'];
                $Affiliation = $adminData['Affiliation'];
                $Position = $adminData['Position'];
                $Image = isset($adminData['Image']) ? $adminData['Image'] : null;
            } else {
                // Redirect or handle error if admin data is not found
                showProfileModal("Admin data not found");
                exit();
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
                <h3 class="dashboard apply">EVENT MANAGEMENT SYSTEM</h3>

                <section class="category">

                    <div class="box-container">

                        <a href="total_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Total</h3>
                                <span><?php echo $totalEvents; ?> Events</span>
                            </div>
                        </a>

                        <a href="upcoming_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Upcoming</h3>
                                <span><?php echo $totalUpcoming; ?> Events</span>
                            </div>
                        </a>

                        <a href="ongoing_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Ongoing</h3>
                                <span><?php echo $totalOngoing; ?> Events</span>
                            </div>
                        </a>

                        <a href="ended_events.php" class="box">
                            <i class='bx bx-archive'></i>
                            <div>
                                <h3>Completed</h3>
                                <span><?php echo $totalEnded; ?> Events</span>
                            </div>
                        </a>
                    </div>
                </section>
                
                <!--charts-->
                <div class="graphBox">
                    <div class="box">
                        <div>
                            <select name="selectedYear" id="selectedYear">
                                <?php foreach ($years as $year): ?>
                                    <option value="<?php echo $year['event_year']; ?>"><?php echo $year['event_year']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <canvas id="myChart" width="400" height="300"></canvas> <!--change the size by adjusting the width and height-->
                        </div>
                    </div>
                    <div class="box">
                        <canvas id="eventsYear"></canvas>
                    </div>
                </div>

                

            </div>
        </div>



        




        <!-- CONFIRM DELETE -->
        <script src=js/deleteEvent.js></script>
            

        <!--JS -->
        <script src="js/eventscript.js"></script>


        <!--sidebar functionality-->
        <script src="js/sidebar.js"></script>

        <!--chart js-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <!-- <script src="js/myChart.js"></script> -->

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Existing code for bar chart
                var ctx = document.getElementById('myChart').getContext('2d');
                var yearly = document.getElementById('eventsYear').getContext('2d');

                var selectedYear = document.getElementById('selectedYear');
                var selectedYearValue = selectedYear.value;
                var myChart; // Declare myChart variable

                selectedYear.addEventListener('change', function () {
                    selectedYearValue = selectedYear.value;
                    updateCharts();
                });

                function updateCharts() {
                    // Fetch data for the bar chart (per month)
                    fetchBarChartData(selectedYearValue).then(function (data) {
                        updateBarChart(data);
                    });

                    // Fetch data for the line chart (per year)
                    fetchLineChartData().then(function (data) {
                        updateLineChart(data);
                    });
                }

                function fetchBarChartData(year) {
                    return fetch('../function/F.getMonthlyEvents.php?year=' + year)
                        .then(response => response.json())
                        .then(data => data.events);
                }

                function updateBarChart(data) {
                    // Remove the existing chart if it exists
                    if (myChart) {
                        myChart.destroy();
                    }

                    // Your existing Chart.js code for the bar chart
                    myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of events per month',
                                data: data.map(monthData => monthData.total_events),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                        }
                    });
                }

                function fetchLineChartData() {
                    return fetch('../function/F.getYearlyEvents.php')
                        .then(response => response.json())
                        .then(data => data.events);
                }

                function updateLineChart(data) {
                    // Your existing Chart.js code for the line chart
                    var myChart = new Chart(yearly, {
                        type: 'line',
                        data: {
                            labels: data.map(yearData => yearData.year),
                            datasets: [{
                                label: 'Total # of events per year',
                                data: data.map(yearData => yearData.total_events),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                        }
                    });
                }

                // Initial update on page load
                updateCharts();
            });

        </script>

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
