<?php 
include 'includes/header.php'; 
include 'includes/db.php';

$list_id = isset($_GET['list_id']) ? $_GET['list_id'] : null;  // Get list_id from the URL
?>

<body>

    <!-- Place List -->
    <div class="next-stay-list">
        <a href="favorite.php" class="next-stay-bck"><i class="fa-light fa-chevron-left"></i> Back</a>
        <?php if ($list_id): ?>
            <?php
            $sql = "SELECT contents.id, contents.title, contents.cover_photo, contents.category 
                    FROM favorite_list_places 
                    JOIN contents ON favorite_list_places.content_id = contents.id 
                    WHERE favorite_list_places.favorite_list_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $list_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($place = $result->fetch_assoc()) {
                    // Fetch rating and review data
                    $place_id = $place['id'];
                    $sql_rating = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count FROM ratings WHERE content_id = ?";
                    $stmt_rating = $conn->prepare($sql_rating);
                    $stmt_rating->bind_param("i", $place_id);
                    $stmt_rating->execute();
                    $result_rating = $stmt_rating->get_result();
                    $rating_data = $result_rating->fetch_assoc();
                    $average_rating = $rating_data['average_rating'] ? number_format($rating_data['average_rating'], 1) : null;
                    $review_count = $rating_data['review_count'];
                    $stmt_rating->close();

                    // Calculate full and half circles
                    $full_circles = floor($average_rating);
                    $half_circle = ($average_rating - $full_circles) >= 0.5;
                    ?>

                    <div class="next-stay-item" data-place-id="<?php echo $place_id; ?>">
                        <!-- Favorite Button -->
                        <!-- Heart icon with data attributes for list_id and place_id -->
                        <div class="favorite-button saved" data-list-id="<?php echo $list_id; ?>" data-place-id="<?php echo $place_id; ?>">
                            <i class="fa-solid fa-heart"></i>
                        </div>

                        <!-- Place Image and Details -->
                        <img src="uploads/<?php echo htmlspecialchars($place['cover_photo']); ?>" alt="<?php echo htmlspecialchars($place['title']); ?>" class="place-image">
                        <div class="place-details">
                            <h3><?php echo htmlspecialchars($place['title']); ?></h3>
                            <span class="category"><?php echo htmlspecialchars($place['category']); ?></span>

                            <!-- Rating Circles -->
                            <div class="stay-circles">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="next-circle <?php 
                                        if ($i <= $full_circles) echo 'next-filled'; 
                                        elseif ($i == $full_circles + 1 && $half_circle) echo 'next-half-filled'; 
                                        ?>">
                                    </span>
                                <?php endfor; ?>
                            </div>

                            <!-- Rating and Review Count -->
                            <div class="next-rating">
                                <span class="next-rating-score"><?php echo $average_rating ? $average_rating : 'No Rating'; ?></span>
                                <span class="next-review-count">(<?php echo htmlspecialchars($review_count); ?> reviews)</span>
                            </div>

                            <!-- Check Prices Button -->
                            <div class="check-details">
                                <p>Check details and availability for your selected place</p>
                                <a href="display-contents.php?place_id=<?php echo $place_id; ?>" class="btn btn-primary">Check Details</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No places found in this list.</p>";
            }
            $stmt->close();
            ?>
        <?php endif; ?>
    </div>




    <!-- Next Stay Container -->
    <?php if (!$list_id): ?>  <!-- Show the next-stay-container only if no list_id is present -->
    <div class="next-stay-container">
        <h1>Save, sort and compare your favorite stays</h1>
        
        <div class="next-stay-features">
            <div class="feature">
                <div class="feature-icon">
                    <i class="fa-light fa-heart"></i>
                </div>
                <p><span>Narrow down</span> your search by saving your favorite stays here</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">
                    <i class="fa-light fa-list-radio"></i>
                </div>
                <p>Log in to save your favorites for later and <span>create your own lists</span></p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">
                    <i class="fa-light fa-clone"></i>
                </div>
                <p><span>Compare</span> your favorites to choose the perfect stay</p>
            </div>
        </div>

        <div class="search-stays-btn">
            <a href="index.php" class="btn btn-primary">
                Search stays
            </a>
        </div>
    </div>
    <?php endif; ?>
    <script>
        
    </script>
</body>
