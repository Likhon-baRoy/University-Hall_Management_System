let roleChartInstance;
let hallChartInstance;

// Fetch dashboard data asynchronously
function fetchDashboardData() {
    $('#loading-spinner').removeClass('d-none');
    $.ajax({
        url: '../../controller/admin/fetch_dashboard_data.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Hide loading spinner and display data
            $('#loading-spinner').addClass('d-none');
            $('#stats-cards').removeClass('d-none');
            $('#pending-count').text(data.pending_count);
            $('#approved-count').text(data.approved_count);
            $('#rejected-count').text(data.rejected_count);

            // Update charts
            updateRoleChart(data.roles);
            updateHallChart(data.halls);

            // Update hall information dynamically
            updateHallSection(data.halls);
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch dashboard data:', error);
            $('#loading-spinner').addClass('d-none');
        }
    });
}

// Update Role Chart
function updateRoleChart(roles) {
    const roleLabels = roles.map(role => role.role);
    const roleCounts = roles.map(role => role.count);

    if (!roleChartInstance) {
        const ctx = document.getElementById('roleChart').getContext('2d');
        roleChartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: roleLabels,
                datasets: [{
                    data: roleCounts,
                    backgroundColor: generateColors(roles.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    } else {
        roleChartInstance.data.labels = roleLabels;
        roleChartInstance.data.datasets[0].data = roleCounts;
        roleChartInstance.update();
    }
}

// Initialize or update Hall Pie Chart (with subtle 3D effect)
function updateHallChart(hallData) {
    const ctx = document.getElementById('hallChart').getContext('2d');

    const data = {
        labels: hallData.map(hall => hall.hall_name),
        datasets: [{
            data: hallData.map(hall => hall.available_seats),
            backgroundColor: generateColors(hallData.length),
            borderWidth: 2,
            hoverOffset: 4
        }]
    };

    // Create or update the chart with adjusted size
    new Chart(ctx, {
        type: 'pie',  // Pie chart for a simple yet impactful look
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            // Adding 3D-like hover effect with value display
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' seats';
                        }
                    }
                }
            },
            elements: {
                arc: {
                    borderWidth: 2,  // Adds clean edges to the pie slices
                }
            },
            animation: {
                animateScale: true,  // Smooth scale animation on render
                animateRotate: true,  // Smooth rotation animation on render
            }
        }
    });
}

// Update Hall Information Section
function updateHallSection(halls) {
    const hallContainer = $('#hall-info-body');
    hallContainer.empty();

    halls.forEach(hall => {
        const hallRow = `
            <tr>
                <td>${hall.hall_name}</td>
                <td>${hall.total_rooms}</td>
                <td>${hall.total_seats}</td>
                <td>${hall.available_seats}</td>
            </tr>
        `;
        hallContainer.append(hallRow);
    });
}

// Generate dynamic colors for charts
function generateColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(`hsl(${(i * 360) / count}, 70%, 70%)`);
    }
    return colors;
}

// Fetch data periodically
setInterval(fetchDashboardData, 15000); // Update every 15 seconds
$(document).ready(fetchDashboardData);
