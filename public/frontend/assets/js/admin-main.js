// main.js
function fetchData(endpoint, callback) {
    $.ajax({
        url: endpoint,
        method: 'GET',
        dataType: 'json',
        success: callback,
        error: (err) => console.error(`Error fetching data from ${endpoint}`, err),
    });
}

// Example usage for stats:
fetchData('../../controller/admin/stats.php', (data) => {
    $('#pending-count').text(data.pending);
    $('#approved-count').text(data.approved);
    $('#rejected-count').text(data.rejected);
});
