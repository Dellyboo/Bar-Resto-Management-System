<?php
// Database connection
$host = 'localhost';  
$db = 'g_bar'; 
$user = 'root';   
$pass = ''; 
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record from the table
    $sql = "DELETE FROM equipement_endommage WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "Success";  // Return success message
    } else {
        echo "Error";  // Return error message if delete fails
    }
} else {
    echo "No ID provided";  // No ID in the request
}
?>
