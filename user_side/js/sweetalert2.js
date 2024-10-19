// Import SweetAlert2
import Swal from 'sweetalert2';

// Display "Participant Limit Reached" Alert
function displayLimitReachedAlert() {
    Swal.fire({
        icon: 'warning',
        title: 'Participant Limit Reached',
        text: 'Sorry, the participant limit for this event has been reached. You cannot join at the moment.',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'userDashboard.php';
    });
}

// Display "Already Joined" Alert
function displayAlreadyJoinedAlert() {
    Swal.fire({
        icon: 'info',
        title: 'Already Joined',
        text: 'You have already joined this event!',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'userDashboard.php';
    });
}

// Display "Joined Successfully" Alert
function displayJoinedSuccessfullyAlert() {
    Swal.fire({
        icon: 'success',
        title: 'Joined Event',
        text: 'Successfully joined the event!',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'userDashboard.php';
    });
}

// Display "Failed to Join" Alert
function displayFailedToJoinAlert() {
    Swal.fire({
        icon: 'error',
        title: 'Failed to Join',
        text: 'Failed to join the event. Please try again.',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'userDashboard.php';
    });
}
