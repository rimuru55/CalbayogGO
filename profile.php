<?php
include 'includes/header.php';
include 'includes/db.php';

if (!$isLoggedIn) {
    header('Location: login.html');
    exit();
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit();
    }
    $stmt->close();
} else {
    echo "User ID not found in session!";
    exit();
}

$profilePicture = (!empty($user['profile_picture']) && file_exists($user['profile_picture']))
    ? htmlspecialchars($user['profile_picture'])
    : 'images/user.png';

?>
<head>
    <style>

        /* Ensure that the #trips content stays within bounds */
#trips {
    max-width: 100%;    /* Ensures the content doesn't exceed the width of its parent */
    overflow: auto;     /* Adds a scrollbar if the content overflows horizontally */
    padding: 20px;      /* Adds space around the content */
    box-sizing: border-box; /* Ensures padding is included in width/height calculations */
}

/* Optionally, add styles for inner content to handle long content gracefully */
#trips .trip-item {
    overflow-wrap: break-word; /* Breaks long words to avoid overflow */
    word-wrap: break-word; /* Ensures long text does not overflow */
    margin-bottom: 10px; /* Adds space between items */
}

    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-img">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Image">
            </div>
            <h1><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h1>
            <p>@<?php echo htmlspecialchars($user['username']); ?></p>
        </div>
        <div class="profile-info">
            <div class="profile-stats">
                <nav class="profile-nav">
                    <a href="#" class="tab active" onclick="showTabContent(event, 'ratings')">Ratings/Reviews</a>
                    <a href="#" class="tab" onclick="showTabContent(event, 'trips')">Trips</a>
                    <a href="#" class="tab" onclick="showTabContent(event, 'photos')">Photos</a>
                </nav>
                <div class="profile-settings">
                    <i class="fa-duotone fa-light fa-gear"></i>
                    <button onclick="openEditProfileModal()" class="edit-profile-button">Edit Profile</button>
                </div>
            </div>
        </div>

        <!-- Content Section (User Feed) -->
        <div class="user-feed">
            <div id="ratings" class="tab-content active"> <!-- Initially active tab -->
                <h2>Your Reviews & Ratings</h2>
                <div id="user-reviews">
                    <!-- Reviews and ratings will be loaded here via JavaScript -->
                </div>
            </div>

            <!-- Trips) -->
            <div id="trips" class="tab-content" style="display: none;">
             <iframe src="destination_display.php" width="100%" height="500" style="border: none;"></iframe>
            </div>

            <div id="photos" class="tab-content" style="display: none;">
                
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div id="profile-modal" class="edit-profile-modal">
            <div class="profile-modal-content">
                <span class="user-close-modal" onclick="closeEditProfileModal()">&times;</span>
                <h2>Edit Profile</h2>
                <form action="Data/user.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit_profile">
                    
                    <div class="profile-image-section">
                        <div class="profile-image-wrapper">
                            <img src="<?php echo $profilePicture; ?>" alt="Profile Image" id="profile-image-preview" class="profile-image">
                            <label for="profile_image" class="change-photo-overlay">
                                <i class="fa-duotone fa-solid fa-camera"></i>
                                <span>Change profile photo</span>
                            </label>
                            <input type="file" id="profile_image" name="profile_image" onchange="previewProfileImage(event)" style="display: none;">
                        </div>
                    </div>
                    
                    <!-- First Name -->
                    <label for="firstname">Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>" required>
                
                    <!-- Username -->
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                    <!-- Submit and Cancel Buttons -->
                    <div class="prof-modal-buttons">
                        <button type="button" onclick="closeEditProfileModal()" class="prof-cancel-button">Cancel</button>
                        <button type="submit" class="prof-save-button">Save</button>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <script>
        const userId = <?php echo json_encode($userId); ?>;

       
    function showTabContent(event, tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(function(content) {
            content.style.display = 'none';
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(function(tab) {
            tab.classList.remove('active');
        });

        // Show the clicked tab's content
        document.getElementById(tabId).style.display = 'block';

        // Add active class to the clicked tab
        event.target.classList.add('active');
    }


    </script>
</body>
