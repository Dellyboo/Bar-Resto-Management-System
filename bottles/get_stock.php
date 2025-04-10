<?php
header('Content-Type: application/json');

try {
    // Database connection (Modify with your actual credentials)
    $pdo = new PDO("mysql:host=localhost;dbname=g_bar", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Query to get available bottles and their quantities
    $stmt = $pdo->prepare("SELECT name, quantity FROM bottles WHERE quantity > 0");
    $stmt->execute();

    // Fetch as associative array
    $bottles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($bottles);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
