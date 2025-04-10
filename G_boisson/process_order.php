<?php
// process_order.php

// Database connection
$host = 'localhost';
$dbname = 'g_bar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"));

    // Extract data from the POST request
    $tableNumber = $data->tableNumber;
    $serverNumber = $data->serverNumber;
    $totalPrice = $data->totalPrice;
    $paymentStatus = $data->paymentStatus;
    $paymentMode = $data->paymentMode;
    $drinks = $data->drinks;

    // Generate a unique invoice number (example format: BOIS#123456)
    $invoiceNumber = 'BOIS#' . rand(100000, 999999); // Simple example, use a better approach for production

    // Insert data into the database for each drink order
    foreach ($drinks as $drink) {
        $nomBoisson = $drink->drink;
        $quantite = $drink->quantity;
        $prix = $drink->unitPrice;
        $total = $drink->total;

        // Insert the order into gestion_commande_boisson
        $sql = "INSERT INTO gestion_commande_boisson 
                (nom_boisson, quantite, prix, total_price, numero_serveur, numero_table, numero_facture, statut_paiement, mode_paiement, date_commande) 
                VALUES 
                (:nom_boisson, :quantite, :prix, :total_price, :numero_serveur, :numero_table, :numero_facture, :statut_paiement, :mode_paiement, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom_boisson', $nomBoisson);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':total_price', $total);
        $stmt->bindParam(':numero_serveur', $serverNumber);
        $stmt->bindParam(':numero_table', $tableNumber);
        $stmt->bindParam(':numero_facture', $invoiceNumber);
        $stmt->bindParam(':statut_paiement', $paymentStatus);
        $stmt->bindParam(':mode_paiement', $paymentMode);
        $stmt->execute();

        // Reduce stock quantity in gestion_stock_boisson
        $updateStockSQL = "UPDATE gestion_stock_boisson 
                           SET quantite_stock = quantite_stock - :quantite 
                           WHERE nom_boisson = :nom_boisson";

        $updateStmt = $pdo->prepare($updateStockSQL);
        $updateStmt->bindParam(':quantite', $quantite);
        $updateStmt->bindParam(':nom_boisson', $nomBoisson);
        $updateStmt->execute();
    }

    // Return success response
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
