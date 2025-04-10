<?php
// add_stock.php
header('Content-Type: application/json');

try {
    require_once 'database.php';

    
   // $category = $_POST['category'];
   //var_dump($_POST); // To see the incoming POST data
    //exit;
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $picture = '';

   
    if (empty($name) || empty($quantity) || empty($price)) {
        throw new Exception("All fields are required");
    }

    if (!is_numeric($quantity) || !is_numeric($price)) {
        throw new Exception("Invalid quantity or price");
    }

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . "/../pictures/"; // Make sure the "pictures" directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["picture"]["name"]);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $file_path)) {
            $picture = $file_name; // Store the filename in the database
        } else {
            throw new Exception("Échec du téléchargement de l'image.");
        }
    }

   
    $stmt = $conn->prepare("INSERT INTO gestion_stock_boisson (nom_boisson, quantite_stock, prix_achat, picture) VALUES (?, ?, ?, ?)");
    
      //  $stmt = $conn->prepare("INSERT INTO gestion_stock_kitchen (nom_produit, quantite_disponible, cout_achat) VALUES (?, ?, ?)");
    

    $stmt->execute([$name, $quantity, $price, $picture]);


    echo json_encode([
        'success' => true,
        'message' => 'Stock added successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>