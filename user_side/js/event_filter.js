document.addEventListener("DOMContentLoaded", function () {
    let dropdowns = document.querySelectorAll('.event-filter form .dropdown-container .dropdown');
    let eventBoxes = document.querySelectorAll(".event-container .box");
    let eventTitleInput = document.getElementById('eventTitleInput');

    dropdowns.forEach(dropdown => {
        let items = dropdown.querySelectorAll('.lists .items');
        let output = dropdown.querySelector('.output');

        items.forEach(item => {
            item.addEventListener('click', function () {
                // Clear values of other dropdowns
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.querySelector('.output').value = '';
                    }
                });

                let selectedValue = item.innerText;
                output.value = selectedValue;

                // Filter event boxes based on selected value
                filterEventBoxes();
            });
        });
    });

    // Helper function to get event mode
    function getEventMode(box) {
        return box.querySelector(".tags p:nth-child(2) span").innerText.trim().toLowerCase();
    }
    
    // Helper function to get event type
    function getEventType(box) {
        return box.querySelector(".event-title").innerText.trim().toLowerCase();
    }

    // Helper function to get event status
    function getEventStatus(box) {
        return box.querySelector(".tags p:nth-child(3) span").innerText.trim().toLowerCase();
    }

    // Helper function to get event title
    function getEventTitle(box) {
        return box.querySelector(".company h3").innerText.trim().toLowerCase();
    }

    // Function to filter event boxes based on all criteria
    function filterEventBoxes() {
        let statusFilter = dropdowns[0].querySelector('.output').value.toLowerCase();
        // let modeFilter = dropdowns[1].querySelector('.output').value.toLowerCase();
        let typeFilter = dropdowns[1].querySelector('.output').value.toLowerCase();
        let titleFilter = eventTitleInput.value.toLowerCase();

        // Filter event boxes based on selected values
        eventBoxes.forEach(box => {
            let boxEventType = getEventType(box);
            // let boxEventMode = getEventMode(box);
            let boxEventStatus = getEventStatus(box);
            let boxEventTitle = getEventTitle(box);

            let displayStyle = (
                (statusFilter === '' || boxEventStatus.includes(statusFilter)) &&
                // (modeFilter === '' || boxEventMode === modeFilter) &&
                (typeFilter === '' || boxEventType === typeFilter) &&
                boxEventTitle.includes(titleFilter)
            ) ? "block" : "none";

            box.style.display = displayStyle;
        });
    }

    // Add event listener to the event title input
    eventTitleInput.addEventListener('input', filterEventBoxes);

    // Add event listener to the dropdowns for real-time filtering
    dropdowns.forEach(dropdown => {
        let output = dropdown.querySelector('.output');
        dropdown.addEventListener('input', filterEventBoxes);
    });
});
