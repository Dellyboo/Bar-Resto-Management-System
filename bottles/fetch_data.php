<?php
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

$sql = "SELECT received_bottle_name, received_quantity, given_bottle_name, given_quantity, receiver_name, provider_name, received_company, provider_company, date_reported FROM bottles_report";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo "0 results";
}

$conn->close();
?>
