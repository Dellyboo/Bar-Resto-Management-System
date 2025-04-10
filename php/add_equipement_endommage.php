<?php
$host = 'localhost'; // Change if needed
$dbname = 'g_bar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $equipement_id = $_POST['nom_equipement'];
    $quantite = (int) $_POST['quantite'];

    // Check if the equipment exists and get the available quantity
    $stmt = $pdo->prepare("SELECT quantite FROM equipements WHERE id = ?");
    $stmt->execute([$equipement_id]);
    $equipement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipement) {
        echo json_encode(["error" => "L'équipement sélectionné n'existe pas."]);
        exit;
    }

    if ($equipement['quantite'] < $quantite) {
        echo json_encode(["error" => "Quantité insuffisante disponible."]);
        exit;
    }

    // Reduce quantity in equipements table
    $new_quantity = $equipement['quantite'] - $quantite;
    $stmt = $pdo->prepare("UPDATE equipements SET quantite = ? WHERE id = ?");
    $stmt->execute([$new_quantity, $equipement_id]);

    // Insert into equipement_endommage table
    $stmt = $pdo->prepare("INSERT INTO equipement_endommage (equipement_id, quantity) VALUES (?, ?)");
    $stmt->execute([$equipement_id, $quantite]);

    echo json_encode(["success" => "Équipement endommagé ajouté avec succès."]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de base de données: " . $e->getMessage()]);
}
?>
