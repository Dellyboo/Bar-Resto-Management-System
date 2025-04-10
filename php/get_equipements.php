<?php
$host = 'localhost'; // Change if needed
$dbname = 'g_bar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all equipment
    $stmt = $pdo->query("SELECT id, name FROM equipements");
    $equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return as JSON
    echo json_encode($equipements);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
