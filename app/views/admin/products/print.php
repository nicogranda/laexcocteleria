<?php
require('../../app/libraries/fpdf/fpdf.php');

$quote_id = $_GET['id'];

$pdf = new FPDF('P', 'mm', 'A4'); // Mantén la codificación en UTF-8
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Logo
$logoPath = 'https://ikusa.net/images/logo.png';
$pdf->Image($logoPath, 120, 5, 30);

// Texto "Quote"
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 5, utf8_decode('Quote'), 0, 1, 'L');

// Salida del PDF
$pdf->Output('I', 'quote_' . $quote_id . '.pdf');
?>
