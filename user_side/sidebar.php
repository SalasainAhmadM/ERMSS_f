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
            <a href="userDashboard.php">
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