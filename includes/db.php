<?php 

// Database connection and user count query
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calbayog_go";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die( "Connection failed: " . $conn->connect_error);
}

?>