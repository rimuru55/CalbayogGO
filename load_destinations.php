<?php
// load_planner.php
include 'dbconnection.php'; // Your DB connection file

// Fetch planner destinations from the database for the logged-in user
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; // Get user ID from session

    // Query to select planner destinations for the logged-in user
    $query = "SELECT destination_data FROM destination_planner WHERE user_id = ?";
    if ($stmt = $conn->prepare($query)) {
        // Bind the user ID parameter
        $stmt->bind_param("i", $userId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $stmt->bind_result($destination_data);
        if ($stmt->fetch()) {
            // Decode the stored destination data (JSON)
            $destinations = json_decode($destination_data, true);
        } else {
            // No destinations found for this user
            $destinations = [];
        }

        // Close the statement
        $stmt->close();
    } else {
        // Query failed
        $destinations = [];
    }
} else {
    // No user is logged in
    $destinations = [];
}

// Set the content type to JSON
header('Content-Type: application/json');

// Return the destinations data as a JSON response
echo json_encode($destinations);
?>
