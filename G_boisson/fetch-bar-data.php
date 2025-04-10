<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('database.php'); // Ensure this is correct

$input = json_decode(file_get_contents("php://input"), true);
$sortBy = isset($input['sort_by']) ? $input['sort_by'] : '';
$dateSelected = isset($input['date_selected']) ? $input['date_selected'] : '';
$dateStart = isset($input['date_start']) ? $input['date_start'] : '';
$dateEnd = isset($input['date_end']) ? $input['date_end'] : '';
$boissonName = isset($input['boisson_name']) ? $input['boisson_name'] : '';

try {
    if (!empty($boissonName)) {
        // Fetch data sorted by boisson name
        $query = "SELECT id, nom_boisson, quantite, prix, (quantite * prix) AS total_price, 
                         numero_serveur, numero_table, numero_facture, mode_paiement, statut_paiement, date_commande
                  FROM gestion_commande_boisson
                  WHERE nom_boisson = :boisson_name";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':boisson_name', $boissonName);
    } elseif ($sortBy == 'date' && !empty($dateSelected)) {
        // Fetch data sorted by a specific date
        $query = "SELECT id, nom_boisson, quantite, prix, (quantite * prix) AS total_price, 
                         numero_serveur, numero_table, numero_facture, mode_paiement, statut_paiement, date_commande
                  FROM gestion_commande_boisson
                  WHERE DATE(date_commande) = :date_selected";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':date_selected', $dateSelected);
    } elseif ($sortBy == 'date' && !empty($dateStart) && !empty($dateEnd)) {
        // Fetch data sorted by date range
        $query = "SELECT id, nom_boisson, quantite, prix, (quantite * prix) AS total_price, 
                         numero_serveur, numero_table, numero_facture, mode_paiement, statut_paiement, date_commande
                  FROM gestion_commande_boisson
                  WHERE DATE(date_commande) BETWEEN :date_start AND :date_end";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':date_start', $dateStart);
        $stmt->bindParam(':date_end', $dateEnd);
    } elseif ($sortBy == 'serveur') {
        // Fetch data sorted by server
        $query = "SELECT id, numero_serveur, nom_boisson, quantite, prix, (quantite * prix) AS total_price, 
                         numero_table, numero_facture, mode_paiement, statut_paiement, date_commande
                  FROM gestion_commande_boisson";
        $stmt = $conn->prepare($query);
    } else {
        // Invalid sorting criteria
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
        exit;
    }

    // Execute the query
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    if ($data) {
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data found']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
