<?php
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

    // Check if data is received
    if (!$data || empty($data->kitchenOrders)) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    // Extract order details
    $tableNumber = $data->tableNumber ?? 'N/A';
    $serverNumber = $data->serverNumber ?? 'N/A';
    $totalPrice = $data->totalPrice ?? 0;
    $paymentStatus = $data->paymentStatus ?? 'pending';
    $paymentMode = $data->paymentMode ?? 'N/A';
    $kitchenOrders = $data->kitchenOrders;

    // Generate a unique invoice number
    $invoiceNumber = 'CUIS#' . rand(100000, 999999);

    // Insert each item into the database
    foreach ($kitchenOrders as $order) {
        $sql = "INSERT INTO gestion_commande_kitchen 
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
    echo json_encode(['success' => true, 'message' => 'Order successfully recorded']);

} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
