<?php
require('fpdf/fpdf.php'); 

// Database connection
$host = "localhost";
$dbname = "g_bar";
$username = "root";
$password = "";
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

// ✅ Validate & Sanitize ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erreur: ID de congé invalide.");
}
$leave_id = intval($_GET['id']);

// ✅ Ensure correct table name (previously "conge", should be "conges" as per convention)
$query = $conn->prepare("SELECT * FROM conge WHERE id = ?");
if (!$query) {
    die("Erreur SQL: " . $conn->error);
}

$query->bind_param("i", $leave_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Aucune demande de congé trouvée pour ID: $leave_id");
}

$leave = $result->fetch_assoc();

// ✅ Company details
$company_address = "Gitega, Burundi";
$company_phone = "+257 68101005";
$company_email = "kukayagabar@gmail.com";
$company_logo = "C:/xampp/htdocs/G_Bar/logo/logo.png"; // Absolute path to the logo

// ✅ Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// ✅ Add Logo (Fix logo path)
if (file_exists($company_logo)) {
    $pdf->Image($company_logo, 10, 10, 50); // Augmente la taille du logo
} else {
    error_log("⚠️ Logo not found: " . $company_logo);
}

// ✅ Set company info in top-right corner
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(160, 10); // X: 160, Y: 10 for top-right placement
$pdf->Cell(0, 7, utf8_decode($company_address), 0, 1, 'R');
$pdf->Ln(5); // Ajoute un saut de ligne
$pdf->SetXY(160, 17); // Position pour le téléphone
$pdf->Cell(0, 7, utf8_decode("Téléphone: " . $company_phone), 0, 1, 'R');
$pdf->Ln(5); // Ajoute un saut de ligne
$pdf->SetXY(160, 24); // Position pour l'email
$pdf->Cell(0, 7, utf8_decode("Email: " . $company_email), 0, 1, 'R');
$pdf->Ln(10);

// ✅ Centered "Détails de la Demande de Congé"
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode("Objet : Accord de Congé"), 0, 1, 'C');
$pdf->Ln(5);

// ✅ Leave Request Details (Centered)
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(20); // Déplace le curseur vers la gauche

$pdf->SetX(10); // Position slightly left
$pdf->Cell(0, 10, utf8_decode("Monsieur/Madame "), 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 12); // Bold for employee's name
$pdf->SetX(50); // Move name slightly to the left
$pdf->Cell(0, 10, utf8_decode($leave['employe'] . ","), 0, 1, 'L'); // Align slightly left

$pdf->Ln(10);



$pdf->SetFont('Arial', '', 12); // Réinitialise la police pour le reste du texte
$pdf->MultiCell(0, 10, utf8_decode("Nous vous informons par la présente que votre demande de congé de type " . $leave['type'] . " pour la période allant du " . date("d/m/Y", strtotime($leave['date_debut'])) . " au " . date("d/m/Y", strtotime($leave['date_fin'])) . " a été approuvée."), 0, 'J');
$pdf->Ln(10);

$pdf->MultiCell(0, 10, utf8_decode("Cette période de congé concerne un total de " . $leave['total_jours'] . ". Nous vous remercions de nous avoir fourni les informations nécessaires pour traiter votre demande."), 0, 'J');
$pdf->Ln(10);

$pdf->MultiCell(0, 10, utf8_decode("Nous vous souhaitons un agréable séjour et nous nous réjouissons de votre retour parmi nous à votre date de reprise prévue."), 0, 'J');
$pdf->Ln(10);

$pdf->MultiCell(0, 10, utf8_decode("Cordialement,"), 0, 'L');
$pdf->Ln(10);

$pdf->MultiCell(0, 10, utf8_decode("Votre nom"), 0, 'L');
$pdf->Ln(10);

// ✅ Signature Fields (Centered)
$pdf->SetX(20); // Réinitialise la position
$pdf->Cell(80, 10, utf8_decode("Signature de l'employé:"), 0, 0, 'L');
$pdf->Cell(80, 10, utf8_decode("Signature du Manager:"), 0, 1, 'R');

$pdf->Ln(15);
$pdf->SetX(20);
$pdf->Cell(80, 10, "_____________________", 0, 0, 'L');
$pdf->Cell(80, 10, "_____________________", 0, 1, 'R');

// ✅ Output PDF
$pdf->Output("D", "Accord_Conge_" . utf8_decode($leave['employe']) . ".pdf");

?>
