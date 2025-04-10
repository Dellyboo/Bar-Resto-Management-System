<?php
require '../database.php'; // Adjust to your database connection file

header('Content-Type: application/json');

try {
    // Prepare SQL query
    $sql = "SELECT DISTINCT nom_boisson FROM gestion_commande_boisson";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Fetch results
    $boissonNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($boissonNames)) {
        echo json_encode(['status' => 'success', 'names' => $boissonNames]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No boisson names found']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
