<?php
include 'includes/db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Get input data and validate
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['userLat'], $data['userLon'], $data['userId'])) {
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

$userLat = $data['userLat'];
$userLon = $data['userLon'];
$userId = $data['userId'];

// Prepare SQL query
$sql = "SELECT id, name, latitude, longitude,
               (6371000 * acos(cos(radians(?)) * cos(radians(latitude)) 
               * cos(radians(longitude) - radians(?)) + sin(radians(?)) 
               * sin(radians(latitude)))) AS distance
        FROM contents
        HAVING distance <= 50";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}
$stmt->bind_param("ddd", $userLat, $userLon, $userLat);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$nearbyDestinations = [];
while ($row = $result->fetch_assoc()) {
    $nearbyDestinations[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude'],
        'distance' => $row['distance']
    ];
}

// Response
$response = [
    'visited' => !empty($nearbyDestinations),
    'nearby_destinations' => $nearbyDestinations
];

// JSON encode and output response
$jsonResponse = json_encode($response);
if ($jsonResponse === false) {
    echo json_encode(['error' => 'JSON encoding error']);
    exit;
}
echo $jsonResponse;
?>
