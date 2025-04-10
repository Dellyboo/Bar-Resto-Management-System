<?php
// Save the bottle report to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "g_bar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$received_bottle_name = $_POST['received_bottle_name'];
$received_quantity = $_POST['received_quantity'];

// If 'given_bottle_name' is empty, set it to the value of 'received_bottle_name'
$given_bottle_name = !empty($_POST['given_bottle_name']) ? $_POST['given_bottle_name'] : $received_bottle_name;

$given_quantity = $_POST['given_quantity'];
$receiver_name = $_POST['receiver_name'];
$provider_name = $_POST['provider_name'];
$received_company = $_POST['received_company'];
$provider_company = $_POST['provider_company'];

// Insert data into the database
$stmt = $conn->prepare("INSERT INTO bottles_report (received_bottle_name, received_quantity, given_bottle_name, given_quantity, receiver_name, provider_name, received_company, provider_company, date_reported) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("siiissss", $received_bottle_name, $received_quantity, $given_bottle_name, $given_quantity, $receiver_name, $provider_name, $received_company, $provider_company);

if ($stmt->execute()) {
    echo "Data saved successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
