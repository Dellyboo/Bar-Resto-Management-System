<?php

$host = 'localhost';  // Change this to your database host
$db = 'g_bar'; // Your database name
$user = 'root';   // Your database username
$pass = ''; // Your database password
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Query to fetch all employees
$query = "SELECT id, nom, prenom FROM gestion_employes";
$stmt = $pdo->query($query);

$employees = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $employees[] = $row;
}

// Return the employees as a JSON response
echo json_encode($employees);
?>
