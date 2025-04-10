<?php
header('Content-Type: application/json'); // Set JSON response

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "g_bar";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "❌ Erreur de connexion: " . $conn->connect_error]);
    exit;
}

$sql = "DELETE FROM bottles_report";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "✅ Historique supprimé avec succès!"]);
} else {
    echo json_encode(["status" => "error", "message" => "❌ Erreur lors de la suppression: " . $conn->error]);
}

$conn->close();
?>
