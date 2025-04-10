<?php
header('Content-Type: application/json'); // Set JSON header

// Database connection
$host = "localhost"; // Change if using a different host
$username = "root";  // Change to your MySQL username
$password = "";      // Change to your MySQL password
$database = "g_bar"; // Change to your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "SELECT * FROM complaints ORDER BY submitted_at DESC";
$result = $conn->query($sql);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
