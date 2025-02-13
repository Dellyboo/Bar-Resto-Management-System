<?php
$host = "localhost"; // Database host
$dbname = "g_bar"; // Your database name
$username = "root"; // Database username
$password = ""; // Database password (empty for localhost)

// Create a PDO instance
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
