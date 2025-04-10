<?php
header('Content-Type: application/json');

try {
    $host = 'localhost';
    $db = 'g_bar';
    $user = 'root';
    $pass = '';
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception("Invalid request method");
    }

    // Get the selected date from the request
    if (isset($_GET['date'])) {
        $selectedDate = $_GET['date'];
    } else {
        throw new Exception("Date is required");
    }

    // Validate the date format (YYYY-MM-DD)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $selectedDate)) {
        throw new Exception("Invalid date format");
    }

    // Query to fetch employee attendance data for the selected date
    $stmt = $pdo->prepare("SELECT ea.attendance 
                           FROM employee_attendance ea
                           WHERE ea.attendance_date = :date");
    $stmt->bindParam(':date', $selectedDate);
    $stmt->execute();

    // Fetch results
    $attendanceData = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no data is found, send an appropriate message
    if (empty($attendanceData)) {
        throw new Exception("No attendance data found for this date");
    }

    // Decode the attendance JSON to get the status for each employee
    $attendanceJson = json_decode($attendanceData['attendance'], true);
    
    // Fetch employee names (assuming you have an employees table with 'id', 'nom', and 'prenom' fields)
    $employeeIds = array_keys($attendanceJson);
    $placeholders = implode(',', array_fill(0, count($employeeIds), '?'));
    $stmt = $pdo->prepare("SELECT id, nom, prenom FROM gestion_employes WHERE id IN ($placeholders)");
    $stmt->execute($employeeIds);

    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map employees with their attendance status
    $attendanceDetails = [];
    foreach ($employees as $employee) {
        $attendanceDetails[] = [
            'id' => $employee['id'],
            'nom' => $employee['nom'],
            'prenom' => $employee['prenom'],
            'status' => $attendanceJson[$employee['id']] // Fetch attendance status from JSON
        ];
    }

    // Return attendance data in JSON format
    echo json_encode([
        'success' => true,
        'data' => $attendanceDetails
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
