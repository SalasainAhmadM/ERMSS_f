function confirmDelete(eventId) {
    let confirmation = confirm("Are you sure you want to delete this event?");
    
    if (confirmation) {
        // Send AJAX request to delete event using fetch
        fetch("../function/F.allUser.php?eventId=" + eventId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If successful response, reload the page
                    location.reload();
                } else {
                    // Handle error, you can log it or show an alert
                    console.error(data.error);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }
}
