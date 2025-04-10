<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "g_bar";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Retrieve form data
    $manager_name = $_POST['manager_name'];
    $specific_item = $_POST['specific_item'];
    $impact = $_POST['impact'];
    $complaint_description = $_POST['complaint_description'];
    $complaint_category = $_POST['complaint_category'];
    $incident_date_time = $_POST['incident_date_time'];
    $cause = $_POST['cause'];
    $incident_location = $_POST['incident_location'];
    $employee_comments = $_POST['employee_comments'];

    // Default status: "non-consulté"
    $status = "non-consulté";

    // Handle file uploads
    $attachments = "";
    if (!empty($_FILES['attachments']['name'][0])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $files = $_FILES['attachments'];
        $filePaths = [];

        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = time() . "_" . basename($files['name'][$i]);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                $filePaths[] = $fileName;
            }
        }
        $attachments = implode(",", $filePaths);
    }

    // Insert data into the database with status column
    $stmt = $conn->prepare("INSERT INTO complaints 
        (manager_name, specific_item, impact, complaint_description, complaint_category, incident_date_time, cause, incident_location, attachments, employee_comments, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssssss", $manager_name, $specific_item, $impact, $complaint_description, $complaint_category, $incident_date_time, $cause, $incident_location, $attachments, $employee_comments, $status);

    if ($stmt->execute()) {
        // Show success message in French and redirect
        echo "<script>
                alert('Votre plainte a été soumise avec succès !');
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
              </script>";
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
