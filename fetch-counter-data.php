<?php
// Database connection parameters
$host = 'localhost'; // Your database host
$dbname = 'g_bar';   // Your database name
$username = 'root';   // Your database username
$password = '';       // Your database password

try {
    // Establish connection with the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Fetch the total number of employees
$stmt = $pdo->query("SELECT COUNT(*) as total_employees FROM gestion_employes");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalEmployees = $row['total_employees'];

// Fetch the total number of sold bottles (sum of all 'quantite' values for today only)
$stmt = $pdo->query("SELECT COALESCE(SUM(quantite), 0) as total_bottles FROM gestion_commande_boisson WHERE DATE(date_commande) = CURDATE()");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalBottles = $row['total_bottles'];



// Fetch the total number of sold plates (for today only)
$stmt = $pdo->query("SELECT COUNT(*) as total_food FROM gestion_commande_kitchen WHERE DATE(date_commande) = CURDATE()");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalFood = $row['total_food'];


// Fetch the total number of new complaints (for today only)
$stmt = $pdo->query("SELECT COUNT(*) as total_complaints FROM complaints WHERE DATE(submitted_at) = CURDATE()");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalComplaints = $row['total_complaints'];


// Return the data as JSON
echo json_encode([
    'totalEmployees' => $totalEmployees,
    'totalBottles' => $totalBottles,
    'totalFood' => $totalFood,
    'totalComplaints' => $totalComplaints
]);
?>
