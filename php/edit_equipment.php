<?php
// Database connection
$host = 'localhost';  
$db = 'g_bar'; 
$user = 'root';   
$pass = ''; 
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Check if POST data is received
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];

    // Update the equipment record
    $sql = "UPDATE equipement_endommage SET name = :name, quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Success";  // Return success message
    } else {
        echo "Error";  // Return error message
    }
} else {
    echo "No data provided";  // No data in the request
}
?>
