
document.addEventListener('DOMContentLoaded', function() {
    // Get the submit button
    var submitBtn = document.getElementById('submitBtn');

    // Add click event listener to the submit button
    submitBtn.addEventListener('click', function() {
        // Create FormData object to collect form data
        var formData = new FormData(document.getElementById('profileForm'));

        // Send AJAX request to handle form submission
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_profile.php', true); // Change 'update_profile.php' to the path of your PHP script
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                showModal(response.message);
            } else {
                showModal('Error: ' + xhr.statusText);
            }
        };
        xhr.onerror = function() {
            showModal('Error: ' + xhr.statusText);
        };
        xhr.send(formData);
    });

    // Modal display function
    function showModal(message) {
        document.getElementById('modal-message').innerText = message;
        document.getElementById('myModal').style.display = 'block';
    }

    // Close modal when clicking on close button or outside modal
    var span = document.getElementsByClassName('close')[0];
    span.onclick = function() {
        document.getElementById('myModal').style.display = 'none';
    };
    window.onclick = function(event) {
        if (event.target == document.getElementById('myModal')) {
            document.getElementById('myModal').style.display = 'none';
        }
    };
});

