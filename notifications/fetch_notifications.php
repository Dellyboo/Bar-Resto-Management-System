<?php
// Database Connection
$host = "localhost";
$username = "root";
$password = "";
$database = "g_bar";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get last seen ID from frontend (passed as GET parameter)
$last_seen_id = isset($_GET['last_seen_id']) ? intval($_GET['last_seen_id']) : 0;

// Fetch only NEW complaints (IDs greater than last seen ID) in descending order
$sql = "SELECT id, manager_name FROM complaints WHERE id > $last_seen_id ORDER BY id DESC";
$result = $conn->query($sql);

$notifications = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            "message" => $row["manager_name"] . " a été mentionné dans le rapport soumis",
            "id" => $row["id"]
        ];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($notifications);

// Close connection
$conn->close();
?>
