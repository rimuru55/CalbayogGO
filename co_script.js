// Outside of DOMContentLoaded

function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "Data/logout.php";
    }
}

function previewProfileImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('profile-image-preview');
        preview.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

function openEditProfileModal() {
    document.getElementById('profile-modal').style.display = 'block';
}

function closeEditProfileModal() {
    document.getElementById('profile-modal').style.display = 'none';
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('profile-modal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});


function showTabContent(event, tabId) {
    // Remove 'active' class from all tabs and hide all content
    document.querySelectorAll('.profile-nav .tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

    // Add 'active' class to the clicked tab
    event.currentTarget.classList.add('active');

    // Show the content associated with the clicked tab
    const content = document.getElementById(tabId);
    content.classList.add('active');

    // Fetch user reviews if the Ratings/Reviews tab is selected
    if (tabId === 'ratings') {
        fetchUserReviews();
    } else if (tabId === 'photos') {
        fetchUserPhotos();
    }
}

// Function to fetch and display user photos
function fetchUserPhotos() {
    const photosContainer = document.getElementById('photos');
    photosContainer.innerHTML = '<p>Loading photos...</p>';

    fetch('Data/user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=get_user_photos'
    })
    .then(response => response.json())
    .then(data => {
        photosContainer.innerHTML = ''; // Clear previous content

        if (data.success && data.photos.length > 0) {
            data.photos.forEach(photo => {
                const imgElement = document.createElement('img');
                imgElement.src = `uploads/${photo}`;
                imgElement.alt = 'Review Photo';
                imgElement.classList.add('review-photo');
                
                photosContainer.appendChild(imgElement);
            });
        } else {
            photosContainer.innerHTML = '<p>No photos available.</p>';
        }
    })
    .catch(error => {
        console.error('Error fetching photos:', error);
        photosContainer.innerHTML = '<p>Error loading photos.</p>';
    });
}


document.addEventListener('click', function(event) {
    const target = event.target.closest('.add-to-favorite');

    if (target) {
        const contentId = target.getAttribute('data-content-id');
        const modal = document.getElementById('favorite-modal');

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
            handleResponse(data);
        })
        .catch(error => {
            console.error('Error:', error);
            showPopupMessage('An error occurred while fetching favorite lists.', 'error');
        });
    }

    function handleResponse(data) {
        if (data.requires_login) {
            showPopupMessage('Please log in first to add this place to your favorites.', 'error');
        } else if (data.success) {
            displayModalWithLists(data);
        } else {
            showPopupMessage('Failed to fetch favorite lists.', 'error');
        }
    }

    function displayModalWithLists(data) {
        modal.style.display = 'block';
        const listContainer = document.getElementById('favorite-lists');
        listContainer.innerHTML = '';

        if (data.favorite_lists.length === 0) {
            listContainer.innerHTML = '<p>No favorite lists found. Create one to start adding places!</p>';
        } else {
            const uniqueLists = getUniqueLists(data.favorite_lists);
            uniqueLists.forEach(list => {
                const listElement = createListElement(list, contentId);
                listContainer.appendChild(listElement);
            });
        }
    }

    function getUniqueLists(lists) {
        return lists.filter((list, index, self) =>
            index === self.findIndex((t) => t.id === list.id)
        );
    }

    function createListElement(list, contentId) {
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

        listElement.addEventListener('click', function() {
            addToFavoriteList(contentId, list.id);
        });

        return listElement;
    }

    function addToFavoriteList(contentId, listId) {
        fetch('Data/favorite-list.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=add_to_list&content_id=${contentId}&list_id=${listId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopupMessage('Added to your favorite list!', 'success');
                modal.style.display = 'none';
            } else {
                showPopupMessage('Failed to add to favorite list.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showPopupMessage('An error occurred while adding to the favorite list.', 'error');
        });
    }

    function showPopupMessage(message, type) {
        const popup = document.createElement('div');
        popup.className = `popup-message ${type}`;
        popup.textContent = message;
        document.body.appendChild(popup);
        
        setTimeout(() => popup.remove(), 3000);
    }
});





let autoScrollInterval;
let autoScrollPaused = false; // Track if auto-scroll is temporarily paused
let autoScrollDirection = 1; // 1 for right, -1 for left

// Adjust `updateScrollButtons` to work more precisely
function updateScrollButtons() {
    const placeList = document.querySelector('.place-list');
    const leftButton = document.querySelector('.trending-nav-button.left');
    const rightButton = document.querySelector('.trending-nav-button.right');

    // Display or hide buttons based on scroll position
    leftButton.style.display = placeList.scrollLeft > 0 ? 'flex' : 'none';
    rightButton.style.display = (placeList.scrollWidth > placeList.clientWidth + placeList.scrollLeft) ? 'flex' : 'none';
}
// Scroll functions with auto-scroll pause
// Manually trigger scrolling to test button visibility
function scrollLeft() {
    pauseAutoScroll();
    const placeList = document.querySelector('.place-list');
    placeList.scrollBy({ left: -250, behavior: 'smooth' });
}

function scrollRight() {
    pauseAutoScroll();
    const placeList = document.querySelector('.place-list');
    placeList.scrollBy({ left: 250, behavior: 'smooth' });
}

// Pause auto-scroll temporarily and resume it after manual scrolling
function pauseAutoScroll() {
    autoScrollPaused = true;
    clearInterval(autoScrollInterval);

    setTimeout(() => {
        autoScrollPaused = false;
        startAutoScroll(); // Restart auto-scroll
    }, 3000);
}


// Set an automatic scroll function with continuous movement
function startAutoScroll() {
    const placeList = document.querySelector('.place-list');

    // Clear any existing interval to prevent overlap
    clearInterval(autoScrollInterval);

    autoScrollInterval = setInterval(() => {
        if (!autoScrollPaused) {
            placeList.scrollBy({ left: autoScrollDirection * 1, behavior: 'smooth' });
            updateScrollButtons();

            // Reverse direction if end is reached
            if (placeList.scrollLeft + placeList.clientWidth >= placeList.scrollWidth) {
                autoScrollDirection = -1;
            } else if (placeList.scrollLeft <= 0) {
                autoScrollDirection = 1;
            }
        }
    }, 10); // Lower interval for smoother scroll speed
}

document.addEventListener('DOMContentLoaded', () => {
    updateScrollButtons(); // Initial button visibility check
    startAutoScroll(); // Start auto-scrolling on page load

    // Set event listeners for manual scrolling
    const leftButton = document.querySelector('.trending-nav-button.left');
    const rightButton = document.querySelector('.trending-nav-button.right');

    leftButton.addEventListener('click', scrollLeft);
    rightButton.addEventListener('click', scrollRight);

    // Update buttons on scroll
    const placeList = document.querySelector('.place-list');
    placeList.addEventListener('scroll', updateScrollButtons);
});
function toggleFilterDropdown() {
    const filterDropdown = document.getElementById('filter-dropdown');
    filterDropdown.style.display = filterDropdown.style.display === 'block' ? 'none' : 'block';
}

function filterByCategory(category) {
    const placeItems = document.querySelectorAll('.place-item');

    placeItems.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        if (category === 'all' || itemCategory === category) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });

    // Hide the dropdown after a selection is made
    document.getElementById('filter-dropdown').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {

    function showAllImages(images) {
        // Check if modal already exists, else create it
        let modal = document.getElementById('imageModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            modal.style.display = 'flex';
            modal.style.flexWrap = 'wrap';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '1000';
            document.body.appendChild(modal);

            // Close modal when clicked outside the images
            modal.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }

        // Clear any existing content in the modal
        modal.innerHTML = '';

        // Add each image to the modal
        images.forEach(image => {
            const img = document.createElement('img');
            img.src = 'uploads/' + image;
            img.style.margin = '10px';
            img.style.maxWidth = '150px';
            img.style.maxHeight = '120px';
            modal.appendChild(img);
        });

        // Display the modal
        modal.style.display = 'flex';
    }

    // Expose the function to the global scope so it can be called from HTML
    window.showAllImages = showAllImages;
    // Function to toggle the notification dropdown
    window.toggleNotificationDropdown = function () {
        var dropdown = document.querySelector('.user-notif');
        if (dropdown) {
            dropdown.classList.toggle('show');

            // Fetch the announcements only if the dropdown is shown
            if (dropdown.classList.contains('show')) {
                fetchAnnouncements();
                markAnnouncementsAsRead(); // Mark announcements as read
            }
        }
    }

    function fetchAnnouncements() {
        fetch('Data/get-announcement.php')
            .then(response => response.json())
            .then(data => {
                const dropdown = document.querySelector('.user-notif');
                let announcementsHtml = '';
    
                const announcements = data.announcements || [];
    
                if (announcements.length === 0) {
                    announcementsHtml = '<p>No announcements available.</p>';
                } else {
                    announcementsHtml = '<ul class="dropdown-list">';
                    announcements.forEach(announcement => {
                        announcementsHtml += `
                            <li class="announcement-item" data-announcement-id="${announcement.id}" data-place-id="${announcement.place_tag}" data-picture="${announcement.picture}">
                                <div class="announcement-cover-photo">
                                    ${announcement.cover_photo ? `<img src="uploads/${announcement.cover_photo}" alt="Cover Photo">` : ''}
                                </div>
                                <div class="announcement-details">
                                    <strong class="announcement-title">${announcement.title}</strong>
                                    ${announcement.place_title ? `<span class="announcement-place">${announcement.place_title}</span>` : ''}
                                    <p class="announcement-message">${announcement.message}</p>
                                    <span class="announcement-time">${getRelativeTime(new Date(announcement.created_at))}</span>
                                </div>
                            </li>`;
                    });
                    announcementsHtml += '</ul>';
                }
    
                if (dropdown) {
                    dropdown.innerHTML = announcementsHtml;
    
                    // Attach click listeners to announcements
                    document.querySelectorAll('.announcement-item').forEach(item => {
                        item.addEventListener('click', function () {
                            const title = this.querySelector('.announcement-title').textContent;
                            const message = this.querySelector('.announcement-message').textContent;
                            const placeTitle = this.querySelector('.announcement-place') ? this.querySelector('.announcement-place').textContent : '';
                            const placeId = this.getAttribute('data-place-id');
                            const picture = this.getAttribute('data-picture');  // Get the announcement-specific picture URL
    
                            // Populate the modal with announcement details
                            const modalTitle = document.getElementById('announcement-modal-title');
                            const modalMessage = document.getElementById('announcement-modal-message');
                            const modalPlace = document.getElementById('announcement-modal-place');
                            const modalPicture = document.getElementById('announcement-modal-picture');
                            const seePlaceBtn = document.getElementById('see-place-btn');
    
                            if (modalTitle) modalTitle.textContent = title;
                            if (modalMessage) modalMessage.textContent = message;
                            if (modalPlace) modalPlace.textContent = placeTitle;
                            if (modalPicture) {
                                if (picture) {
                                    modalPicture.src = `uploads/${picture}`; // Adjusted path to match directory structure
                                    modalPicture.style.display = 'block';
                                } else {
                                    modalPicture.style.display = 'none';
                                }
                            }
                            if (seePlaceBtn) seePlaceBtn.setAttribute('data-id', placeId);
    
                            // Show the modal
                            openModal('announce-modal');
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching announcements:', error);
            });
    }

    // Function to mark announcements as read
    function markAnnouncementsAsRead() {
        fetch('Data/read-announcement.php', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationDot = document.querySelector('.notification-dot');
                    if (notificationDot) {
                        notificationDot.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error marking announcements as read:', error);
            });
    }

    // Fetch and display user reviews
    fetchUserReviews();
    
    function fetchUserReviews() {
        fetch(`Data/get-user-reviews.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                const userReviewsContainer = document.getElementById('user-reviews');
                userReviewsContainer.innerHTML = ''; // Clear previous content
    
                if (data.reviews && data.reviews.length > 0) {
                    data.reviews.forEach(review => {
                        const reviewItem = document.createElement('div');
                        reviewItem.className = 'review-item';
    
                        reviewItem.innerHTML = `
                            <h4>${review.content_title}</h4>
                            <p>Rating: ${review.rating}/5</p>
                            <p>${review.review}</p>
                        `;
                        
                        userReviewsContainer.appendChild(reviewItem);
                    });
                } else {
                    userReviewsContainer.innerHTML = '<p>No reviews available.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching user reviews:', error);
            });
    }
    

    // Function to calculate the relative time difference
    function getRelativeTime(createdTime) {
        const now = new Date();
        const diffMs = now - createdTime; // Difference in milliseconds

        const minutes = Math.floor(diffMs / (1000 * 60));
        const hours = Math.floor(diffMs / (1000 * 60 * 60));
        const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (minutes < 60) {
            return `${minutes}m`;
        } else if (hours < 24) {
            return `${hours}h`;
        } else {
            return `${days}d`;
        }
    }

    // Event listener for "Back" button to close modal
    const backBtn = document.getElementById('ann-back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', function () {
            closeModal('announce-modal');
        });
    }

    // Event listener for "See Place" button to navigate
    const seePlaceBtn = document.getElementById('see-place-btn');
    if (seePlaceBtn) {
        seePlaceBtn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (id) {
                window.location.href = `display-contents.php?id=${id}`;
            }
        });
    }

    // Close the dropdown if clicked outside
    window.addEventListener('click', function (event) {
        const dropdown = document.querySelector('.user-notif');
        if (
            !event.target.closest('.user-notif') &&
            !event.target.closest('.notification-item') &&
            dropdown &&
            dropdown.classList.contains('show')
        ) {
            dropdown.classList.remove('show');
        }
    });

    // Check unread notifications on page load
    checkUnreadNotifications();

    // Function to check unread notifications and update the notification dot
    function checkUnreadNotifications() {
        fetch('Data/get-announcement.php')
            .then(response => response.json())
            .then(data => {
                const unreadCount = data.unread_count || 0;
                const notificationDot = document.querySelector('.notification-dot');

                if (notificationDot) {
                    if (unreadCount > 0) {
                        notificationDot.style.display = 'block';
                    } else {
                        notificationDot.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching unread notifications:', error);
            });
    }
    
});

