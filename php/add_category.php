<?php
// add_category.php
header('Content-Type: application/json');

try {
    require_once 'database.php';

    // Get the POST data
    $categoryType = $_POST['category_type'];
    $categoryName = $_POST['category_name'];
    $parentCategory = isset($_POST['parent_category']) ? $_POST['parent_category'] : NULL;

    // Check if required fields are present
    if (empty($categoryType) || empty($categoryName)) {
        throw new Exception("All fields are required");
    }

    // Insert data into appropriate table
    if ($categoryType === 'cuisine_categorie') {
        // Insert into cuisine_categorie table
        $stmt = $conn->prepare("INSERT INTO cuisine_categorie (name) VALUES (?)");
        $stmt->execute([$categoryName]);
    } elseif ($categoryType === 'sous_categorie') {
        if (empty($parentCategory)) {
            throw new Exception("Parent category is required for sous_categorie");
        }
        // Insert into sous_categorie table
        $stmt = $conn->prepare("INSERT INTO sous_categorie (cuisine_categorie_id, name) VALUES (?, ?)");
        $stmt->execute([$parentCategory, $categoryName]);
    } else {
        throw new Exception("Invalid category type");
    }

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Category added successfully'
    ]);

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
