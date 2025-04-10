<?php
header('Content-Type: application/json');

try {
    require_once 'database.php'; // Ensure this file connects to your database

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID de l'employe est necessaire");
    }

    $employeeId = $_GET['id'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, nom, prenom, poste, salaire, numero_serveur, date_embauche FROM gestion_employes WHERE id = ?");
    $stmt->execute([$employeeId]);

    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception("Employee n'existe pas");
    }

    echo json_encode([
        'success' => true,
        'data' => $employee
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
