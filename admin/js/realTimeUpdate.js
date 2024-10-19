document.addEventListener("DOMContentLoaded", function () {
    function updateEventStatus() {
        var currentDateTime = new Date();

        // Loop through each event box and update the status
        var eventBoxes = document.querySelectorAll(".event-container .box");
        eventBoxes.forEach(function (box) {
            var startDate = new Date(box.getAttribute("data-start-date"));
            var endDate = new Date(box.getAttribute("data-end-date"));

            if (currentDateTime >= startDate && currentDateTime <= endDate) {
                box.querySelector(".tags p:nth-child(2) span").innerText = "ongoing";
            } else if (currentDateTime < startDate) {
                box.querySelector(".tags p:nth-child(2) span").innerText = "upcoming";
            } else if (currentDateTime > endDate) {
                box.querySelector(".tags p:nth-child(2) span").innerText = "ended";
            }
        });
    }

    // Call the function initially
    updateEventStatus();

    // Set an interval to update the status every minute (adjust as needed)
    setInterval(updateEventStatus, 10000); // 60000 milliseconds = 1 minute
});