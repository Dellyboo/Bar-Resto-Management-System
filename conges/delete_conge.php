<?php
// Database connection details
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Erreur de connexion"]));
}

// Check if 'id' is provided
if (!isset($_POST["id"])) {
    echo json_encode(["success" => false, "message" => "ID manquant."]);
    exit();
}

// Sanitize user input
$id = $conn->real_escape_string($_POST["id"]);

// Delete the leave request from the database
$sql = "DELETE FROM conge WHERE id = $id";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la suppression: " . $conn->error]);
}

$conn->close();
?>
