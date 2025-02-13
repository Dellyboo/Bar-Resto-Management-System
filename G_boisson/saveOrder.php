<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "g_bar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive the data from JavaScript (order details)
$data = json_decode(file_get_contents("php://input"), true);
$orderData = $data['orderData'];
$tableNumber = $data['tableNumber'];
$serverNumber = $data['serverNumber'];

// Insert each drink into the database
foreach ($orderData as $drink) {
    $drinkName = $drink['drinkName'];
    $quantity = $drink['quantity'];
    $price = $drink['price'];
    $total = $drink['total'];
    $dateCommande = date('Y-m-d H:i:s');
    
    // Check if this drink already exists in the database (same name)
    $sql = "SELECT id FROM gestion_commande_boisson WHERE nom_boisson = '$drinkName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If the drink exists, update the quantity
        $row = $result->fetch_assoc();
        $drinkId = $row['id'];
        $sql = "UPDATE gestion_commande_boisson SET quantite = quantite + $quantity, prix = $price WHERE id = $drinkId";
        $conn->query($sql);
    } else {
        // If the drink does not exist, insert it into the table
        $sql = "INSERT INTO gestion_commande_boisson (nom_boisson, quantite, prix, date_commande) 
                VALUES ('$drinkName', $quantity, $price, '$dateCommande')";
        $conn->query($sql);
        $drinkId = $conn->insert_id; // Get the ID of the newly inserted drink
    }
}

$conn->close();
echo json_encode(["status" => "success"]);
?>
