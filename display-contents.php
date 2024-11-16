<?php
include 'includes/header.php';
include 'includes/db.php';

// Retrieve the user ID from session, with null fallback
$user_id = $_SESSION['user_id'] ?? null;

// Check for content ID in the URL
$content_id = $_GET['id'] ?? $_GET['place_id'] ?? null;

// Check if content ID exists
if ($content_id) {
    // Fetch content details
    $sql = "SELECT * FROM contents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
    $stmt->close();

    if ($content) {
        // Deserialize the additional pictures
        $pictures = !empty($content['pictures']) ? @unserialize($content['pictures']) : [];
        if ($pictures === false && $content['pictures'] !== 'b:0;') {
            $pictures = [];
        }

        // Fetch average rating and review breakdown
        $sql = "SELECT 
                  SUM(rating = 5) AS excellent_count,
                  SUM(rating = 4) AS very_good_count,
                  SUM(rating = 3) AS average_count,
                  SUM(rating = 2) AS poor_count,
                  SUM(rating = 1) AS terrible_count,
                  COUNT(*) AS review_count,
                  AVG(rating) as average_rating
              FROM ratings
              WHERE content_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $content_id);
        $stmt->execute();
        $rating_data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $average_rating = round($rating_data['average_rating'], 1);
        $review_count = $rating_data['review_count'];
        $excellent_count = $rating_data['excellent_count'];
        $very_good_count = $rating_data['very_good_count'];
        $average_count = $rating_data['average_count'];
        $poor_count = $rating_data['poor_count'];
        $terrible_count = $rating_data['terrible_count'];

        // Fetch user reviews with associated user details
        $sql_reviews = "SELECT r.rating, r.review, r.created_at, r.photo, u.username FROM ratings r 
                        JOIN users u ON r.user_id = u.id WHERE r.content_id = ?";
        $stmt_reviews = $conn->prepare($sql_reviews);
        $stmt_reviews->bind_param("i", $content_id);
        $stmt_reviews->execute();
        $reviews = $stmt_reviews->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_reviews->close();

        // Decode amenities (if serialized) and define icons
        $amenities_icons = [
            'Wifi' => 'fa-regular fa-wifi',
            'Parking' => 'fa-regular fa-circle-parking',
            'Beach' => 'fa-regular fa-umbrella-beach',
            'Pool' => 'fa-regular fa-swimming-pool',
            'Outdoor Pool' => 'fa-regular fa-water',
            'Cottage' => 'fa-regular fa-home',
            'Banquet Room' => 'fa-regular fa-people-line',
            'Non-Smoking Hotel' => 'fa-regular fa-ban-smoking',
            'Nature Spot' => 'fa-regular fa-mountains',
            'Outdoor Activity' => 'fa-regular fa-person-hiking',
            'Historical Site' => 'fa-regular fa-landmark'
        ];
        $amenities = !empty($content['amenities']) ? @unserialize($content['amenities']) : [];
        if ($amenities === false && $content['amenities'] !== 'b:0;') {
            $amenities = [];
        }

        // Decode JSON-encoded transportation options with double-decoding fallback
        $transportation_data = isset($content['transportation']) ? $content['transportation'] : '';
        if (!empty($transportation_data)) {
            $decoded_data = json_decode($transportation_data, true);
            $transportation_options = is_string($decoded_data) ? json_decode($decoded_data, true) : $decoded_data;
        } else {
            $transportation_options = [];
        }
        
        if (!is_array($transportation_options)) {
            error_log("JSON decode error: " . json_last_error_msg());
            $transportation_options = [];
        }

        // Default things_to_do to an empty string if not set
        $things_to_do = !empty($content['things_to_do']) ? $content['things_to_do'] : '';

        // Check if the user has visited the place
        $sql_visit = "SELECT * FROM visited_places WHERE user_id = ? AND content_id = ?";
        $stmt_visit = $conn->prepare($sql_visit);
        $stmt_visit->bind_param("ii", $user_id, $content_id);
        $stmt_visit->execute();
        $visited = $stmt_visit->get_result()->num_rows > 0;
        $stmt_visit->close();

    } else {
        $content = null;
    }
} else {
    $content = null;
}

if ($content) {
    // Your remaining code for displaying content here...

?>


<div class="content-details">
    <div class="title-save">
        <h1><?php echo htmlspecialchars($content['title']); ?></h1>
        <a href="javascript:void(0);" id="save-content-button" class="save-content" data-content-id="<?php echo $content['id']; ?>">
            <i class="fa-regular fa-heart"></i> Save
        </a>
    </div>


    <div class="price-review">
        <p><?php echo htmlspecialchars($content['price']); ?></p>
        <p><?php echo htmlspecialchars($review_count); ?> reviews</p>
    </div>
    <div class="address-review">
        <i class="fa-regular fa-map-location-dot adrs">
        <a href=""><?php echo htmlspecialchars($content['address']); ?></a></i>
        <div class="circles">
            <?php 
                $full_circles = floor($average_rating);
                $half_circle = ($average_rating - $full_circles) >= 0.5;
                for ($i = 1; $i <= 5; $i++): ?>
                <span class="circle <?php 
                    if ($i <= $full_circles) echo 'filled';
                    elseif ($i == $full_circles + 1 && $half_circle) echo 'half-filled';
                    ?>">
                </span>
            <?php endfor; ?>
            <span><?php echo $average_rating; ?></span>
        </div>
    </div>

    <div class="content-img">
        <!-- Display the cover photo -->
        <?php if (!empty($content['cover_photo'])): ?>
            <img class="cover-photo" src="uploads/<?php echo htmlspecialchars($content['cover_photo']); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>">
        <?php endif; ?>

        <!-- Display the first additional image -->
        <?php if (!empty($pictures[0])): ?>
            <img class="additional-photo" src="uploads/<?php echo htmlspecialchars($pictures[0]); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>">
        <?php endif; ?>

        <!-- Display the second additional image with an overlay if more images exist -->
        <?php if (!empty($pictures[1])): ?>
            <div class="image-overlay">
                <img class="additional-photo" src="uploads/<?php echo htmlspecialchars($pictures[1]); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>">
                <?php if (count($pictures) > 2): ?>
                    <a href="all-pictures.php?content_id=<?php echo $content['id']; ?>" class="image-count-overlay">
                        +<?php echo count($pictures) - 2; ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="content-container">
    <div class="content-about">
        <h4>About</h4>
        <p><?php echo htmlspecialchars($content['description']); ?></p>
        
        <div class="content-amenities">
            <?php if (!empty($content['amenities'])): ?>
                <h5>Amenities:</h5>
                <ul>
                    <?php 
                    $amenities = unserialize($content['amenities']);
                    foreach ($amenities as $amenity): ?>
                        <li>
                            <?php if (isset($amenities_icons[$amenity])): ?>
                                <i class="fa <?php echo $amenities_icons[$amenity]; ?>"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($amenity); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No amenities available.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="things-to-do">
            <h4>Things to Do</h4>
            <p><?php echo htmlspecialchars($things_to_do ?: "No activities listed."); ?></p>

            <div class="content-transportation">
                <h5>Transportation Options:</h5>
                <?php if (!empty($transportation_options)): ?>
                    <ul>
                        <?php foreach ($transportation_options as $option): ?>
                            <li><?php echo htmlspecialchars($option); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No transportation options available.</p>
                <?php endif; ?>
            </div>
        </div>
</div>


            <!-- Display the rating -->
            <div class="rating-review">
            <h4>Reviews & Ratings</h4>
            <div class="display-rating">
            <!-- Rating Summary on the left -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#ratingModal" class="rating-summary">
                    <div class="average-rating">
                        <h3><?php echo number_format($average_rating, 1); ?></h3> <!-- Average rating -->
                        <div class="circles">
                            <?php 
                            $full_circles = floor($average_rating);
                            $half_circle = ($average_rating - $full_circles) >= 0.5;
                            for ($i = 1; $i <= 5; $i++): ?>
                                <span class="circle <?php 
                                if ($i <= $full_circles) echo 'filled';
                                elseif ($i == $full_circles + 1 && $half_circle) echo 'half-filled';
                                ?>">
                                </span>
                            <?php endfor; ?>
                            <span><?php echo htmlspecialchars($review_count); ?> reviews</span>
                        </div>
                    </div>
                    <div class="rating-breakdown">
                        <div class="rating-bar">
                            <span class="label">Excellent</span>
                            <div class="bar"><span style="width: <?php echo ($review_count > 0) ? ($excellent_count / $review_count) * 100 : 0; ?>%;"></span></div>
                            <span class="count"><?php echo $excellent_count; ?></span>
                        </div>
                        <div class="rating-bar">
                            <span class="label">Very Good</span>
                            <div class="bar"><span style="width: <?php echo ($review_count > 0) ? ($very_good_count / $review_count) * 100 : 0; ?>%;"></span></div>
                            <span class="count"><?php echo $very_good_count; ?></span>
                        </div>
                        <div class="rating-bar">
                            <span class="label">Average</span>
                            <div class="bar"><span style="width: <?php echo ($review_count > 0) ? ($average_count / $review_count) * 100 : 0; ?>%;"></span></div>
                            <span class="count"><?php echo $average_count; ?></span>
                        </div>
                        <div class="rating-bar">
                            <span class="label">Poor</span>
                            <div class="bar"><span style="width: <?php echo ($review_count > 0) ? ($poor_count / $review_count) * 100 : 0; ?>%;"></span></div>
                            <span class="count"><?php echo $poor_count; ?></span>
                        </div>
                        <div class="rating-bar">
                            <span class="label">Terrible</span>
                            <div class="bar"><span style="width: <?php echo ($review_count > 0) ? ($terrible_count / $review_count) * 100 : 0; ?>%;"></span></div>
                            <span class="count"><?php echo $terrible_count; ?></span>
                        </div>
                    </div>
                </a>

                <!-- User Reviews Section -->
                <div class="user-reviews">
                    <h4>User Reviews:</h4>
                    <?php if (count($reviews) > 0): ?>
                        <ul class="each-review">
                            <?php foreach ($reviews as $review): ?>
                                <div class="prof-reviews">
                                    <div class="prof-image-wrapper">
                                        <img src="<?php echo $userImage; ?>" alt="User Profile" class="user-profile">
                                    </div>
                                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                </div>
                                <li>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="circle <?php echo ($i <= $review['rating']) ? 'filled' : ''; ?>"></span>
                                        <?php endfor; ?>
                                    </div>
                                    <p><?php echo htmlspecialchars($review['review']); ?></p>

                                    <!-- Display each photo -->
                                    <?php 
                                    $photos = unserialize($review['photo']);
                                    if (!empty($photos) && is_array($photos)): 
                                        $photoCount = count($photos);
                                    ?>
                                        <div class="review-photos">
                                            <?php foreach ($photos as $index => $photo): ?>
                                                <?php if ($index < 3): ?>
                                                    <div class="review-photo">
                                                        <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="Review Photo" class="review-image">
                                                    </div>
                                                <?php elseif ($index == 3): ?>
                                                    <div class="review-photo" onclick="showAllImages(<?php echo htmlspecialchars(json_encode($photos)); ?>)">
                                                        <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="Review Photo" class="review-image">
                                                        <?php if ($photoCount > 4): ?>
                                                            <div class="photo-count-overlay">
                                                                +<?php echo $photoCount - 4; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <small><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>                                  
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No reviews yet.</p>
                    <?php endif; ?>
                </div>


            </div>
        </div>

    </div>
    <div id="successMessageModal" class="custom-modal" style="display:none;">
    <div class="modal-content">
        <span class="close-success">&times;</span>
        <p>Ratings & Reviews Successfully Submitted</p>
    </div>
</div>

    <!-- Modal for Rating Submission -->
    <div id="ratingModal" class="custom-modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h5>Rate This Place</h5>

        <?php if ($visited): ?>
            <form action="Data/submit-rating.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="content_id" value="<?php echo $content_id; ?>">

                <label for="rating">Rating:</label>
                <div class="rating-options">
                    <input type="radio" id="rating-5" name="rating" value="5">
                    <label for="rating-5">Excellent</label><br>

                    <input type="radio" id="rating-4" name="rating" value="4">
                    <label for="rating-4">Very Good</label><br>

                    <input type="radio" id="rating-3" name="rating" value="3">
                    <label for="rating-3">Average</label><br>

                    <input type="radio" id="rating-2" name="rating" value="2">
                    <label for="rating-2">Poor</label><br>

                    <input type="radio" id="rating-1" name="rating" value="1">
                    <label for="rating-1">Terrible</label>
                </div>

                <label for="review">Review:</label>
                <textarea name="review" id="review"></textarea>

                <!-- New file input to allow multiple photo uploads -->
                <label for="rating-photo">Upload Photos:</label>
                <input type="file" name="rating_photos[]" id="rating-photo" accept="image/*" multiple>

                <button type="submit" class="rating-btn" >Submit Rating</button>
            </form>
        <?php else: ?>
            <p>You must visit this place before submitting a rating.</p>
        <?php endif; ?>
    </div>
</div>

      <!-- Ensure content ID is available to JavaScript -->
<script>
    const contentId = <?php echo json_encode($content_id); ?>;  // Use the `$content_id` variable from PHP

document.addEventListener('DOMContentLoaded', function () {
    // Get the "Save" button and the favorite modal
    const saveButton = document.getElementById('save-content-button');
    const favoriteModal = document.getElementById('favorite-modal');
    const listContainer = document.getElementById('favorite-lists');
    const contentId = saveButton.getAttribute('data-content-id'); // Retrieve content ID

    // Add click event listener to the "Save" button
    saveButton.addEventListener('click', function () {
        // Fetch favorite lists from the server
        fetch('Data/favorite-list.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=get_favorite_lists'
        })
        .then(response => response.json())
        .then(data => {
            // Clear previous list content
            listContainer.innerHTML = '';

            // Show login prompt if required
            if (data.requires_login) {
                alert('Please log in to add this place to your favorites.');
                window.location.href = 'user-login.php';
            } else if (data.success) {
                // Populate the modal with favorite lists
                favoriteModal.style.display = 'block';

                if (data.favorite_lists.length === 0) {
                    listContainer.innerHTML = '<p>No favorite lists found. Create one to start adding places!</p>';
                } else {
                    // Ensure unique lists
                    const uniqueLists = [];
                    data.favorite_lists.forEach(list => {
                        if (!uniqueLists.some(ul => ul.id === list.id)) {
                            uniqueLists.push(list);
                        }
                    });

                    uniqueLists.forEach(list => {
                        const listElement = document.createElement('div');
                        listElement.classList.add('favorite-list-item');

                        listElement.innerHTML = `
                            <div class="favorite-image-placeholder">
                                ${list.places.length > 0 
                                    ? `<img src="uploads/${list.places[0].cover_photo}" alt="${list.places[0].title}">` 
                                    : '<i class="fa-regular fa-image"></i>'
                                }
                            </div>
                            <div class="favorite-title">
                                <h3>${list.name} <span class="place-count">(${list.place_count} places)</span></h3>
                            </div>
                        `;

                        // Add click event to add the content to the selected list
                        listElement.addEventListener('click', function() {
                            fetch('Data/favorite-list.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `action=add_to_list&content_id=${contentId}&list_id=${list.id}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Added to your favorite list!');
                                    favoriteModal.style.display = 'none';
                                } else {
                                    alert('Failed to add to favorite list.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        });

                        listContainer.appendChild(listElement);
                    });
                }
            } else {
                alert('Failed to fetch favorite lists.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Close the modal when the close button is clicked
    document.querySelector('.favorite-close').addEventListener('click', function () {
        favoriteModal.style.display = 'none';
    });

    // Handle the form submission via AJAX
document.querySelector('.rating-btn').addEventListener('click', function (e) {
    e.preventDefault();  // Prevent default form submission

    let formData = new FormData(document.querySelector('#ratingModal form'));

    fetch('Data/submit-rating.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the rating modal
            document.getElementById('ratingModal').style.display = 'none';

            // Show the success modal
            document.getElementById('successMessageModal').style.display = 'block';
        } else {
            alert(data.message || 'Submission failed.');
        }
    })
    .catch(error => console.error('Error:', error));
});

// Close the success message modal
document.querySelector('.close-success').addEventListener('click', function () {
    document.getElementById('successMessageModal').style.display = 'none';
});

// Close the rating modal when the close button is clicked
document.querySelector('.close').addEventListener('click', function () {
    document.getElementById('ratingModal').style.display = 'none';
});


    
});

</script>

      
<?php
} else {
    echo "<p>Content not found.</p>";
}
?>
