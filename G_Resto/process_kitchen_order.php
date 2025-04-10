<?php
// process_kitchen_order.php

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
    $kitchenOrders = $data->kitchenOrders;

    // Generate a unique invoice number (example format: CUIS#123456)
    $invoiceNumber = 'CUIS#' . rand(100000, 999999); // Simple example, use a better approach for production

    // Insert data into the database for each dish order
    foreach ($kitchenOrders as $order) {
        $sql = "INSERT INTO gestion_commande_cuisine 
                (nom_plat, quantite, prix, total_price, numero_serveur, numero_table, numero_facture, statut_paiement, mode_paiement, date_commande) 
                VALUES 
                (:nom_plat, :quantite, :prix, :total_price, :numero_serveur, :numero_table, :numero_facture, :statut_paiement, :mode_paiement, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom_plat', $order->dish);
        $stmt->bindParam(':quantite', $order->quantity);
        $stmt->bindParam(':prix', $order->unitPrice);
        $stmt->bindParam(':total_price', $order->total);
        $stmt->bindParam(':numero_serveur', $serverNumber);
        $stmt->bindParam(':numero_table', $tableNumber);
        $stmt->bindParam(':numero_facture', $invoiceNumber);
        $stmt->bindParam(':statut_paiement', $paymentStatus);
        $stmt->bindParam(':mode_paiement', $paymentMode);

        $stmt->execute();
    }

    // Return success response
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
