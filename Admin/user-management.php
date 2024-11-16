<?php
// Include the user data
include('../Data/user.php');
?>
<body>
<div class="user-management-container">
    <h1 class="user-management-title">User Management</h1>
    <div id="user-list" class="user-management-search-filter">
        <input type="text" id="search" placeholder="Search by name, email, or role">
        <select id="filter-role">
            <option value="">Filter by Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <select id="filter-status">
            <option value="">Filter by Status</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>
    <table class="user-management-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Registration Date</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user): ?>
                    <tr data-username="<?php echo htmlspecialchars($user['username']); ?>"
                        data-email="<?php echo htmlspecialchars($user['email']); ?>"
                        data-role="<?php echo htmlspecialchars($user['role']); ?>"
                        data-status="<?php echo htmlspecialchars($user['status']); ?>">
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                        <td><?php echo htmlspecialchars($user['registration_date']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_login']); ?></td>
                        <td>
                            <button class="view-btn" data-id="<?php echo $user['id']; ?>">View</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- User Details Section -->
    <div id="user-details" class="user-management-details" style="display:none;">
        <button id="back-btn" class="user-management-back-btn">Back to User List</button>
        <h2>User Details</h2>
        <div id="user-info"></div> <!-- User Information will be populated here -->
        <div id="user-reviews" class="user-management-section">
            <h3>User Reviews</h3>
            <div id="user-reviews-content"></div>
        </div>
        <div id="user-visited-places" class="user-management-section">
            <h3>Visited Places</h3>
            <div id="visited-places-content"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Search and filter function
    function filterUsers() {
        const searchQuery = $('#search').val().toLowerCase();
        const filterRole = $('#filter-role').val();
        const filterStatus = $('#filter-status').val();

        $('#userTable tr').each(function() {
            const username = $(this).data('username').toLowerCase();
            const email = $(this).data('email').toLowerCase();
            const role = $(this).data('role');
            const status = $(this).data('status');

            const matchesSearch = username.includes(searchQuery) || email.includes(searchQuery);
            const matchesRole = !filterRole || role === filterRole;
            const matchesStatus = !filterStatus || status === filterStatus;

            $(this).toggle(matchesSearch && matchesRole && matchesStatus);
        });
    }

    // Attach event listeners for search and filter inputs
    $('#search').on('input', filterUsers);
    $('#filter-role').on('change', filterUsers);
    $('#filter-status').on('change', filterUsers);

    // View button click event
    $('.view-btn').on('click', function() {
        const userId = $(this).data('id');
        $.ajax({
            url: '../Data/user.php',
            type: 'POST',
            data: { action: 'get_user_details', id: userId },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    populateUserInfo(data);
                    populateUserReviews(data);
                    populateVisitedPlaces(data);
                    $('#user-list').hide();
                    $('#user-details').show();
                } catch (e) {
                    console.error("Error parsing response:", e);
                    alert("An error occurred while fetching user details.");
                }
            },
            error: function() {
                alert("Failed to fetch user details. Please try again.");
            }
        });
    });

    // Populate user information
    function populateUserInfo(data) {
        $('#user-info').html(`
            <h3>${data.user.username}</h3>
            <p>Email: ${data.user.email}</p>
            <p>Role: ${data.user.role}</p>
            <p>Status: ${data.user.status}</p>
            <p>Registration Date: ${data.user.registration_date}</p>
            <p>Last Login: ${data.user.last_login}</p>
        `);
    }

    // Populate user reviews
    function populateUserReviews(data) {
        if (data.reviews.length > 0) {
            $('#user-reviews-content').empty();
            data.reviews.forEach(review => {
                $('#user-reviews-content').append(`
                    <div class="review-item" data-review-id="${review.id}">
                        <p><strong>Rating:</strong> ${review.rating}</p>
                        <p><strong>Review:</strong> ${review.review}</p>
                        <p><strong>Date:</strong> ${review.created_at}</p>
                        <button class="delete-review-btn" data-review-id="${review.id}">Delete</button>
                    </div>
                `);
            });
        } else {
            $('#user-reviews-content').html('<p>No reviews found.</p>');
        }
    }

    // Populate visited places
    function populateVisitedPlaces(data) {
        if (data.visited_places.length > 0) {
            $('#visited-places-content').empty();
            data.visited_places.forEach(place => {
                $('#visited-places-content').append(`
                    <div class="visited-place-item">
                        <p><strong>Place:</strong> ${place.title}</p>
                        <p><strong>Visited Date:</strong> ${place.last_visited_date}</p>
                    </div>
                `);
            });
        } else {
            $('#visited-places-content').html('<p>No visited places found.</p>');
        }
    }

    // Handle delete and edit review actions dynamically
    $(document).on('click', '.delete-review-btn', function() {
        const reviewId = $(this).data('review-id');
        if (confirm('Are you sure you want to delete this review?')) {
            $.ajax({
                url: '../Data/user.php',
                type: 'POST',
                data: { action: 'delete_review', review_id: reviewId },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        alert('Review deleted successfully.');
                        $(`.review-item[data-review-id="${reviewId}"]`).remove();
                    } else {
                        alert('Failed to delete the review.');
                    }
                },
                error: function() {
                    alert('Error deleting the review.');
                }
            });
        }
    });

    // Back button click event
    $('#back-btn').on('click', function() {
        $('#user-details').hide();
        $('#user-list').show();
    });
});
</script>

</body>
