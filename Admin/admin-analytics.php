<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="../libs/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="analytics-container">
        <!-- Section for Most Added Places -->
        <div class="most-added-places">
            <h2>Most Added Places to Favorites</h2>
            <ul id="most-added-list" class="place-list"></ul>
        </div>

        <!-- Section for New Users Bar Chart -->
        <div class="new-users-chart">
            <h2>New Users per Month</h2>
            <canvas id="userChart"></canvas>
        </div>

        <!-- Section for Most Visited Places -->
        <div class="most-visited-places">
            <h2>Most Visited Places</h2>
            <ul id="most-visited-list" class="place-list"></ul>
        </div>
    </div>

    <script>
        // Fetch and display the most added places to favorite lists
        async function loadMostAddedPlaces() {
            const response = await fetch('../Data/analytics.php?action=get_most_added_places');
            const data = await response.json();
            const listElement = document.getElementById('most-added-list');
            data.forEach(place => {
                const listItem = document.createElement('li');
                listItem.className = 'place-item';
                listItem.textContent = `${place.title} - ${place.favorite_count} times`;
                listElement.appendChild(listItem);
            });
        }

        // Fetch and render new user counts per month as a bar chart
        async function loadNewUsersChart() {
            const response = await fetch('../Data/analytics.php?action=get_new_users_per_month');
            const data = await response.json();
            const months = data.map(entry => entry.month);
            const userCounts = data.map(entry => entry.user_count);

            const ctx = document.getElementById('userChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months.reverse(),
                    datasets: [{
                        label: 'New Users',
                        data: userCounts.reverse(),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Fetch and display the most visited places
        async function loadMostVisitedPlaces() {
            const response = await fetch('../Data/analytics.php?action=get_most_visited_places');
            const data = await response.json();
            const listElement = document.getElementById('most-visited-list');
            data.forEach(place => {
                const listItem = document.createElement('li');
                listItem.className = 'place-item';
                listItem.textContent = `${place.title} - ${place.visit_count} visits`;
                listElement.appendChild(listItem);
            });
        }

        // Initialize analytics data
        loadMostAddedPlaces();
        loadNewUsersChart();
        loadMostVisitedPlaces(); // Load most visited places
    </script>
</body>
</html>
