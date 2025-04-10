<?php
// Database connection
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Erreur de connexion"]));
}

// Fetch data
$sql = "SELECT * FROM conge ORDER BY date_debut DESC";
$result = $conn->query($sql);

$leaveRequests = [];
while ($row = $result->fetch_assoc()) {
    $leaveRequests[] = $row;
}

echo json_encode($leaveRequests);

$conn->close();
?>
