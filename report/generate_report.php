<?php
include "../admin/Main.php";
$conn = new Index;
error_reporting(E_ALL);
ini_set('display_errors', 1);



require '../assets/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

ob_start();
include 'report_data.php';

$html = ob_get_clean();


$dompdf->loadHtml($html);

// $dompdf->setPaper('A4', 'portrait');

$dompdf->setPaper('legal', 'portrait'); // Set paper size to legal

$dompdf->render();

$dompdf->stream('document.pdf', ['Attachment' => false]);
