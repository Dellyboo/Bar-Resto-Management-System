<?php
header('Content-Type: application/json');
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
if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Invalid request"]);
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM complaints WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);
?>
