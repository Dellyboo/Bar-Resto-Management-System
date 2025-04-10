<?php
// Database connection details
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";

// Get the raw POST data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Check if the necessary data is present
if (isset($data['numero_facture'], $data['statut_paiement'], $data['mode_paiement'])) {
    $numero_facture = $data['numero_facture'];
    $statut_paiement = $data['statut_paiement'];
    $mode_paiement = $data['mode_paiement'];

    // Create a new PDO instance for database connection
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the payment status and payment mode in the database
        $sql = "UPDATE gestion_commande_kitchen 
                SET statut_paiement = :statut_paiement, mode_paiement = :mode_paiement 
                WHERE numero_facture = :numero_facture";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':statut_paiement', $statut_paiement);
        $stmt->bindParam(':mode_paiement', $mode_paiement);
        $stmt->bindParam(':numero_facture', $numero_facture);

        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Payment status updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "No changes made or facture not found"]);
        }

    } catch (PDOException $e) {
        // If there's an error, return a message
        echo json_encode(["error" => "Failed to update payment status: " . $e->getMessage()]);
    }

} else {
    // If the necessary data is not provided, return an error message
    echo json_encode(["error" => "Missing required data"]);
}
?>
