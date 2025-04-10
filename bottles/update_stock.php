<?php
// Include database connection
require_once 'database.php'; 

// Set response header to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate received data
    if (!isset($data['id'], $data['name'], $data['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes']);
        exit;
    }

    // Sanitize input
    $id = intval($data['id']);
    $name = htmlspecialchars(trim($data['name']));
    $quantity = intval($data['quantity']);

    try {
        // Prepare update statement
        $stmt = $pdo->prepare("UPDATE bottles SET name = :name, quantity = :quantity WHERE id = :id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Mise à jour réussie']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
}
?>
