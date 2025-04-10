<?php
// add_stock.php
header('Content-Type: application/json');

try {
    require_once 'database.php';

    
   // $category = $_POST['category'];
   //var_dump($_POST); // To see the incoming POST data
    //exit;
    $name = $_POST['nom_equipement'];
    $quantite = $_POST['quantite'];

   
    if (empty($name) || empty($quantite)) {
        throw new Exception("All fields are required");
    }

    if (!is_numeric($quantite)) {
        throw new Exception("Invalid quantity");
    }

   
    $stmt = $conn->prepare("INSERT INTO equipements (name, quantite) VALUES (?, ?)");
    
      //  $stmt = $conn->prepare("INSERT INTO gestion_stock_kitchen (nom_produit, quantite_disponible, cout_achat) VALUES (?, ?, ?)");
    

    $stmt->execute([$name, $quantite]);


    echo json_encode([
        'success' => true,
        'message' => $quantite . ' ' . $name . '(s) ont ete ajoute avec success'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>