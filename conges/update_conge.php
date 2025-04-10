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

// Check if 'id' and 'statut' are provided
if (!isset($_POST["id"]) || !isset($_POST["statut"])) {
    echo json_encode(["success" => false, "message" => "ID ou statut manquant."]);
    exit();
}

// Sanitize user input
$id = $conn->real_escape_string($_POST["id"]);
$statut = $conn->real_escape_string($_POST["statut"]);

// Update the leave status in the database
$sql = "UPDATE conge SET statut = '$statut' WHERE id = $id";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour: " . $conn->error]);
}

$conn->close();
?>
