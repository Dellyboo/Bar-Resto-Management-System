<?php
$host = "localhost"; // Database host
$dbname = "g_bar"; // Your database name
$username = "root"; // Database username
$password = ""; // Database password (empty for localhost)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
