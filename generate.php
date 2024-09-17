<?php
// Assuming you have a function that fetches data from somewhere
function fetchDataFromSomewhere()
{
    // Implement your data fetching logic here
    $data = [
        ['column1' => 'value1', 'column2' => 'value2'],
        ['column1' => 'value3', 'column2' => 'value4'],
    ];
    return $data;
}

require_once 'fetch.php';
require_once 'vendor/autoload.php';

$data = fetchDataFromSomewhere();

// The rest of your code remains the same

// Use the TCPDF library without use statement
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jouw Naam');
$pdf->SetTitle('Voorbeeld PDF');
$pdf->SetSubject('Voorbeeld Onderwerp');
$pdf->SetKeywords('TCPDF, PDF, voorbeeld, gids');

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Add a page
$pdf->AddPage();

// Set the content of the PDF
$html = '<h1>Gegevens uit de database</h1><table border="1" cellpadding="4">';
foreach ($data as $row) {
    $html .= '<tr>';
    foreach ($row as $column) {
        $html .= '<td>' . $column . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

// Add HTML content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close and save the PDF
$pdf->Output(__DIR__ . '/voorbeeld.pdf', 'F'); // Save the PDF on the server