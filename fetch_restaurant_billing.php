<?php
// Database connection details
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";

// Create a new PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch restaurant billing data
    $sql = "SELECT * FROM gestion_commande_kitchen ORDER BY date_commande DESC"; // Fetch bills ordered by date

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch all results
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send the response as JSON
    echo json_encode($bills);

} catch (PDOException $e) {
    // If there's an error, return a message
    echo json_encode(["error" => "Failed to fetch data: " . $e->getMessage()]);
}
?>
