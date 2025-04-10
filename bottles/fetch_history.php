<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "g_bar";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch history data
$sql = "SELECT received_bottle_name, received_quantity, receiver_name, received_company, 
               given_bottle_name, given_quantity, provider_name, provider_company, date_reported 
        FROM bottles_report 
        ORDER BY date_reported DESC"; // Order by latest report

$result = $conn->query($sql);

$historyData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historyData[] = $row;
    }
}

// Return data as JSON
echo json_encode($historyData);

$conn->close();
?>
