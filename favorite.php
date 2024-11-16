<?php 
include 'includes/header.php'; 
include 'Data/favorite-list.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit;
}

// Fetch the favorite lists directly here
$favorite_lists = getFavoriteLists($conn, $_SESSION['user_id']);
?>

<body>

    <div class="user-notif">

    </div>
    <div class="favorite-lists">
        <div class="favorite-head">
            <h2>Your favorites</h2>
        </div>

        <div class="list-items">
            <!-- Always display default favorite item -->
            <div class="favorite-list-items">
                <a href="next-stay.php" class="favorite-link">
                    <div class="favorite-image-placeholder">
                        <i class="fa-light fa-image"></i>
                    </div>
                </a>
                <div class="favorite-title">
                    <h3>Your next stay</h3>
                </div>
            </div>
             <!-- Modal for Announcement Details -->
            <div id="announce-modal" class="announce-modal" style="display: none;">
                <div class="announce-modal-content">
                    <h2 id="announcement-modal-title"></h2>
                    <p id="announcement-modal-place"></p>
                    <p id="announcement-modal-message"></p>
                    <button id="see-place-btn" data-id="">See Place</button>
                    <button id="ann-back-btn">Back</button>
                </div>
            </div>

            <!-- Display favorite lists -->
            <?php if (!empty($favorite_lists)): ?>
                <?php foreach ($favorite_lists as $list): ?>
                    <div class="favorite-list-items" data-list-id="<?php echo $list['id']; ?>" data-places='<?php echo json_encode($list['places']); ?>'>
                        <a href="next-stay.php?list_id=<?php echo $list['id']; ?>" class="favorite-link">
                            <div class="favorite-image-placeholder">
                                <?php if (!empty($list['places'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($list['places'][0]['cover_photo']); ?>" alt="<?php echo htmlspecialchars($list['places'][0]['title']); ?>">
                                <?php else: ?>
                                    <i class="fa-light fa-image"></i> <!-- Show icon only if no places -->
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="favorite-title">
                            <h3>
                                <?php echo htmlspecialchars($list['name']); ?> 
                                <span class="place-count">(<?php echo $list['place_count']; ?> places)</span>
                            </h3>
                        </div>

                        
                        <div class="edit-delete-list">
                            <button class="favorite-list-toggle" data-list-id="<?php echo $list['id']; ?>"><i class="fa-solid fa-ellipsis"></i></button>
                            <div class="list-options" id="list-options-<?php echo $list['id']; ?>" style="display: none;">
                                <button class="edit-btn" data-list-name="<?php echo htmlspecialchars($list['name']); ?>" data-list-id="<?php echo $list['id']; ?>">Rename</button>
                                <form method="POST" class="delete-form">
                                    <input type="hidden" name="id" value="<?php echo $list['id']; ?>">
                                    <button type="submit" name="delete_favorite_list" class="delete-btn">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Create new list form -->
        <div class="create-new-list">
            <a href="javascript:void(0);" onclick="openModal('create-list-modal')">
                <i class="fa-solid fa-plus"></i> Create new list
            </a>
        </div>

        <!-- Create List Modal -->
        <div id="create-list-modal" class="favorite-modal" style="display: none;">
            <div class="favorite-modal-content">
                <span class="create-modal-close" onclick="closeModal('create-list-modal')">&times;</span>
                <form method="POST">
                    <label>Create a New List</label>
                    <input type="text" name="name" placeholder="List Name" required>
                    <button type="submit" name="create_favorite_list">Create List</button>
                </form>
            </div>
        </div>

        <!-- Rename List Modal -->
        <div id="rename-list-modal" class="favorite-modal" style="display: none;">
            <div class="favorite-modal-content">
                <span class="close" onclick="closeModal('rename-list-modal')">&times;</span>
                <form id="rename-list-Form" method="POST">
                    <h2>Rename List</h2>
                    <input type="text" id="rename-list-name" name="name" placeholder="New List Name" required>
                    <input type="hidden" id="rename-list-id" name="list_id">
                    <button type="submit" name="rename_favorite_list">Rename List</button>
                </form>
            </div>
        </div>
    </div>
</body>
