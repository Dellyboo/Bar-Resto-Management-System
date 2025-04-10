<?php
// Database connection
$host = 'localhost';
$dbname = 'g_bar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the necessary data is provided
if (!isset($data['kitchenItems']) || !isset($data['serverNumber']) || !isset($data['tableNumber']) || !isset($data['invoiceNumber']) || !isset($data['totalPrice']) || !isset($data['paymentStatus']) || !isset($data['paymentMode'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

// Prepare the SQL insert query
$query = "INSERT INTO gestion_commande_kitchen (nom_plat, quantite, prix, total_price, numero_serveur, numero_table, numero_facture, statut_paiement, mode_paiement, date_commande) VALUES ";

$values = [];
foreach ($data['kitchenItems'] as $item) {
    $values[] = "(:nom_plat, :quantite, :prix, :total_price, :numero_serveur, :numero_table, :numero_facture, :statut_paiement, :mode_paiement, NOW())";
}

$query .= implode(", ", $values);

try {
    // Prepare the statement
    $stmt = $pdo->prepare($query);

    // Bind the values for each item
    $index = 0;
    foreach ($data['kitchenItems'] as $item) {
        // Bind each value for the current item in the loop
        $stmt->bindValue(":nom_plat", $item['dish']);
        $stmt->bindValue(":quantite", $item['quantity']);
        $stmt->bindValue(":prix", $item['unitPrice']);
        $stmt->bindValue(":total_price", $item['total']);
        $stmt->bindValue(":numero_serveur", $data['serverNumber']);
        $stmt->bindValue(":numero_table", $data['tableNumber']);
        $stmt->bindValue(":numero_facture", $data['invoiceNumber']);
        $stmt->bindValue(":statut_paiement", $data['paymentStatus']);
        $stmt->bindValue(":mode_paiement", $data['paymentMode']);
        $index++;
    }

    // Execute the query
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error inserting data: ' . $e->getMessage()]);
}

?>
