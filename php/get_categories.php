<?php
// Database connection
$host = 'localhost';  // Change this to your database host
$db = 'g_bar'; // Your database name
$user = 'root';   // Your database username
$pass = ''; // Your database password
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Retrieve parameters from the DataTable
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;  // Pagination start
$length = isset($_GET['length']) ? (int)$_GET['length'] : 10; // Pagination length
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : ''; // Search term
$orderColumn = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 0; // Sorting column index
$orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc'; // Sorting direction

// Mapping of column indexes to actual column names (modify this to match your table)
$columns = ['id', 'name'];  // Update this array with the actual column names from your table
$orderColumnName = $columns[$orderColumn];

// Construct the SQL query with search and order
$sql = "SELECT id, name FROM cuisine_categorie 
        WHERE name LIKE :searchValue 
        ORDER BY $orderColumnName $orderDir 
        LIMIT :start, :length";

// Prepare the statement
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':searchValue', '%' . $searchValue . '%', PDO::PARAM_STR);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);
$stmt->execute();

// Fetch the results
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of records (without pagination)
$totalRecordsStmt = $pdo->query("SELECT COUNT(*) FROM cuisine_categorie");
$totalRecords = $totalRecordsStmt->fetchColumn();

// Return the data in the DataTable format
$response = [
    'draw' => isset($_GET['draw']) ? (int)$_GET['draw'] : 1,  // DataTable draw counter
    'recordsTotal' => $totalRecords, // Total number of records in the database
    'recordsFiltered' => $totalRecords, // Total records after search filter (same as recordsTotal in this case)
    'data' => $data // The actual data to display in the table
];

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
