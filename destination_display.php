<?php
// destination_display.php

// Assuming user authentication and the user ID is available
session_start();
$userId = $_SESSION['user_id']; // Get the actual user ID from session

// Database connection
include 'includes/db.php'; // Assuming dbconnection.php has the database connection

// Query to retrieve the destination data for the user
$query = "SELECT destination_data FROM destination_planner WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($destinationData);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
    $plannerDestinations = json_decode($destinationData, true); // Decode the JSON to an array
} else {
    $plannerDestinations = [];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Travel Planner</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
/* General Styles */
body, html {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

.travel-planner-header {
    text-align: center;
    color: #333;
    margin-top: 20px;
}

/* Map and Side Panel Layout */
.travel-planner-map {
    height: 100vh; /* Full viewport height */
    width: 60%;
}

.travel-planner-side-panel {
    float: right;
    position: relative;
    width: 35%;
    padding: 20px;
    height: 100vh;
    overflow-y: auto; /* Enable scrolling */
    background-color: #f8f9fa; /* Light background color */
    box-shadow: -5px 0 5px -5px #333; /* Subtle shadow to separate from map */
}

.destination-list {
    list-style-type: none;
    padding: 0;
}

.destination-list li {
    margin-bottom: 15px;
    padding: 10px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.destination-list li:hover, .selected {
    background-color: #e9ecef; /* Highlight on hover and selection */
}

.route-button {
    padding: 8px 16px;
    margin-left: 10px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.route-button:hover {
    background-color: #0056b3;
}

/* Responsiveness for smaller screens */
@media (max-width: 768px) {
    .travel-planner-map, .travel-planner-side-panel {
        width: 100%;
        float: none;
    }

    .travel-planner-map {
        height: 60vh; /* Adjust height for smaller devices */
    }

    .travel-planner-side-panel {
        height: auto;
    }
}

/* Utility Classes */
.hidden {
    display: none;
}

.show {
    display: block;
}

    </style>
</head>
<body>

<h1 class="travel-planner-header">My Travel Planner</h1>

<div id="map" class="travel-planner-map"></div>
<div id="side-panel" class="travel-planner-side-panel">
    <h3 class="side-panel-title">Destinations</h3>
    <ul id="planner-list" class="destination-list"></ul>
    <button id="getRouteBtn" class="route-button">Get Route</button>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="scripts/travel-planner.js"></script> <!-- Link to the external JS file -->
</body>

<script>
// PHP: Encode the PHP array of destinations into a JSON object for JavaScript
let plannerDestinations = <?php echo json_encode($plannerDestinations); ?>;

// Initialize the Leaflet map
const map = L.map('map').setView([12.251263, 124.399251], 13); // Center map to a default location

// Set up the tile layer (map background)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add markers to the map
function addMarkers() {
    plannerDestinations.forEach(destination => {
        const { title, latitude, longitude } = destination;
        const marker = L.marker([latitude, longitude]).addTo(map);
        marker.bindPopup(`<b>${title}</b><br>Latitude: ${latitude}<br>Longitude: ${longitude}`);
    });
}

// Display destinations in the side panel list
function updatePlannerList() {
    const plannerList = document.getElementById("planner-list");
    plannerList.innerHTML = ""; // Clear existing planner items

    plannerDestinations.forEach((destination, index) => {
        const destinationItem = document.createElement("li");
        const selectedClass = destination.selected ? 'selected' : '';
        destinationItem.className = selectedClass;

        destinationItem.innerHTML = `
            <span>${destination.title}</span>
            <button onclick="selectDestination(${index})">Select</button>
        `;
        plannerList.appendChild(destinationItem);
    });
}

// Store selected destinations for route calculation
let selectedDestinations = [];
function selectDestination(index) {
    const destination = plannerDestinations[index];
    if (destination.selected) {
        // If already selected, deselect it
        destination.selected = false;
        selectedDestinations = selectedDestinations.filter(d => d !== destination);
    } else {
        // Otherwise, select it
        destination.selected = true;
        selectedDestinations.push(destination);
    }

    // Update the UI list to reflect the selection
    updatePlannerList();
}

// Geolocation: Get user's current location
let userLocation = null;
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            userLocation = [position.coords.latitude, position.coords.longitude];
            L.marker(userLocation).addTo(map).bindPopup("You are here").openPopup();
            map.setView(userLocation, 13); // Zoom to user's location
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

// Get route from user's location to selected destinations using Leaflet Routing Machine
function getRoute() {
    if (!userLocation || selectedDestinations.length === 0) {
        alert("Please select at least one destination and ensure location is available.");
        return;
    }

    // Create waypoints array with the user's location and selected destinations
    const waypoints = [L.latLng(userLocation[0], userLocation[1])];
    selectedDestinations.forEach(destination => {
        waypoints.push(L.latLng(destination.latitude, destination.longitude));
    });

    // Create the route control with multiple waypoints
    const routeControl = L.Routing.control({
        waypoints: waypoints,
        routeWhileDragging: true,
        geocoder: L.Control.Geocoder.nominatim() // Use Nominatim geocoder for address lookup
    }).addTo(map);
}

// Button to trigger route generation
document.getElementById("getRouteBtn").addEventListener("click", getRoute);

// Initialize the map and markers
document.addEventListener("DOMContentLoaded", () => {
    addMarkers();
    updatePlannerList();
    getUserLocation(); // Fetch user's location on page load
});
</script>

</body>
</html>
