<body>
<button id="openModalBtn">Create New Announcement</button>

<!-- Modal Form -->
<div id="announcementModal" class="announcement-manage-modal">
    <div class="announce-modal-content">
        <span class="close">&times;</span>
        <h2>Create New Announcement</h2>
        <form id="announcementForm" action="javascript:void(0);" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="message">Message:</label><br>
            <textarea id="message" name="message" rows="4" cols="50" required></textarea><br>

            <label for="place_tag">Tagged Place:</label>
            <select id="place_tag" name="place_tag">
                <option value="">No Place</option>
                <?php
                    include('../includes/db.php');
                    $sql_places = "SELECT id, title FROM contents";
                    $result_places = $conn->query($sql_places);
                    while ($place = $result_places->fetch_assoc()) {
                        echo "<option value='" . $place['id'] . "'>" . htmlspecialchars($place['title']) . "</option>";
                    }
                ?>
            </select><br>

            <label for="picture">Announcement Picture:</label>
            <input type="file" id="picture" name="picture"><br>

            <label for="target_audience">Target Audience:</label>
            <select id="target_audience" name="target_audience" required>
                <option value="all">All Users</option>
                <option value="new_users">New Users</option>
                <option value="frequent_travelers">Frequent Travelers</option>
                <option value="specific_group">Specific Group</option>
            </select><br>

            <label for="urgency">Urgency:</label>
            <select id="urgency" name="urgency" required>
                <option value="normal">Normal</option>
                <option value="high">High Priority</option>
            </select><br>

            <label for="send_now">Send Now:</label>
            <input type="radio" id="send_now_yes" name="send_now" value="1" checked> Yes
            <input type="radio" id="send_now_no" name="send_now" value="0"> No<br>

            <label for="scheduled_time">Scheduled Time:</label>
            <input type="datetime-local" id="scheduled_time" name="scheduled_time"><br>

            <label for="notification_type">Notification Type:</label><br>
            <input type="checkbox" id="in_app" name="notification_type[]" value="in_app"> In-App<br>
            <input type="checkbox" id="push" name="notification_type[]" value="push"> Push Notification<br>
            <input type="checkbox" id="email" name="notification_type[]" value="email"> Email Notification<br>

            <button type="submit" id="submitAnnouncementBtn">Create Announcement</button>
        </form>
    </div>
</div>


<!-- Table to display announcements -->
<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Target Group</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="announcementTable">
            <!-- Announcements will be loaded here via JavaScript -->
        </tbody>
    </table>
</div>

<script>
// Initialize modal, close behavior, and form submission logic
function initializeAnnouncementModal() {
    var modal = document.getElementById("announcementModal");
    var openModalBtn = document.getElementById("openModalBtn");
    var closeModalBtn = document.getElementsByClassName("close")[0];
    var submitButton = document.getElementById("submitAnnouncementBtn");

    // Open modal when button clicked for new announcement
    openModalBtn.onclick = function() {
        modal.style.display = "block";
        resetForm();  // Reset form for a new announcement
        submitButton.textContent = "Create Announcement";  // Set button to "Create Announcement"

        // Remove the current form submission event listeners
        document.getElementById("announcementForm").onsubmit = null;

        // Bind the create handler
        document.getElementById("announcementForm").onsubmit = function(e) {
            e.preventDefault();  // Prevent form refresh
            createAnnouncement(); // Call create function
        };
    };

    // Close modal when the 'x' is clicked
    closeModalBtn.onclick = function() {
        modal.style.display = "none";
        resetForm();  // Reset form when modal is closed
    };

    // Close modal when clicking outside of the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            resetForm();  // Reset form when modal is closed
        }
    };
}


// Function to create a new announcement
function createAnnouncement() {
    var formElement = document.getElementById("announcementForm");
    var formData = new FormData(formElement);
    formData.append("action", "create");

    // AJAX POST request to submit the data to announcement.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../Data/announcement.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);  // Parse the JSON response
                console.log(response.message || response.error);  // Display success/error message
                loadAnnouncements();  // Reload table with new data
                closeModal();  // Close modal after successful submission
            } catch (error) {
                console.error("Error parsing JSON: " + error);
            }
        } else {
            console.error("Error loading content: " + xhr.status + " " + xhr.statusText);
        }
    };
    xhr.send(formData);
}


// Function to edit an existing announcement
function editAnnouncement(id, announcementData) {
    var modal = document.getElementById("announcementModal");
    var submitButton = document.getElementById("submitAnnouncementBtn");

    // Populate the form with existing announcement data
    document.getElementById("title").value = announcementData.title;
    document.getElementById("message").value = announcementData.message;
    document.getElementById("target_audience").value = announcementData.target_audience;
    document.getElementById("urgency").value = announcementData.urgency;
    document.getElementById("place_tag").value = announcementData.place_tag;

    modal.style.display = "block";  // Show the modal
    submitButton.textContent = "Update Announcement";  // Change button to "Update Announcement"

    // Remove previous submit event listeners to avoid multiple bindings
    document.getElementById("announcementForm").onsubmit = null;

    // Bind the edit handler
    document.getElementById("announcementForm").onsubmit = function(e) {
        e.preventDefault();  // Prevent form from refreshing

        var updatedTitle = document.getElementById("title").value;
        var updatedMessage = document.getElementById("message").value;
        var updatedTargetAudience = document.getElementById("target_audience").value;
        var updatedUrgency = document.getElementById("urgency").value;
        var updatedPlaceTag = document.getElementById("place_tag").value;

        // Prepare the form data for editing
        var formData = "action=edit&id=" + encodeURIComponent(id) +
                       "&title=" + encodeURIComponent(updatedTitle) +
                       "&message=" + encodeURIComponent(updatedMessage) +
                       "&target_audience=" + encodeURIComponent(updatedTargetAudience) +
                       "&urgency=" + encodeURIComponent(updatedUrgency) +
                       "&place_tag=" + encodeURIComponent(updatedPlaceTag);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../Data/announcement.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response.message || response.error);
                    loadAnnouncements();  // Reload table
                    closeModal();  // Close modal
                } catch (error) {
                    console.error("Error parsing JSON: " + error);
                }
            } else {
                console.error("Error: " + xhr.statusText);
            }
        };
        xhr.send(formData);
    };
}

// Function to delete an announcement
function deleteAnnouncement(id) {
    if (confirm("Are you sure you want to delete this announcement?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../Data/announcement.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log("Announcement deleted successfully.");
                loadAnnouncements();  // Reload announcements after deletion
            } else {
                console.error("Error deleting announcement.");
            }
        };

        xhr.send("action=delete&id=" + encodeURIComponent(id));
    }
}

function loadAnnouncements() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../Data/get-announcement.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);

                // Extract announcements from the response
                var announcements = response.announcements;

                var tableBody = document.getElementById("announcementTable");

                // Clear the table body before reloading
                tableBody.innerHTML = "";

                if (announcements.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='4'>No announcements found.</td></tr>";
                    return;
                }

                // Populate the table with updated announcements
                announcements.forEach(function (announcement) {
                    var row = document.createElement("tr");

                    var titleCell = document.createElement("td");
                    titleCell.textContent = announcement.title || "No Title";
                    row.appendChild(titleCell);

                    var messageCell = document.createElement("td");
                    messageCell.textContent = announcement.message || "No Message";
                    row.appendChild(messageCell);

                    var targetAudienceCell = document.createElement("td");
                    targetAudienceCell.textContent = announcement.target_audience || "No Audience";
                    row.appendChild(targetAudienceCell);

                    var actionsCell = document.createElement("td");

                    // Edit Button
                    var editButton = document.createElement("button");
                    editButton.textContent = "Edit";
                    editButton.classList.add("editBtn");
                    editButton.onclick = function () {
                        editAnnouncement(announcement.id, announcement);
                    };

                    // Delete Button
                    var deleteButton = document.createElement("button");
                    deleteButton.textContent = "Delete";
                    deleteButton.classList.add("deleteBtn");
                    deleteButton.onclick = function () {
                        deleteAnnouncement(announcement.id);
                    };

                    actionsCell.appendChild(editButton);
                    actionsCell.appendChild(deleteButton);
                    row.appendChild(actionsCell);

                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error("Error parsing JSON response:", error);
                console.log("Raw response:", xhr.responseText);
            }
        } else {
            console.error("Error loading announcements: Status", xhr.status, xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error("Network error while loading announcements.");
    };
    xhr.send();
}

// Function to close the modal
function closeModal() {
    document.getElementById("announcementModal").style.display = "none";
}

// Function to reset form inputs
function resetForm() {
    document.getElementById("announcementForm").reset();
}

// Load announcements when the page loads
window.onload = function() {
    initializeAnnouncementModal();
    loadAnnouncements();
};
</script>
</body>
