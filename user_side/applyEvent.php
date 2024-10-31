<?php
include('../function/F.applyEventU.php');
include('../function/F.event_retrieve.php');

// Check if event_id is set in the POST data
$eventId = isset($_POST['event_id']) ? $_POST['event_id'] : null;

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect to login page or handle accordingly
    header("Location: ../login.php");
    exit();
}

// Retrieve user details from database
$userID = $_SESSION['UserID'];
$userData = getUserData($conn, $userID);

// Check if user data is retrieved successfully
if (!$userData) {
    // Handle error, redirect, or display a message
    echo "Error retrieving user data.";
    exit();
}

$FirstName = $userData['FirstName'];
$MI = $userData['MI'];
$LastName = $userData['LastName'];
$Email = $userData['Email'];
$ContactNo = $userData['ContactNo'];
$Affiliation = $userData['Affiliation'];
$Position = $userData['Position'];
$Image = $userData['Image'];


// Fetch total number of cancelled events
$countCancelledEventsSql = "SELECT COUNT(*) AS totalCancelledEvents FROM Events WHERE event_cancel IS NOT NULL AND event_cancel <> ''";
$countCancelledEventsResult = mysqli_query($conn, $countCancelledEventsSql);
$countCancelledEventsRow = mysqli_fetch_assoc($countCancelledEventsResult);
$totalCancelledEvents = $countCancelledEventsRow['totalCancelledEvents'];

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
                <a href="userDashboard.php  ">
                    <i class="bx bxs-grid-alt"></i>
                    <span class="nav-item">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>

            <li class="events-side first nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-archive'></i>
                    <span class="nav-item">Events</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Events</span>
                <div class="uno">
                    <ul>
                        <a href="landingPageU.php">Join Event</a>
                        <a href="history.php">History</a>
                        <a href="cancelEventU.php">Cancelled <span><?php echo $canceledEventsCount; ?></span></a>
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
        <div class="containerr apply">
            <!-- <h3 class="dashboard">EVENTS</h3> -->


            <div class="wrapper">
                <div class="title">
                    Join Event
                </div>
                <form action="F.applyEventU.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">

                    <div class="input_field">
                        <label>Email</label>
                        <input type="text" name="Email" class="input" value="<?php echo $Email; ?>">
                    </div>

                    <div class="input_field">
                        <label>Full Name</label>
                        <input type="text" name="FullName" class="input"
                            value="<?php echo $FirstName . ' ' . $MI . ' ' . $LastName; ?>">

                    </div>


                    <div class="input_field">
                        <label>Contact Number</label>
                        <input type="number" name="ContactNo" class="input" value="<?php echo $ContactNo; ?>">
                    </div>

                    <div class="input_field">
                        <label>Affiliation</label>
                        <input type="text" name="Affiliation" class="input" value="<?php echo $Affiliation; ?>">
                    </div>

                    <div class="input_field">
                        <label>Position</label>
                        <input type="text" name="Position" class="input" value="<?php echo $Position; ?>">
                    </div>



                    <div class="input_field">
                        <input type="submit" value="Join" class="createBtn">
                    </div>

                </form>
            </div>


        </div>

        <!--back button-->
        <section class="category">
            <div class="box-container">
                <a href="view_event.php" class="box">
                    <i class="fa-solid fa-arrow-left"></i>
                    <div>
                        <h3>Go Back</h3>
                        <span>Click to go back</span>
                    </div>
                </a>

            </div>
        </section>



    </div>





    <!--JS -->
    <script src="js/eventscript.js"></script>


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