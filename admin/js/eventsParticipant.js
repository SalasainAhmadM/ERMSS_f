function triggerAttendance(participant_id) {
    const event_id = $('input[name="event_id"]').val();
    const attendance_date = getStoredDate();  // Get date from sessionStorage

    if (!attendance_date || attendance_date === "") {
        Swal.fire({
            title: 'Error',
            text: 'Please select a date before marking attendance.',
            icon: 'error',
            customClass: {
                popup: 'larger-swal'
            }
        });
        return;
    }

    // Check the current attendance status before marking attendance
    $.ajax({
        url: 'check_status.php',  // This will check the status
        type: 'POST',
        data: {
            participant_id: participant_id,
            event_id: event_id,
            attendance_date: attendance_date
        },
        success: function (response) {
            const result = JSON.parse(response);

            if (result.time_out) {
                Swal.fire({
                    title: 'Already Timed Out',
                    text: 'This participant has already timed out for the day.',
                    icon: 'info',
                    customClass: {
                        popup: 'larger-swal'
                    }
                });
            } else if (result.status === 'absent') {
                Swal.fire({
                    title: 'Error',
                    text: 'Participant is already marked as Absent.',
                    icon: 'error',
                    customClass: {
                        popup: 'larger-swal'
                    }
                });
            } else if (result.status === 'present') {
                // When participant is already marked as present, ask for time-out confirmation
                Swal.fire({
                    title: 'Participant Already Present',
                    text: 'Do you want to Time Out this participant?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Time Out',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'larger-swal',
                        confirmButton: 'swal-confirm',
                        cancelButton: 'swal-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update attendance to time-out
                        updateAttendance(participant_id, 'timeout');  // Pass 'timeout' status here
                    }
                });
            } else {
                // If no prior status, allow user to select Present or Absent
                Swal.fire({
                    title: 'Mark Attendance',
                    text: 'Select the attendance status for this participant:',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Present',
                    cancelButtonText: 'Absent',
                    reverseButtons: true,
                    customClass: {
                        popup: 'larger-swal',
                        confirmButton: 'swal-confirm',
                        cancelButton: 'swal-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateAttendance(participant_id, 'present');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        updateAttendance(participant_id, 'absent');
                    }
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Unable to check the participant status.',
                icon: 'error',
                customClass: {
                    popup: 'larger-swal'
                }
            });
        }
    });
}

function updateAttendance(participant_id, status) {
    const event_id = $('input[name="event_id"]').val();
    const attendance_date = getStoredDate();

    let url = 'update_attendance.php';  // Default URL for updating status (present/absent)

    if (status === 'timeout') {
        url = 'time_out.php';  // Use time_out.php for time out actions
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            participant_id: participant_id,
            event_id: event_id,
            attendance_date: attendance_date,
            status: status
        },
        success: function (response) {
            let res = JSON.parse(response);
            if (res.status === 'success') {
                let statusMessage = status === 'present' ? 'Participant marked as Present' :
                    status === 'timeout' ? 'Participant timed out successfully' : 'Participant marked as Absent';

                Swal.fire({
                    title: 'Success',
                    text: statusMessage,
                    icon: 'success',
                    customClass: {
                        popup: 'larger-swal'
                    }
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: res.message,
                    icon: 'error',
                    customClass: {
                        popup: 'larger-swal'
                    }
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'There was an issue updating the attendance.',
                icon: 'error',
                customClass: {
                    popup: 'larger-swal'
                }
            });
        }
    });
}


function getStoredDate() {
    return sessionStorage.getItem('selectedDate');
}
function updateTable() {
    const selectedDate = document.getElementById('event_day-filter').value;

    // Save the selected date in sessionStorage
    sessionStorage.setItem('selectedDate', selectedDate);

    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('selectedDate', selectedDate);

    // Reload the page with the updated query parameter
    window.location.search = urlParams.toString();
}


// Automatically select the stored date on page load
window.onload = function () {
    const storedDate = getStoredDate();
    if (storedDate) {
        document.getElementById('event_day-filter').value = storedDate;
    }
};

// sdasdas
