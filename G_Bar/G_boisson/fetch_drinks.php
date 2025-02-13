<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "g_bar";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch drinks from gestion_stock_boisson
$sql = "SELECT nom_boisson, prix FROM gestion_stock_boisson";
$result = $conn->query($sql);

$drinks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $drinks[] = $row;
    }
}

echo json_encode($drinks);
$conn->close();
?>
