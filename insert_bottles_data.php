<?php
// Database connection parameters
$host = 'localhost'; 
$dbname = 'g_bar';   
$username = 'root';   
$password = '';       

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $received_bottle_name = $_POST['received_bottle_name'];
    $received_quantity = $_POST['received_quantity'];
    $given_bottle_name = $_POST['given_bottle_name'];
    $given_quantity = $_POST['given_quantity'];
    $receiver_name = $_POST['receiver_name'];
    $provider_name = $_POST['provider_name'];
    $received_company = $_POST['received_company'];
    $provider_company = $_POST['provider_company'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO bottles_report (received_bottle_name, received_quantity, given_bottle_name, given_quantity, receiver_name, provider_name, received_company, provider_company) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiissss", $received_bottle_name, $received_quantity, $given_bottle_name, $given_quantity, $receiver_name, $provider_name, $received_company, $provider_company);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Data successfully inserted!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
