let currentSortDir = 'DESC';
let currentPage = 1;
let pageSize = 3;

function loadTable(page = 1) {
    const filter = $('#filter').val() || 'all';
    const search = $('input[name="search"]').val() || '';
    currentPage = page;

    // Show loading spinner
    $('#loading-spinner').show();

    $.ajax({
        url: '../../controller/admin/fetch_user.php',
        method: 'GET',
        data: {
            filter,
            search,
            page,
            limit: pageSize,
            status: currentStatus, // Using the status from PHP
            sortDir: currentSortDir
        },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                alert(response.error);
                return;
            }

            updateTable(response.users);
            updatePagination(response.pagination.currentPage, response.pagination.totalPages);
            updateResultsInfo(
                response.pagination.currentResults,
                response.pagination.totalResults
            );
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("An error occurred while loading data. Please try again.");
        },
        complete: function() {
            // Hide loading spinner
            $('#loading-spinner').hide();
        }
    });
}

function approveApplicant(uid) {
    // Prompt for room, hall, and seat details
    const room = prompt("Enter room number:");
    const hall = prompt("Enter hall name:");
    const seat = prompt("Enter seat number:");

    if (!room || !hall || !seat) {
        alert("All details are required to approve the applicant.");
        return;
    }

    // Show loading indicator
    $('#loading-spinner').show();

    $.ajax({
        url: '../../controller/auth/approve_applicants.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            uid: uid,
            room: room,
            hall: hall,
            seat: seat
        }),
        success: function(response) {
            if (response.status === 'success') {
                alert("Applicant approved successfully. Approval email sent.");
                loadTable(currentPage);  // Reload current page
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function(xhr) {
            alert("An error occurred while approving the applicant.");
        },
        complete: function() {
            $('#loading-spinner').hide();
        }
    });
}

function rejectApplicant(uid) {
    const reason = prompt("Please provide a reason for rejection (optional):");

    // Show loading indicator
    $('#loading-spinner').show();

    $.ajax({
        url: '../../controller/auth/reject_applicant.php', // New endpoint
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            uid: uid,
            reason: reason || '' // Send empty string if cancelled
        }),
        success: function(response) {
            if (response.status === 'success') {
                alert("Applicant rejected successfully.");
                loadTable(currentPage);  // Reload current page
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function(xhr) {
            alert("An error occurred while rejecting the applicant.");
        },
        complete: function() {
            $('#loading-spinner').hide();
        }
    });
}

function updateTable(users) {
    const userTableBody = $('#user-table-body');
    userTableBody.empty();

    if (users && users.length > 0) {
        users.forEach(user => {
            const actionButtons = (currentStatus === 'pending')
                  ? `<button onclick="approveApplicant(${user.uid})" class="btn btn-success btn-sm">
                     <i class="fas fa-check me-1"></i>Approve
                   </button>
                   <button onclick="rejectApplicant(${user.uid})" class="btn btn-danger btn-sm ms-1">
                     <i class="fas fa-times me-1"></i>Reject
                   </button>`
                  : `<button onclick="editUser(${user.uid})" class="btn btn-warning btn-sm">
                     <i class="fas fa-edit me-1"></i>Edit
                   </button>
                   <button onclick="deleteUser(${user.uid})" class="btn btn-danger btn-sm ms-1">
                     <i class="fas fa-trash me-1"></i>Delete
                   </button>`;

            userTableBody.append(`
                <tr>
                    <td>${user.uid}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td><span class="badge bg-secondary">${user.role}</span></td>
                    <td>${user.formatted_timestamp}</td>
                    <td>${actionButtons}</td>
                </tr>
            `);
        });
    } else {
        userTableBody.append(`
            <tr>
                <td colspan="7" class="text-center">
                    <div class="p-3">
                        <i class="fas fa-search me-2"></i>No users found
                    </div>
                </td>
            </tr>
        `);
    }
}

function updatePagination(currentPage, totalPages) {
    const pagination = $('#pagination');
    pagination.empty();

    // Add pagination container
    const paginationHtml = `
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <button class="page-link" onclick="loadTable(1)" ${currentPage === 1 ? 'disabled' : ''}>
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                </li>
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <button class="page-link" onclick="loadTable(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                        <i class="fas fa-angle-left"></i>
                    </button>
                </li>`;

    pagination.append(paginationHtml);

    // Add numbered pages
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        pagination.find('ul').append(`
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <button class="page-link" onclick="loadTable(${i})">${i}</button>
            </li>
        `);
    }

    // Add next and last buttons
    pagination.find('ul').append(`
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <button class="page-link" onclick="loadTable(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                <i class="fas fa-angle-right"></i>
            </button>
        </li>
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <button class="page-link" onclick="loadTable(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}>
                <i class="fas fa-angle-double-right"></i>
            </button>
        </li>
    `);
}

function updateResultsInfo(currentResults, totalResults) {
    $('#currentResults').text(currentResults);
    $('#totalResults').text(totalResults);
    $('#currentPage').text(currentPage);
    $('#totalPages').text(Math.ceil(totalResults / pageSize));
}

// Event handlers
$(document).ready(function() {
    // Initial load
    loadTable(1);

    // Sort toggle
    $('#sortToggle').on('click', function() {
        currentSortDir = currentSortDir === 'DESC' ? 'ASC' : 'DESC';
        const arrow = currentSortDir === 'DESC' ? '↓' : '↑';
        const timeType = currentStatus === 'pending' ? 'Application' : 'Approval';
        $('.sort-text').text(`${timeType} Time ${arrow}`);
        loadTable(1);
    });

    // Search input with debounce
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadTable(1);
        }, 300);
    });

    // Role filter
    $('#filter').on('change', function() {
        loadTable(1);
    });

    // Page size
    $('#pageSize').on('change', function() {
        pageSize = parseInt($(this).val());
        loadTable(1);
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('input[name="search"]').val('');
        $('#filter').val('all');
        currentSortDir = 'DESC';
        const timeType = currentStatus === 'pending' ? 'Application' : 'Approval';
        $('.sort-text').text(`${timeType} Time ↓`);
        loadTable(1);
    });
});
