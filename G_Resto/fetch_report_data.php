<?php
// Database connection details
$host = 'localhost';
$dbname = 'g_bar';
$username = 'root';
$password = '';

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a date is provided in the request
$date_filter = isset($_GET['date_commande']) ? $_GET['date_commande'] : null;

// Base query for fetching summarized data
$query = "SELECT nom_plat, SUM(quantite) as total_quantite, SUM(total_price) as total_price  
          FROM gestion_commande_kitchen";

// Apply date filter if provided
if ($date_filter) {
    $query .= " WHERE DATE(date_commande) = '$date_filter'";  // Use the date filter if provided
}

$query .= " GROUP BY nom_plat ORDER BY nom_plat ASC";  // Group by 'nom_plat' and order alphabetically

$result = $conn->query($query);

// Check if there are any results
if ($result->num_rows > 0) {
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode([]); // Return an empty array if no results found
}

// Close the database connection
$conn->close();
?>
