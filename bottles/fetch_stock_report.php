<?php
header("Content-Type: application/json");

// Database connection details
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch stock report data, ordered by the latest id first
    $stmt = $pdo->prepare("SELECT received_bottle_name, provider_name, received_quantity, given_quantity, provider_company, date_reported 
                           FROM bottles_report 
                           ORDER BY id DESC"); // Ordering by id in descending order to fetch the latest records first
    $stmt->execute();
    $stockReport = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data in JSON format
    echo json_encode($stockReport);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion: " . $e->getMessage()]);
}
?>
