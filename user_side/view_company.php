<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>


    <!--browser icon-->
    <link rel="icon" href="img/wesmaarrdec.jpg" type="image/png">

    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
            <a href="profile.html"><img src="img/mark.png" alt="user" class="user-img"></a>
            <div>
                <p class="bold">Mark Larenz</p>
                <p>Admin</p>
            </div>
        </div>
        
        <ul>
            <li class="nav-sidebar">
                <a href="#">
                    <i class="bx bxs-grid-alt"></i>
                    <span class="nav-item">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <!-- <li>
                <a href="landingPage.html">
                    <i class='bx bx-archive'></i>
                    <span class="nav-item">Events</span>
                </a>
                <span class="tooltip">Events</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-book-add'></i>
                    <span class="nav-item">Add Event</span>
                </a>
                <span class="tooltip">Add Event</span>
            </li> -->
            <li class="events-side first nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-archive'></i>
                    <span class="nav-item">Events</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Events</span>
                <div class="uno">
                    <ul>
                        <a href="landingPage_user.html">All Event</a>
                        <!-- <a href="addEvent.html">Add Event</a> -->
                    </ul>
                </div>
            </li>

            
            <!-- <li>
                <a href="#">
                    <i class="bx bx-body"></i>
                    <span class="nav-item">Total Users</span>
                </a>
                <span class="tooltip">Total Users</span>
            </li> -->
            <!-- <li>
                <a href="#">
                    <i class="bx bx-list-check"></i>
                    <span class="nav-item">User Validation</span>
                </a>
                <span class="tooltip">User Validation</span>
            </li> -->

            <li class="events-side nav-sidebar">
                <a href="#" class="a-events">
                    <i class='bx bx-user'></i>
                    <span class="nav-item">Account</span>
                    <i class='bx bx-chevron-down hide'></i>
                </a>
                <span class="tooltip">Account</span>
                <div class="uno">
                    <ul>
                        <!-- <a href="validation.html">User Validation</a>
                        <a href="newAccount.html">Create Account</a> -->
                        <a href="profile.html">My Profile</a>
                    </ul>
                </div>
            </li>

            <li class="nav-sidebar">
                <a href="../login.html">
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
            <!-- <h3 class="dashboard">COMPANY DETAILS</h3> -->
            <!--company details-->
            <section class="view-company">

                <h1 class="heading">ORGS/AGENCY/SPONSOR/DEPARTMENT Details</h1>

                <div class="details">

                    <div class="info">
                        <img src="img/wesmaarrdec.jpg" alt="">
                        <h3>wesmaarrdec</h3>
                        <p><i class="fas fa-map-marker-alt"></i> tetuan, zamboanga city</p>
                    </div>

                    <div class="description">
                        <h3>About The Agency/Orgs/Sponsors/ANY INFO</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut obcaecati esse sunt recusandae nihil facere et libero maiores, saepe eius, consequatur reprehenderit totam quaerat nam ratione temporibus. Quaerat, quasi nihil!</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel sequi dolore fugit quo suscipit mollitia quod cum fuga aut repudiandae, repellat tempora quos atque eius minima enim laborum libero illum?</p>
                    </div>

                    <ul>
                        <li>any info</li>
                        <li>any info</li>
                        <li>any info</li>
                    </ul>

                </div>

            </section>
            <!--company details ends-->

        </div>

        <!--back button-->

        <section class="category">
            <div class="box-container">
              <a href="view_event.html" class="box">
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
    <script>
        let btnn = document.querySelector('#btnn');
        let sidebar = document.querySelector('.sidebar');
        let sidebarToggleIcons = document.querySelectorAll('.events-side .a-events'); 
        // let logoutIcon = document.querySelector('.sidebar ul li:last-child a');
    
        btnn.onclick = function () {
            sidebar.classList.toggle('active');
        };

        // Toggle sidebar when archive or user icon is clicked
        sidebarToggleIcons.forEach(icon => {
        icon.onclick = function () {
            sidebar.classList.toggle('active');
            };
        });

        // Toggle sidebar when logout icon is clicked
        // logoutIcon.onclick = function () {
        // sidebar.classList.toggle('active');
        // };
    </script>
    <!--sidebar functionality ends-->



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