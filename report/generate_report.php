<?php
include "../admin/Main.php";
$conn = new Index;

require '../assets/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    // Only PDF is allowed, no need to check format
    if (!$reportType || !$startDate || !$endDate) {
        die("Missing required fields.");
    }

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);

    // Load HTML content from a separate file
    ob_start();
    include 'report_data.php'; // Make sure this generates HTML correctly using the $_POST values
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('legal', 'portrait');
    $dompdf->render();

    $dompdf->stream("{$reportType}_report.pdf", ['Attachment' => false]);
    exit;
} else {
    echo "Invalid request.";
}
