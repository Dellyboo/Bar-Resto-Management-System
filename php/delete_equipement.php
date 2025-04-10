<?php
// delete_stock.php
header('Content-Type: application/json');

try {
    require_once 'database.php';
    
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("ID de l'equipement est necessaire");
    }
    
    $id = $_POST['id'];
    
    // Check if the stock item exists
    $stmt = $conn->prepare("SELECT * FROM equipements WHERE id = ?");
    $stmt->execute([$id]);
    $stock = $stmt->fetch();
    
    if (!$stock) {
        throw new Exception("Equipement non retrouve");
    }
    
    // Delete the stock item
    $stmt = $conn->prepare("DELETE FROM equipements WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode([
        'success' => true,
        'message' => "Details de l'equipement supprime avec succes"
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
