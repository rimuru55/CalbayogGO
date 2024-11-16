<?php
session_start();
include 'includes/db.php'; // Ensure this file has your DB connection logic

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate if plannerDestinations is received
    if (!isset($data['plannerDestinations'])) {
        echo json_encode(['success' => false, 'message' => 'No destinations provided']);
        exit();
    }
    
    $plannerDestinations = $data['plannerDestinations'];
    $userId = $_SESSION['user_id']; // Use the userId from the session

    // Assuming you are saving the planner destinations in a table like 'destination_planner'
    $query = "INSERT INTO destination_planner (user_id, destination_data) VALUES (?, ?) 
              ON DUPLICATE KEY UPDATE destination_data = ?";  // Handles insert or update if the user already has an entry
    
    // Prepare the SQL statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters
        $encodedDestinations = json_encode($plannerDestinations); // Encode destinations as JSON
        $stmt->bind_param("iss", $userId, $encodedDestinations, $encodedDestinations); // Bind the values
        
        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving planner']);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}
?>
