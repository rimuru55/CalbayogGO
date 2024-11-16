<?php
include 'includes/db.php'; // Database connection

$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;

$stmt = $conn->prepare("SELECT destination_data FROM destination_planner WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "plannerDestinations" => $row['destination_data']]);
} else {
    echo json_encode(["success" => false, "message" => "No planner data found"]);
}

$stmt->close();
$conn->close();
?>
