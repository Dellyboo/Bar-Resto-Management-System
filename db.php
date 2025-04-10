<?php
// db.php - Database connection and fetching drinks

// Database connection parameters
$host = 'localhost'; // Your database host
$dbname = 'g_bar';   // Your database name
$username = 'root';   // Your database username
$password = '';       // Your database password

try {
    // Establish connection with the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}

// Fetch only 'nom_boisson' and 'prix_achat' from the database
$query = "SELECT nom_boisson, prix_achat FROM gestion_stock_boisson";
$stmt = $pdo->query($query);
$drinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
