<?php 
include 'includes/header.php'; 
include 'Data/contents.php';
include 'includes/db.php';
include 'Data/favorite-list.php';



session_start(); // Start session if not already started
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session


?>

<body>
<div id="popup-message" class="popup-message"></div>
    <div class="search-box-container">
        <div class="where">
            <h1>Where to?</h1>
            <h3>Search your favorite places in Calbayog</h3>
        </div>
        <form action="Data/search.php" method="GET">
            <div class="search-container">
                <div class="search-icon">
                    <i class="fa-light fa-magnifying-glass"></i>
                </div>
                <input type="text" id="search-input" name="q" class="search-input" placeholder="Search..." required>
                <button class="search-button" type="submit">Search</button>
                <div id="search-suggestions" class="search-suggestions"></div>
            </div>
        </form>
    </div>

    <div class="user-notif"></div>

    <div class="trendingplace">
    <h2>Trending Places in Calbayog</h2>
    
    <!-- Filter Icon with Dropdown -->
    <div class="filter-container">
        <i class="fa-light fa-filter-list filter-icon" onclick="toggleFilterDropdown()"></i>
        <div id="filter-dropdown" class="filter-dropdown">
            <div class="filter-option" onclick="filterByCategory('all')">All Categories</div>
            <?php
                // Fetch distinct categories from contents
                $categoriesResult = $conn->query("SELECT DISTINCT category FROM contents");
                while ($categoryRow = $categoriesResult->fetch_assoc()):
            ?>
                <div class="filter-option" onclick="filterByCategory('<?php echo htmlspecialchars($categoryRow['category']); ?>')">
                    <?php echo htmlspecialchars($categoryRow['category']); ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <button class="trending-nav-button left" onclick="scrollLeft()"><i class="fa-light fa-chevron-left"></i></button>

    <div class="place-list" id="place-list">
        <?php foreach ($contents as $content): ?>
            <?php
                $average_rating = round($content['average_rating'], 1);
                $review_count = $content['review_count'];
                $full_circles = floor($average_rating);
                $half_circle = ($average_rating - $full_circles) >= 0.5;
            ?>
            <div class="place-item" data-category="<?php echo htmlspecialchars($content['category']); ?>">
                <a href="display-contents.php?id=<?php echo $content['id']; ?>" class="place-item-link">
                    <img src="uploads/<?php echo htmlspecialchars($content['cover_photo']); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>">
                    <div class="favorite">
                        <a href="javascript:void(0);" class="add-to-favorite" data-content-id="<?php echo $content['id']; ?>">
                            <i class="fa-regular fa-heart"></i>
                        </a>
                    </div>
                    <h3><?php echo htmlspecialchars($content['title']); ?></h3>
                    <div class="circles">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="circle <?php if ($i <= $full_circles) echo 'filled'; elseif ($i == $full_circles + 1 && $half_circle) echo 'half-filled'; ?>"></span>
                        <?php endfor; ?>
                        <span class="avrg-rating"><?php echo number_format($average_rating, 1); ?></span>
                    </div>
                    <span class="span-cat"><?php echo htmlspecialchars($content['category']); ?></span>
                    <i class="fa-regular fa-map-location-dot adrs place-add">
                        <span class="span-add"><?php echo htmlspecialchars($content['address']); ?></span>
                    </i>
                </a>
            </div>
        <?php endforeach; ?>
        <button class="trending-nav-button right" onclick="scrollRight()"><i class="fa-light fa-chevron-right"></i></button>
    </div>

            <!-- Modal for selecting favorite list -->
        <div id="favorite-modal" class="favorite-modal" style="display: none;">
            <div class="favorite-modal-content">
                <span class="favorite-close" onclick="closeModal('favorite-modal')">&times;</span>
                <h2>Add to Favorite List</h2>
                <div id="favorite-lists" class="favorite-list-container">
                    <!-- List items will be dynamically added here -->
                </div>
            </div>
        </div>

                <!-- Modal for Announcement Details -->
        <div id="announce-modal" class="announce-modal" style="display: none;">
            <div class="announce-modal-content">
                <h2 id="announcement-modal-title"></h2>
                
                <!-- Centered Image Container -->
                <div class="announcement-image-container">
                    <img id="announcement-modal-picture" style="display: none;" alt="Announcement Picture">
                </div>

                <p class="announce-place">Place: <span id="announcement-modal-place"></span></p>
                <p id="announcement-modal-message"></p>

                <!-- Centered Buttons Container -->
                <div class="announcement-buttons-container">
                    <button id="see-place-btn" data-id="">See Place</button>
                    <button id="ann-back-btn">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
