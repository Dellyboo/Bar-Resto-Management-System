<?php
// Database connection
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Query to fetch distinct dish names, ordered by the latest added dish
$sql = "SELECT DISTINCT nom_plat
        FROM gestion_commande_kitchen
        ORDER BY id DESC";  // Ordering by 'id' field in descending order

$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
