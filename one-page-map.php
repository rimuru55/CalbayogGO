<?php
include 'includes/header.php'; 
?>

    <div class="index-map">
            <div class="custom-map-search-container">
                <form action="Data/search.php" method="GET" class="custom-map-search-form">
                    <input type="text" name="q" class="custom-map-search-input" placeholder="Search Places" autocomplete="off">
                    <button type="submit" class="custom-map-search-button">
                        <i class="fa-light fa-magnifying-glass"></i>                    
                    </button>
                </form>
                <button class="custom-map-planner">
                    <i class="fa-light fa-diamond-turn-right"></i>      
                </button> 
            </div>
            <div id="results-container" class="results-container"></div>
        <div class="directions-panel">
            <input type="text" id="start-input" placeholder="Choose Starting Point" autocomplete="off">
            <input type="text" id="end-input" placeholder="Choose destination, or click on the map..." autocomplete="off">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="destination-planner custom-destination-planner" id="destination-planner">
                    <h2>Your Destination Planner</h2>
                    <ul id="planner-list" class="planner-list">
                        <!-- Dynamic destination items will be added here -->
                    </ul>
                    <button id="save-planner" onclick="savePlanner()">Save Planner</button>
                </div>
            <?php endif; ?>
        </div>
        <div id="map"></div>
    </div>
    <script>

    
let plannerDestinations = [];

// Function to add destination to planner with coordinates
function addDestination(title, id, latitude, longitude) {
    const isDuplicate = plannerDestinations.some(destination => 
        destination.title === title && destination.latitude === latitude && destination.longitude === longitude
    );

    if (!isDuplicate) {
        plannerDestinations.push({ title, id, latitude, longitude });
        updatePlannerUI();
    } else {
        alert('This destination is already in your planner!');
    }
}

// Update the planner UI
function updatePlannerUI() {
    const plannerList = document.getElementById("planner-list");
    plannerList.innerHTML = "";

    plannerDestinations.forEach((destination, index) => {
        const destinationItem = document.createElement("li");
        destinationItem.className = "planner-item";
        destinationItem.innerHTML = `
            <span>${destination.title}</span>
            <div class="order_button">
                <button onclick="moveUp(${index})"><i class="fas fa-arrow-up"></i></button>
                <button onclick="moveDown(${index})"><i class="fas fa-arrow-down"></i></button>
                <button onclick="removeDestination(${index})"><i class="fa-sharp fa-solid fa-xmark"></i></button>
            </div>
        `;
        plannerList.appendChild(destinationItem);
    });
}

// Function to remove a destination from the planner
function removeDestination(index) {
    plannerDestinations.splice(index, 1);
    updatePlannerUI();
}

// Move destination up in the planner list
function moveUp(index) {
    if (index > 0) {
        [plannerDestinations[index], plannerDestinations[index - 1]] = [plannerDestinations[index - 1], plannerDestinations[index]];
        updatePlannerUI();
    }
}

// Move destination down in the planner list
function moveDown(index) {
    if (index < plannerDestinations.length - 1) {
        [plannerDestinations[index], plannerDestinations[index + 1]] = [plannerDestinations[index + 1], plannerDestinations[index]];
        updatePlannerUI();
    }
}

function savePlanner() {
    // Log the plannerDestinations to the console (for debugging purposes)
    console.log("Saved Planner: ", plannerDestinations);

    // Send plannerDestinations to the server via AJAX (POST request)
    $.ajax({
        url: 'save_planner.php',  // The URL of the PHP script
        type: 'POST',             // Method to send data
        contentType: 'application/json',  // Sending JSON format
        data: JSON.stringify({     // Convert plannerDestinations to JSON
            plannerDestinations: plannerDestinations
        }),
        success: function(response) {
            // Handle the server response
            const result = JSON.parse(response);
            if (result.success) {
                alert('Planner saved successfully!');
            } else {
                alert('Error saving planner: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error occurred: ' + error);
            alert('An error occurred while saving the planner.');
        }
    });
}

// Function to toggle the filter dropdown
function toggleFilterDropdown() {
    const dropdown = document.getElementById("filter-dropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

// Function to filter by category
function filterByCategory(category) {
    const placeItems = document.querySelectorAll('.place-item');
    placeItems.forEach(item => {
        if (category === 'all' || item.getAttribute('data-category') === category) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Function to load the saved planner from the database
function loadPlanner(userId) {
    $.ajax({
        url: 'load_planner.php',  // The PHP script that loads the planner
        type: 'GET',              // GET request
        data: { userId: userId }, // Send userId to fetch their saved planner
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                // If planner data is found, update the UI
                plannerDestinations = JSON.parse(result.plannerDestinations);
                updatePlannerUI();
            } else {
                alert('No saved planner found for this user.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error occurred: ' + error);
            alert('An error occurred while loading the planner.');
        }
    });
}

// Call the loadPlanner function when the page is loaded (if userId is available)
$(document).ready(function() {
    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    if (userId) {
        loadPlanner(userId);
    }
});

$(document).ready(function() {
    // Check if the user is logged in
    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

    // Hide the destination planner if no user is logged in
    if (!userId) {
        $('#destination-planner').hide();
    }

    // Load the planner if user is logged in
    if (userId) {
        loadPlanner(userId);
    }
});

// Function to check if the user is near a destination (within 50 meters)
function checkProximity(userLatitude, userLongitude, destinationLatitude, destinationLongitude) {
    const R = 6371000; // Radius of Earth in meters

    const dLat = (destinationLatitude - userLatitude) * Math.PI / 180;
    const dLon = (destinationLongitude - userLongitude) * Math.PI / 180;

    const lat1 = userLatitude * Math.PI / 180;
    const lat2 = destinationLatitude * Math.PI / 180;

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = R * c; // Distance in meters
    return distance <= 5000000;  // Check if within 50 meters
}

// Function to check user's current location and compare with planner destinations
function trackUserProximity() {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        return;
    }

    navigator.geolocation.watchPosition(position => {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;

        plannerDestinations.forEach(destination => {
            const destLat = destination.latitude;
            const destLng = destination.longitude;

            // Check if the user is within 50 meters of the destination
            if (checkProximity(userLat, userLng, destLat, destLng)) {
                // Log the visit to the server
                logVisitedPlace(destination.id);
            }
        });
    }, error => {
        console.error("Error getting geolocation: ", error.message);
    }, {
        enableHighAccuracy: true,
        maximumAge: 10000,
        timeout: 5000
    });
}

// Function to send the visited place to the server
function logVisitedPlace(destinationId) {
    $.ajax({
        url: 'log_visited_place.php',  // PHP script to handle logging
        type: 'POST',
        data: { destinationId: destinationId },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                console.log("Visited place logged for destination ID:", destinationId);            
            } else {
                console.error('Error logging visited place: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('An error occurred while logging visited place: ' + error);
        }
    });
}

// Start tracking proximity when the page is loaded
$(document).ready(function() {
    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    if (userId) {
        trackUserProximity(); // Only track if user is logged in
    }
});

document.querySelector('.custom-map-search-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the form from submitting in the traditional way
    
    const query = document.querySelector('.custom-map-search-input').value;
    const url = `Data/search.php?q=${encodeURIComponent(query)}`;

    fetch(url)
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log the data to see what's coming back from the server
        displayResults(data);
    })
    .catch(error => console.error('Error fetching data:', error));
});
function displayResults(data) {
    const resultsContainer = document.getElementById('results-container');
    resultsContainer.innerHTML = ''; // Clear previous results

    if (data.length > 0) {
        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'result-item';

            div.innerHTML = `
                <div class="result-item-content">
                    <img src="${item.cover_photo}" alt="${item.title}" class="result-item-image">
                    <div class="result-item-text">
                        <h3 class="result-item-title">${item.title}</h3>
                        <p class="result-item-address">${item.address}</p>
                        <button class="add-to-planner-btn">Add to Planner</button>
                    </div>
                </div>
            `;

            resultsContainer.appendChild(div);

            // Event listener to center map on this location when the content is clicked
            const content = div.querySelector('.result-item-content');
            content.addEventListener('click', () => {
                if (map && typeof map.flyTo === 'function') {
                    map.flyTo({
                        center: [item.longitude, item.latitude],
                        zoom: 15
                    });
                } else {
                    console.error('Map instance not ready or flyTo function not available');
                }
            });
        });
    } else {
        resultsContainer.innerHTML = '<p>No results found.</p>'; // Display if no data
    }
}

function showLocationOnMap(item) {
    // Assuming you have a Mapbox map instance called 'map'
    map.flyTo({
        center: [item.longitude, item.latitude], // Mapbox uses [longitude, latitude] order
        zoom: 15  // Adjust zoom level as needed
    });
}
function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

// Function to handle the search query
function handleSearchInput() {
    const query = document.querySelector('.custom-map-search-input').value;
    if (query.length >= 2) { // Only search if at least 2 characters are typed
        const url = `Data/search.php?q=${encodeURIComponent(query)}`;

        fetch(url)
        .then(response => response.json())
        .then(data => {
            displayResults(data);
        })
        .catch(error => console.error('Error fetching data:', error));
    } else {
        // Clear results if query is less than 2 characters
        displayResults([]);
    }
}

// Add event listener for the search input with debouncing
document.querySelector('.custom-map-search-input').addEventListener('input', debounce(handleSearchInput, 300));


document.querySelector('.custom-map-planner').addEventListener('click', function() {
        const planner = document.getElementById('destination-planner');
        
        // Toggle visibility
        if (planner.style.display === 'none' || planner.style.display === '') {
            planner.style.display = 'block';  // Show the planner
        } else {
            planner.style.display = 'none';   // Hide the planner
        }
    });
</script>