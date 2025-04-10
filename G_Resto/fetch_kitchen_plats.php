<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "";
$database = "g_bar";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->query("SELECT nom_ingredient, prix_achat FROM gestion_stock_kitchen");
    $plats = $stmt->fetchAll();

    echo json_encode($plats);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
}
?>
