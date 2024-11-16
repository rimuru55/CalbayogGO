// Open modal function (open only the modal passed as an argument)
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

// Close modal function (close only the modal passed as an argument)
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.input');
    const burger = document.getElementById('burger');
    const topNav = document.querySelector('.top-nav');
    const searchInput = document.getElementById('search-input');
    const searchSuggestionsContainer = document.getElementById('search-suggestions');
    const placeholderTexts = ["Malajog", "Bukid ni Manang", "Tarangban Falls", "Marju Krisel", "Ton-ok Falls"];
    let index = 0;

    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.nextElementSibling.classList.add('active');
        });

        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.nextElementSibling.classList.remove('active');
            }
        });

        if (input.value !== '') {
            input.nextElementSibling.classList.add('active');
        } else {
            input.nextElementSibling.classList.remove('active');
        }
    });

    burger.addEventListener('click', () => {
        topNav.classList.toggle('active');
    });

    document.getElementById('burger').addEventListener('click', function() {
        document.getElementById('nav-links').classList.toggle('active');
    });

    document.querySelectorAll('.trendbtn').forEach(button => {
        button.addEventListener('click', function() {
            const contentId = this.getAttribute('data-content-id');
            window.location.href = 'display-contents.php?id=' + contentId;
        });
    });

    attachToggleListOptions();

    function attachToggleListOptions() {
        document.querySelectorAll('.favorite-list-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const listId = this.getAttribute('data-list-id');
                const options = document.getElementById(`list-options-${listId}`);
                options.style.display = options.style.display === 'block' ? 'none' : 'block';
            });
        });
    }

    document.querySelectorAll('.favorite-button').forEach(button => {
        button.addEventListener('click', function() {
            const listId = this.getAttribute('data-list-id');
            const placeId = this.getAttribute('data-place-id');

            fetch('Data/remove-place.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `list_id=${listId}&content_id=${placeId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('.next-stay-item').remove();
                    alert('Place removed successfully');
                    
                    const placeCountElement = document.querySelector(`[data-list-id="${listId}"] .place-count`);
                    if (placeCountElement) {
                        let placeCount = parseInt(placeCountElement.textContent.match(/\d+/)[0]);
                        placeCountElement.textContent = `(${--placeCount} places)`;
                    }
                } else {
                    alert('Failed to remove place');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    const renameForm = document.getElementById('rename-list-Form');
    if (renameForm) {
        renameForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(renameForm);
            formData.append('action', 'rename');

            fetch('Data/favorite-list.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const listId = data.list_id;
                    const newName = data.new_name;
                    const listElement = document.querySelector(`[data-list-id="${listId}"] h3`);
                    if (listElement) {
                        listElement.textContent = newName;
                    }
                    closeModal('rename-list-modal');
                    showNotification('List renamed successfully!', 'success');
                } else {
                    showNotification(data.message || 'Failed to rename the list.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error occurred during renaming.', 'error');
            });
        });
    }

    const createListForm = document.querySelector('#create-list-modal form');

    if (createListForm) {
        createListForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(createListForm);
            formData.append('create_favorite_list', 'create_favorite_list');
    
            fetch('Data/favorite-list.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok, status: ${response.status}`);
                }
                return response.text(); // Get response as text for debugging purposes
            })
            .then(text => {
                console.log("Raw response:", text);  // Log the raw response for inspection
    
                // Trim any potential whitespace from the response text to avoid JSON parse issues
                const trimmedText = text.trim();
    
                // Attempt to parse JSON, with error handling if it fails
                try {
                    const data = JSON.parse(trimmedText);
    
                    if (data.success) {
                        console.log('List created successfully:', data);
                        const listContainer = document.querySelector('.list-items');
                    
                        // Create new list element
                        const newListElement = document.createElement('div');
                        newListElement.classList.add('favorite-list-items');
                        newListElement.setAttribute('data-list-id', data.list_id);
                        newListElement.innerHTML = `
                            <a href="next-stay.php?list_id=${data.list_id}" class="favorite-link">
                                <div class="favorite-image-placeholder">
                                    <i class="fa-light fa-image"></i>
                                </div>
                            </a>
                            <div class="favorite-title">
                                <h3>${data.list_name} <span class="place-count">(0 places)</span></h3>
                            </div>
                            <div class="edit-delete-list">
                                <button class="favorite-list-toggle" data-list-id="${data.list_id}">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <div class="list-options" id="list-options-${data.list_id}" style="display: none;">
                                    <button class="edit-btn" data-list-name="${data.list_name}" data-list-id="${data.list_id}">Rename</button>
                                    <form method="POST" class="delete-form">
                                        <input type="hidden" name="id" value="${data.list_id}">
                                        <button type="submit" name="delete_favorite_list" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </div>
                        `;
                    
                        listContainer.appendChild(newListElement);
                        attachToggleListOptions();
                        attachRenameButton();
                        attachDeleteButton(); // Re-enable delete functionality for the new list
                    
                        closeModal('create-list-modal');
                        showNotification('List created successfully!', 'success');
                    }
                     else {
                        console.error('Failed response:', data.message);
                        showNotification(data.message || 'Failed to create the list.', 'error');
                    }
                } catch (error) {
                    // This handles any non-JSON responses
                    console.error('Response parsing error:', error, 'Trimmed Response Text:', trimmedText);
                    showNotification(`Unexpected server response: ${trimmedText}`, 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showNotification('Error occurred during list creation.', 'error');
            });
        });
    }
    
    function attachDeleteButton() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
    
                const formData = new FormData(this);
                formData.append('action', 'delete');
    
                fetch('Data/favorite-list.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('List deleted successfully!', 'success');
                        const listId = formData.get('id');
                        const listElement = document.querySelector(`[data-list-id="${listId}"]`);
                        if (listElement) {
                            listElement.remove(); // Remove the list element from the DOM
                        }
                    } else {
                        showNotification(data.message || 'Failed to delete the list.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error occurred during deletion.', 'error');
                });
            });
        });
    }
    
    function attachRenameButton() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const listName = this.getAttribute('data-list-name');
                const listId = this.getAttribute('data-list-id');

                document.getElementById('rename-list-name').value = listName;
                document.getElementById('rename-list-id').value = listId;

                document.querySelectorAll('.list-options').forEach(option => {
                    option.style.display = 'none';
                });

                openModal('rename-list-modal');
            });
        });
    }

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'delete');

            fetch('Data/favorite-list.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('List deleted successfully!', 'success');
                    const listId = formData.get('id');
                    const listElement = document.querySelector(`[data-list-id="${listId}"]`);
                    if (listElement) {
                        listElement.remove();
                    }
                } else {
                    showNotification(data.message || 'Failed to delete the list.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error occurred during deletion.', 'error');
            });
        });
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const listName = this.getAttribute('data-list-name');
            const listId = this.getAttribute('data-list-id');

            document.getElementById('rename-list-name').value = listName;
            document.getElementById('rename-list-id').value = listId;

            document.querySelectorAll('.list-options').forEach(option => {
                option.style.display = 'none';
            });

            openModal('rename-list-modal');
        });
    });

    function renderSearchSuggestions(contents) {
        searchSuggestionsContainer.innerHTML = '';

        contents.forEach(content => {
            const suggestion = document.createElement('div');
            suggestion.classList.add('search-suggestion');

            suggestion.innerHTML = `
                <a href="display-contents.php?id=${content.id}">
                    <div class="suggestion-image">
                        <img src="${content.cover_photo}" alt="Cover Photo" />
                    </div>
                    <div class="suggestion-info">
                        <h3>${content.title}</h3>
                        <p>${content.address}</p>
                    </div>
                </a>
            `;

            searchSuggestionsContainer.appendChild(suggestion);
        });
    }

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase();

        if (query.length > 2) {
            fetch(`Data/search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        renderSearchSuggestions(data);
                        searchSuggestionsContainer.style.display = 'block';
                    } else {
                        searchSuggestionsContainer.innerHTML = '<p>No results found</p>';
                        searchSuggestionsContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        } else {
            searchSuggestionsContainer.innerHTML = '';
            searchSuggestionsContainer.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchSuggestionsContainer.contains(event.target)) {
            searchSuggestionsContainer.style.display = 'none';
        }
    });

    function changePlaceholder() {
        searchInput.setAttribute("placeholder", placeholderTexts[index]);
        index = (index + 1) % placeholderTexts.length;
    }

    setInterval(changePlaceholder, 2000);
    changePlaceholder();

    window.onclick = function(event) {
        const modals = ['create-list-modal', 'rename-list-modal', 'favorite-modal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    };

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `renameNotification ${type}`;
        notification.innerText = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});




//end for ajax function




// Navbar and modal functionality
$(document).ready(function() {

  const modal = document.getElementById("ratingModal");
  const btn = document.querySelector(".rating-summary"); // Ensure this selector matches the trigger element
  const span = document.querySelector(".custom-modal .close");

   // Check if user is logged in before opening the modal
   if (btn) {
    btn.addEventListener('click', function(event) {
      event.preventDefault();

      // Fetch login status
      fetch('includes/check-login.php')
        .then(response => response.json())
        .then(data => {
          if (data.logged_in) {
            // User is logged in, show the modal
            modal.style.display = "block";
          } else {
            // User is not logged in, prompt to log in
            alert('You must be logged in to rate.');
            window.location.href = 'user-login.php'; // Redirect to login page
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
  }

  // Close the modal when clicking on <span> (x)
  if (span) {
    span.addEventListener('click', function() {
      modal.style.display = "none";
    });
  }

  // Close the modal when clicking anywhere outside of the modal
  window.addEventListener('click', function(event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

// Navbar AJAX content loading
$('.admin-nav a').on('click', function(event) {
  event.preventDefault();

  const sectionUrl = $(this).data('section');

  $.ajax({
    url: '../' + sectionUrl, // Adjusting the path
    type: 'GET',
    success: function(response) {
      $('.contents').html(response);

      // Re-initialize any scripts within the loaded content
      if (sectionUrl === 'Admin/admin-announcement.php') {
          loadAnnouncements();  // Load announcements if it's the announcements page
          initializeAnnouncementModal();  // Initialize modal if needed
      }
    },
    error: function() {
      $('.contents').html('<p>Error loading content. Please try again.</p>');
    }
  });
});


  // Load the default section
  $('a[data-section="Admin/dashboard.php"]').trigger('click');

  // Open Add Content Modal
  $('.contents').on('click', '#addContentBtn', function() {
    $('#addContentModal').show();
  });

  // Open Edit Content Modal
  $('.contents').on('click', '.edit-content-btn', function() {
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    const title = row.find('td:eq(0)').text();
    const description = row.find('td:eq(1)').text();
    const address = row.find('td:eq(2)').text();
    const price = row.find('td:eq(3)').text();

    $('#edit-content-id').val(id);
    $('#edit-title').val(title);
    $('#edit-description').val(description);
    $('#edit-address').val(address);
    $('#edit-price').val(price);

    $('#editContentModal').show();
  });

  // Close Modal
  $('.contents').on('click', '.close', function() {
    $(this).closest('.modal').hide();
  });

  // Handle Delete Content
  $('.contents').on('click', '.delete-content-btn', function() {
    if (confirm('Are you sure you want to delete this content?')) {
      const id = $(this).data('id');
      $.post('../Data/contents.php', { action: 'delete', id: id }, function(response) {
        location.reload();
      });
    }
  });

  // Handle Add Content
  $('#addContentForm').on('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'add');

    $.ajax({
      url: '../Data/contents.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        location.reload();
      }
    });
  });

  // Handle Edit Content
  $('#editContentForm').on('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'edit');

    $.ajax({
      url: '../Data/contents.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        location.reload();
      }
    });
  });

  $('.burger-menu').on('click', function() {
    $('.top-nav ul').toggleClass('show');
  });

});

