<?php
// Include the main TCPDF library (search for installation path).
require 'vendor/autoload.php';
include "db_connect.php";

// Check if the database connection is successful
if (!isset($conn) || $conn === null) {
    die("Database connection failed. Please check your database settings.");
}

// Select the most recent row from the 'aanvraag' table
$sql = "SELECT id, studentNum, naamStudent, bewijs FROM aanvraag ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);

$stmt->execute();

// Fetch the first row from the result set
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT* FROM aanvraag_examens WHERE aanvraag_id = ?";
$stmt = $conn->prepare($sql);

$stmt->execute([
    $student['id']
]);

$examens = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$student) {
    die("No student data found.");
}

// Define the path to the logo image
$image_file = 'download.png';

// Check if the image file exists
if (!file_exists($image_file)) {
    die('Logo file not found: ' . $image_file);
}

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{
    protected $logoPath;

    public function __construct($logoPath, $orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa)
    {
        $this->logoPath = $logoPath;
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }

    // Page header
    public function Header()
    {
        // Check if logo file exists before attempting to display it
        if (file_exists($this->logoPath)) {
            // Increase the width and height to make the logo larger
            $this->Image($this->logoPath, 10, 10, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else {
            $this->Cell(0, 15, 'Logo file not found', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'vrijstelling aanvraag', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Create new PDF document and pass the logo path to the constructor
$pdf = new MYPDF($image_file, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Indien pdf');

// Set default header data
$pdf->SetHeaderData('', 0, '', '');

// Set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Set font
$pdf->SetFont('', '', 12);

// Add a page
$pdf->AddPage();

// Prepare the student information string
$studentInfo = "Studentnummer: \t" . htmlspecialchars($student['studentNum']) . "\n\n" .
               "Studentnaam: \t" . htmlspecialchars($student['naamStudent']) . "\n\n";

foreach ($examens as $examen) {
   $studentInfo .= "Examen: \t" . htmlspecialchars($examen['examenNaam']) . "\n\n" .
    "Examen code: \t" . htmlspecialchars($examen['examenCode']) . "\n\n";
}

// Print a block of text using Write()
$pdf->Write(0, $studentInfo, '', 0, 'C', true, 0, false, false, 0);

// Check if 'bewijs' exists and print it
if (isset($student['bewijs']) && !empty($student['bewijs']) && $student['bewijs'] !== 'nee') {
    $bewijsText = "Bewijs: \n\t" . __DIR__ . '\\' . $student['bewijs'];
    $pdf->Write(0, $bewijsText, '', 0, 'C', true, 0, false, false, 0);
}

// ---------------------------------------------------------

// Close and output PDF document
$pdf->Output('IndieingExamen.pdf', 'I');
?>
