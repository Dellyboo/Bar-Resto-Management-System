<?php
// delete_stock.php
header('Content-Type: application/json');

try {
    require_once 'database.php';
    
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("Stock ID is required");
    }
    
    $id = $_POST['id'];
    
    // Check if the stock item exists
    $stmt = $conn->prepare("SELECT * FROM gestion_stock_boisson WHERE id = ?");
    $stmt->execute([$id]);
    $stock = $stmt->fetch();
    
    if (!$stock) {
        throw new Exception("Stock item not found");
    }
    
    // Delete the stock item
    $stmt = $conn->prepare("DELETE FROM gestion_stock_boisson WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Stock deleted successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
