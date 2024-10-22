<?php

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