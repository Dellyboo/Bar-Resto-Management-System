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

// Receive data from AJAX
$employe = $_POST["employe"];
$type = $_POST["type"];
$date_debut = $_POST["date_debut"];
$date_fin = $_POST["date_fin"];
$total_jours = $_POST["total_jours"];
$raison = $_POST["raison"]; // NEW: Get the reason

// Insert into database
$sql = "INSERT INTO conge (employe, type, date_debut, date_fin, total_jours, raison) 
        VALUES ('$employe', '$type', '$date_debut', '$date_fin', '$total_jours', '$raison')";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur: " . $conn->error]);
}

$conn->close();
?>
