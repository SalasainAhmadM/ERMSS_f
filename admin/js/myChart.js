document.addEventListener('DOMContentLoaded', function () {
    // Existing code for bar chart
    var ctx = document.getElementById('myChart').getContext('2d');
    var yearly = document.getElementById('eventsYear').getContext('2d');

    var selectedYear = document.getElementById('selectedYear');
    var selectedYearValue = selectedYear.value;
    var myChart; // Declare myChart variable

    selectedYear.addEventListener('change', function () {
        selectedYearValue = selectedYear.value;
        updateCharts();
    });

    function updateCharts() {
        // Fetch data for the bar chart (per month)
        fetchBarChartData(selectedYearValue).then(function (data) {
            updateBarChart(data);
        });

        // Fetch data for the line chart (per year)
        fetchLineChartData().then(function (data) {
            updateLineChart(data);
        });
    }

    function fetchBarChartData(year) {
        return fetch('../../function/F.getMonthlyEvents.php?year=' + year)
            .then(response => response.json())
            .then(data => data.events);
    }

    function updateBarChart(data) {
        // Remove the existing chart if it exists
        if (myChart) {
            myChart.destroy();
        }

        // Your existing Chart.js code for the bar chart
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: '# of events per month',
                    data: data.map(monthData => monthData.total_events),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
            }
        });
    }

    function fetchLineChartData() {
        return fetch('../../function/F.getYearlyEvents.php')
            .then(response => response.json())
            .then(data => data.events);
    }

    function updateLineChart(data) {
        // Your existing Chart.js code for the line chart
        var myChart = new Chart(yearly, {
            type: 'line',
            data: {
                labels: data.map(yearData => yearData.year),
                datasets: [{
                    label: 'Total # of events per year',
                    data: data.map(yearData => yearData.total_events),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
            }
        });
    }

    // Initial update on page load
    updateCharts();
});
