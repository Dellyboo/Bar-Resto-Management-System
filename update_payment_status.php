<?php
// Fetch POST data
$data = json_decode(file_get_contents('php://input'), true);

$numero_facture = $data['numero_facture'];
$statut_paiement = $data['statut_paiement'];
$mode_paiement = $data['mode_paiement'];

// Database connection (adjust according to your settings)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "g_bar";  // Use the g_bar database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$sql = "UPDATE gestion_commande_boisson SET statut_paiement = ?, mode_paiement = ? WHERE numero_facture = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $statut_paiement, $mode_paiement, $numero_facture);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
