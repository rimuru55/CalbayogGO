<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$destinationId = $_POST['destinationId'];

// Insert or update the visited place
$insertQuery = $conn->prepare("
    INSERT INTO visited_places (user_id, content_id, first_visited_date, last_visited_date)
    VALUES (?, ?, NOW(), NOW())
    ON DUPLICATE KEY UPDATE
        last_visited_date = NOW()
");
$insertQuery->bind_param('ii', $userId, $destinationId);

if ($insertQuery->execute()) {
    echo json_encode(['success' => true, 'message' => 'Visited place logged or updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error logging or updating visited place']);
}

$insertQuery->close();
$conn->close();
?>
