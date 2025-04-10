<?php
header('Content-Type: application/json');
// Include database connection file
include_once('database.php');

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT * FROM gestion_commande_boisson ORDER BY statut_paiement DESC, numero_facture, date_commande DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $factures = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $facture_id = $row['numero_facture'];

        if (!isset($factures[$facture_id])) {
            $factures[$facture_id] = [
                'numero_facture' => $facture_id,
                'statut_paiement' => $row['statut_paiement'],
                'mode_paiement' => $row['mode_paiement'],
                'date_commande' => $row['date_commande'],
                'numero_serveur' => $row['numero_serveur'],
                'numero_table' => $row['numero_table'],
                'total_price' => 0,
                'items' => []
            ];
        }

        $factures[$facture_id]['items'][] = [
            'nom_boisson' => $row['nom_boisson'],
            'quantite' => $row['quantite'],
            'prix' => $row['prix'],
            'total_price' => $row['total_price']
        ];

        $factures[$facture_id]['total_price'] += $row['total_price'];
    }

    echo json_encode(array_values($factures));
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
