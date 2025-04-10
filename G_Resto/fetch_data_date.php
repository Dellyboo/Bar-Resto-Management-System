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

// Get parameters
$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];

// Query for fetching data sorted by date and latest ID first
$sql = "SELECT numero_serveur, numero_table, numero_facture, statut_paiement, mode_paiement, date_commande, nom_plat, quantite, prix, (quantite * prix) AS total_price
        FROM gestion_commande_kitchen
        WHERE date_commande BETWEEN :startDate AND :endDate
        ORDER BY id DESC";  // Ordering by 'id' field in descending order

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':startDate', $startDate);
$stmt->bindParam(':endDate', $endDate);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
