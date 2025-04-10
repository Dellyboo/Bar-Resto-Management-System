<?php
// add_kitchen_stock.php
header('Content-Type: application/json');

try {
    $host = 'localhost';  // Change this to your database host
    $db = 'g_bar'; // Your database name
    $user = 'root';   // Your database username
    $pass = ''; // Your database password
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ingredient_name = $_POST['nom_ingredient'];
    $ingredient_price = $_POST['prix_achat'];
    $category = $_POST['cuisine_categorie_id'];
    $sub_category = $_POST['sous_categorie_id'];
    $picture = ''; // Default value if no image is uploaded

    if (empty($ingredient_name) || empty($ingredient_price) || empty($category) || empty($sub_category)) {
        throw new Exception("All fields are required");
    }

    if (!is_numeric($ingredient_price)) {
        throw new Exception("Quantité ou prix invalide");
    }

    // Handle file upload
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

    // Prepare the SQL statement to insert data into the gestion_stock_kitchen table
    $stmt = $pdo->prepare("INSERT INTO gestion_stock_kitchen (nom_ingredient, prix_achat, cuisine_categorie_id, sous_categorie_id, picture) VALUES (?, ?, ?, ?, ?)");
    
    // Execute the query with the data
    $stmt->execute([$ingredient_name, $ingredient_price, $category, $sub_category, $picture]);

    echo json_encode([
        'success' => true,
        'message' => 'Produit ajouté au stock de cuisine avec succès',
        'picture' => $picture ? "pictures/$picture" : null // Return image URL for preview
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
