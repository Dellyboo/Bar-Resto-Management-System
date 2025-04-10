<?php
// Database connection
$host = 'localhost';
$db = 'g_bar';
$user = 'root';
$pass = '';
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Fetch all categories (without pagination)
$sql = "SELECT id, name FROM cuisine_categorie ORDER BY name ASC";
$stmt = $pdo->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the data
$response = [
    'success' => true,
    'data' => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>
