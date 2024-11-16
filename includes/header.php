<?php
session_start();
include('db.php');

$isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in
$userImage = 'images/user.png'; // Default profile image

// Check if the user is logged in and retrieve user details
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];

    // Retrieve user information from the database
    $sql = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Set user profile picture if available
        $userImage = (!empty($user['profile_picture']) && file_exists($user['profile_picture']))
            ? htmlspecialchars($user['profile_picture'])
            : 'images/user.png';
    }
    $stmt->close();
}

// Fetch unread notifications count if the user is logged in
$unread_count = 0;
if ($isLoggedIn) {
    $sql = "SELECT COUNT(*) as unread_count FROM user_announcements WHERE user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $unread_count = $row['unread_count'];
    }
    $stmt->close();
}
?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="libs/user.css">
    <link rel="stylesheet" href="libs/others.css">
    <link rel="stylesheet" href="libs/shared.css">
    <link rel="stylesheet" href="libs/responsive.css"> <!-- Adjusted path for CSS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
<!-- Mapbox GL JS library -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>

<!-- Mapbox Geocoder plugin (for search functionality) -->
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css' type='text/css' />
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js'></script>

<!-- Mapbox Directions plugin (for routing and directions) -->
<link href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.0.0/mapbox-gl-directions.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.0.0/mapbox-gl-directions.js'></script>

<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.0.0/mapbox-gl-directions.css" type="text/css">
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.0.0/mapbox-gl-directions.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>
  <script src="co_script.js"></script>
  <script src="map-script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/dist/sortable.min.js"></script>
</head>

<body>
    <nav>
        <header>
            <div class="user-logo">
                <a href="index.php"><img src="images/CalbayogGO.png" alt="logo"></a>
            </div>
            <div class="top-nav">
                <ul class="nav-links" id="nav-links">
                    <li class="one-page-map-nav">
                        <a href="one-page-map.php">
                        <i class="fa-light fa-map-location-dot"></i>                            
                            <span class="map-nav-label">Maps</span>
                        </a>
                    </li>
                    <li class="index-favorites-icon">
                        <a href="<?php echo $isLoggedIn ? 'favorite.php' : 'user-login.php'; ?>">
                            <i class="fa-light fa-heart favorites-icon"></i>
                            <span class="favorites-label">Favorites</span> <!-- Label for larger screens -->
                        </a>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <li class="notification-item" onclick="toggleNotificationDropdown()">
                            <div class="notification-icon">
                                <i class="fa-light fa-bell"></i>
                                <span id="notification-dot" class="notification-dot"></span>
                            </div>
                            <a href="javascript:void(0)" class="notification-text">Notification</a>
                        </li>

                        <li class="menu-item profile-menu">
                            <img src="<?php echo $userImage . '?' . time(); ?>" alt="Profile Image" class="user-profile">
                            <ul class="dropdown">
                                <li><a href="profile.php">Profile</a></li>
                                <li><a href="Data/logout.php" onclick="confirmLogout()" id="logout-button">Log Out</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><i class="fa-light fa-user"></i><a href="user-login.php">Log In</a></li>
                    <?php endif; ?>
                </ul>
                <div class="burger-menu" id="burger">
                    <i class="fa-light fa-bars"></i>
                </div>
            </div>
        </header>
    </nav>
</body>