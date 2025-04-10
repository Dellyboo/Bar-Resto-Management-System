<?php
// Database connection settings
$host = 'localhost'; // Your host
$dbname = 'g_bar'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password (use empty if no password)

// Create a PDO instance
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query to fetch data from the database
    $query = "SELECT nom_boisson, quantite_stock, prix_achat FROM gestion_stock_boisson";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Check if there are any results
    if ($stmt->rowCount() > 0) {
        // Loop through each row and display the data
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Check the value of Quantité en Stock to determine the color for Quantité en Stock value
            $stockQuantity = $row['quantite_stock'];
            $stockColor = $stockQuantity < 10 ? 'red' : 'green'; // Red for stock < 100, Green for stock >= 100
            
            echo '<div class="drink-item">';
            echo '<p><strong>Nom Boisson:</strong> <span style="text-decoration: none;">' . $row['nom_boisson'] . '</span></p>';
            echo '<p><strong>Quantité en Stock:</strong> <span style="color: ' . $stockColor . ';">' . $row['quantite_stock'] . '</span></p>';
            echo '<p><strong>Prix d\'achat:</strong> ' . $row['prix_achat'] . ' FBU</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No drinks available.</p>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
