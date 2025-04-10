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

// Get dishName parameter
$dishName = $_GET['dishName'];

// Query for fetching data sorted by dish name and latest ID first
$sql = "SELECT numero_serveur, numero_table, numero_facture, statut_paiement, mode_paiement, date_commande, nom_plat, quantite, prix, (quantite * prix) AS total_price
        FROM gestion_commande_kitchen
        WHERE nom_plat = :dishName
        ORDER BY id DESC";  // Ordering by 'id' field in descending order

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':dishName', $dishName);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
