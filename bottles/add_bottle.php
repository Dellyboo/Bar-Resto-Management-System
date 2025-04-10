<?php
// Database connection settings
$servername = "localhost";  // Database server
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "g_bar";          // Your database name

// Create a PDO connection to the database
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form and convert name to uppercase
    $bottleName = strtoupper(trim($_POST['bottleName'])); // Convert to uppercase
    $bottleQuantity = $_POST['bottleQuantity'];

    // Ensure that the name and quantity are not empty
    if (empty($bottleName) || empty($bottleQuantity)) {
        echo "❌ Nom de la bouteille et quantité sont requis!";
    } else {
        // Prepare the SQL query to insert the bottle data
        $sql = "INSERT INTO bottles (name, quantity, created_at) VALUES (:name, :quantity, NOW())";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':name', $bottleName);
        $stmt->bindParam(':quantity', $bottleQuantity);

        // Execute the query
        if ($stmt->execute()) {
            echo "✅ Bouteille ajoutée avec succès!";
        } else {
            echo "❌ Erreur lors de l'ajout!";
        }
    }
}
?>
