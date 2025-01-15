let btnn = document.querySelector('#btnn');
let sidebar = document.querySelector('.sidebar');
let sidebarToggleIcons = document.querySelectorAll('.events-side .a-events, .events-side2 .a-events');

// Make the sidebar open by default when the page loads
sidebar.classList.add('active');

// Add the toggle functionality to the button
btnn.onclick = function () {
    sidebar.classList.toggle('active');
};

// Toggle sidebar when archive or user icon is clicked
sidebarToggleIcons.forEach(icon => {
    icon.onclick = function () {
        sidebar.classList.toggle('active');
    };
});
