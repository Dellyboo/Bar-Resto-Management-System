<?php
session_start();
$host = 'localhost';
$dbname = 'g_bar'; // Update if needed
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assume the logged-in user has session ID stored as 'user_id'
    $user_id = $_SESSION['user_id'];

    $query = "SELECT username, email, role, profile_picture FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user); // Return data as JSON
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
