<?php
// Database connection
$host = 'localhost';  
$db = 'g_bar'; 
$user = 'root';   
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}

// Retrieve DataTables parameters
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$length = isset($_GET['length']) ? (int)$_GET['length'] : 10;
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : ''; 
$orderColumn = isset($_GET['order'][0]['column']) ? (int)$_GET['order'][0]['column'] : 0;
$orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc';

// Map DataTables column index to database columns
$columns = ['e.name', 'ee.quantity'];  
$orderColumnName = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'ee.id';

// SQL Query: Join `equipement_endommage` with `equipements` to get the equipment name
$sql = "SELECT ee.id, e.name, ee.quantity, ee.created_at 
        FROM equipement_endommage ee
        JOIN equipements e ON ee.equipement_id = e.id
        WHERE e.name LIKE :searchValue OR ee.quantity LIKE :searchValue
        ORDER BY $orderColumnName $orderDir
        LIMIT :start, :length";

// Prepare and execute query
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':searchValue', '%' . $searchValue . '%', PDO::PARAM_STR);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);
$stmt->execute();

// Fetch data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total record count
$totalRecordsStmt = $pdo->query("SELECT COUNT(*) FROM equipement_endommage");
$totalRecords = $totalRecordsStmt->fetchColumn();

// Response format for DataTables
$response = [
    'draw' => isset($_GET['draw']) ? (int)$_GET['draw'] : 1,
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $totalRecords, 
    'data' => $data
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
