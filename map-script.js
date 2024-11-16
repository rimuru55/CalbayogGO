document.addEventListener("DOMContentLoaded", function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiY29kZXg1NSIsImEiOiJjbTJkajJjbzQwZTh6MnhzYmdzdjk0bzNhIn0.86KHel6qwG6wlZB0CqanJQ';
    
    const startInput = document.getElementById('start-input');
    const suggestions = document.getElementById('start-suggestions');

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [124.595390, 12.067878], // Default center
        zoom: 12
    });

        // Setup the geocoder for the start input
    const startGeocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
    });

        // Setup the geocoder for the end input
    const endGeocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
    });

    document.getElementById('start-input').appendChild(startGeocoder.onAdd(map));
    document.getElementById('end-input').appendChild(endGeocoder.onAdd(map));

        // Show suggestions when the input is focused
    startInput.addEventListener('focus', function() {
        populateSuggestions();
        suggestions.style.display = 'block';
    });

    // Hide suggestions when the input is blurred
    startInput.addEventListener('blur', function() {
        setTimeout(() => suggestions.style.display = 'none', 200); // Delay to allow click event to register
    });

    function populateSuggestions() {
        suggestions.innerHTML = ''; // Clear previous suggestions

        // Add "Your Location" suggestion
        const yourLocationDiv = document.createElement('div');
        yourLocationDiv.textContent = 'Your Location';
        yourLocationDiv.addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const coords = [position.coords.longitude, position.coords.latitude];
                    map.flyTo({
                        center: coords,
                        essential: true
                    });
                    directions.setOrigin(coords);
                    startInput.value = 'Your Location';
                });
            }
        });
        suggestions.appendChild(yourLocationDiv);

        // Load recent searches from localStorage or another store
        const recentSearches = getRecentSearches(); // Assume function to fetch recent searches
        recentSearches.forEach(search => {
            const div = document.createElement('div');
            div.textContent = search;
            div.addEventListener('click', function() {
                startInput.value = search;
                startGeocoder.query(search);
            });
            suggestions.appendChild(div);
        });
    }

    function getRecentSearches() {
        // Retrieve and parse recent searches from localStorage
        const searches = localStorage.getItem('recentSearches');
        return searches ? JSON.parse(searches) : [];
    }

        // Listen for result events from geocoders to update directions
    startGeocoder.on('result', function(e) {
        directions.setOrigin([e.result.geometry.coordinates[0], e.result.geometry.coordinates[1]]);
    });

    endGeocoder.on('result', function(e) {
        directions.setDestination([e.result.geometry.coordinates[0], e.result.geometry.coordinates[1]]);
    });

        // Optional: Respond to map clicks to set destination
    map.on('click', function(e) {
        const coords = e.lngLat;
        directions.setDestination([coords.lng, coords.lat]);
        // Automatically populate the end input if needed:
        document.getElementById('end-input').value = `Lng: ${coords.lng}, Lat: ${coords.lat}`;
    });


    // Add geolocate control to the map.
    const geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true,
        showUserLocation: true
    });

    map.addControl(geolocateControl, 'top-right');

    // Add directions control
    const directions = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/driving',
        geometries: 'geojson',
        controls: { instructions: false }
    });

    // Listen to geolocation events
    geolocateControl.on('geolocate', function(e) {
        let lng = e.coords.longitude;
        let lat = e.coords.latitude;
        console.log(`User's current location: Longitude: ${lng}, Latitude: ${lat}`);
        map.flyTo({
            center: [lng, lat],
            essential: true
        });
    });

    // Listen to directions events to handle waypoints
    directions.on('route', (e) => {
        console.log('Route details:', e.route); // Log route details for debugging
    });

    // Trigger geolocation on load
    map.on('load', function() {
        geolocateControl.trigger();
        addMarkers();
    });

// Function to add markers to the map within 10 km range
function addMarkers() {
    // First, get the user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Fetch the marker data
            fetch('Data/contents.php?action=get_all')
                .then(response => response.json())
                .then(data => {
                    data.forEach(content => {
                        // Calculate distance using the Haversine formula
                        const markerLat = content.latitude;
                        const markerLng = content.longitude;
                        const distance = haversineDistance(userLat, userLng, markerLat, markerLng);

                        // Only add the marker if it's within 10 km of the user's location
                        if (distance <= 50) {
                            const marker = new mapboxgl.Marker()
                                .setLngLat([markerLng, markerLat])
                                .addTo(map);

                            // Create a button to add the destination
                            const addButton = document.createElement('button');
                            addButton.innerHTML = 'Add to Planner'; // Button text
                            addButton.classList.add('add-destination-btn'); // Optional styling class

                            // Add click event to the button
                            addButton.addEventListener('click', () => {
                                // Call addDestination function when the button is clicked
                                addDestination(content.title, content.id, content.latitude, content.longitude);
                            });

                            // Create popup content
                            const popupContent = document.createElement('div');
                            const titleElement = document.createElement('h3');
                            titleElement.textContent = content.title;
                            const descriptionElement = document.createElement('p');
                            descriptionElement.textContent = content.description;

                            // Append the title, description, and button to the popup content div
                            popupContent.appendChild(titleElement);
                            popupContent.appendChild(descriptionElement);
                            popupContent.appendChild(addButton);

                            // Create the popup and set its content
                            const popup = new mapboxgl.Popup()
                                .setMaxWidth("300px")
                                .setDOMContent(popupContent);  // Set the popup content to the created div

                            // Attach the popup to the marker
                            marker.setPopup(popup);
                        }
                    });
                })
                .catch(error => console.error('Error loading the content data:', error));
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

// Function to calculate the Haversine distance between two points in km
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in km
    const dLat = (lat2 - lat1) * (Math.PI / 180);
    const dLon = (lon2 - lon1) * (Math.PI / 180);
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // Distance in km
}


// Helper function to check if a destination with the same ID already exists
function isDestinationDuplicate(id) {
    return plannerDestinations.some(destination => destination.id === id);
}

// Function to update the planner UI
function updatePlannerUI() {
    const plannerList = document.getElementById('planner-list');
    plannerList.innerHTML = "";  // Clear the current planner items

    // If there are no destinations, display a message
    if (plannerDestinations.length === 0) {
        plannerList.innerHTML = "<li>No destinations added yet.</li>";
    } else {
        plannerDestinations.forEach((destination, index) => {
            const listItem = document.createElement('li');
            listItem.className = 'planner-item';
            listItem.innerHTML = `
                <span>${destination.title}</span>
                <button onclick="removeDestination(${index})">Remove</button>
            `;
            plannerList.appendChild(listItem);
        });
    }
}

// Function to remove a destination from the planner
function removeDestination(index) {
    plannerDestinations.splice(index, 1);
    updatePlannerUI();  // Re-update the planner UI after removal
}


    
});
