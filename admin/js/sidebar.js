let btnn = document.querySelector('#btnn');
let sidebar = document.querySelector('.sidebar');
let sidebarToggleIcons = document.querySelectorAll('.events-side .a-events, .events-side2 .a-events');

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