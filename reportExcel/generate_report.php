<?php
require '../assets/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reportType = $_POST['report_type'];
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Database connection
include "../admin/Main.php";
$conn = new Index;

$rowNumber = 1;

function setHeaders($headers, &$sheet, $rowNumber) {
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col.$rowNumber, $header);
        $col++;
    }
}

switch ($reportType) {
    case 'orders':
        setHeaders(['Name', 'User ID', 'Total', 'Status', 'Date'], $sheet, $rowNumber);
        $stmt = $conn->con->prepare("
            SELECT transaction.u_id, transaction.total_price, transaction.status, transaction.order_date, 
            CONCAT(users.f_name, ' ', users.l_name) AS Name
            FROM transaction
            LEFT JOIN users ON transaction.u_id = users.u_id
            WHERE order_date BETWEEN ? AND ?
        ");
        break;

    case 'sales':
        setHeaders(['Name', 'Total Price', 'Payment Status', 'Order Date'], $sheet, $rowNumber);
        $stmt = $conn->con->prepare("
            SELECT transaction.total_price, transaction.payment_status, transaction.order_date, 
            CONCAT(users.f_name, ' ', users.l_name) AS Name
            FROM transaction
            LEFT JOIN users ON transaction.u_id = users.u_id
            WHERE transaction.status = 'order_delivered' AND transaction.order_date BETWEEN ? AND ?
        ");
        break;

    case 'users':
        setHeaders(['Name', 'Username', 'Email', 'Phone', 'Registered Date'], $sheet, $rowNumber);
        $stmt = $conn->con->prepare("
            SELECT CONCAT(f_name, ' ', l_name) AS NAME, username, email, phone, created_at 
            FROM users 
            WHERE created_at BETWEEN ? AND ?
        ");
        break;

    case 'stalls':
        setHeaders(['Stall Name', 'Phone', 'Status', 'Date Registered'], $sheet, $rowNumber);
        $stmt = $conn->con->prepare("
            SELECT title, phone, status, created_at 
            FROM restaurant 
            WHERE created_at BETWEEN ? AND ?
        ");
        break;

    case 'riders':
        setHeaders(['Name', 'Email', 'Phone', 'Status', 'Registered'], $sheet, $rowNumber);
        $stmt = $conn->con->prepare("
            SELECT f_name, l_name, email, phone, status, created_at 
            FROM users 
            WHERE role = 2 AND created_at BETWEEN ? AND ?
        ");
        break;

    default:
        die("Invalid report type.");
}

$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
$rowNumber++;

while ($row = $result->fetch_assoc()) {
    switch ($reportType) {
        case 'orders':
            $statusMap = [
                'order_confirmation' => 'Confirmed',
                'Order_Canceled' => 'Cancelled',
                'Order_Cancelled' => 'Cancelled',
                'Order_Received' => 'Received',
                'in_process' => 'In Process',
                'order_delivered' => 'Delivered',
                'place_order' => 'Placed'
            ];
            $status = $statusMap[$row['status']] ?? ucfirst(str_replace('_', ' ', $row['status']));
            $sheet->fromArray([
                $row['Name'],
                $row['u_id'],
                $row['total_price'],
                $status,
                date("F j, Y", strtotime($row['order_date']))
            ], null, 'A' . $rowNumber);
            break;

        case 'sales':
            $sheet->fromArray([
                $row['Name'],
                $row['total_price'],
                $row['payment_status'],
                date("F j, Y", strtotime($row['order_date']))
            ], null, 'A' . $rowNumber);
            break;

        case 'users':
            $sheet->fromArray([
                $row['NAME'],
                $row['username'],
                $row['email'],
                $row['phone'],
                date("F j, Y", strtotime($row['created_at']))
            ], null, 'A' . $rowNumber);
            break;

        case 'stalls':
            $statusText = $row['status'] == 0 ? 'Active' : 'Inactive';
            $sheet->fromArray([
                $row['title'],
                $row['phone'],
                $statusText,
                date("F j, Y", strtotime($row['created_at']))
            ], null, 'A' . $rowNumber);
            break;

        case 'riders':
            $statusText = $row['status'] == 0 ? 'Available' : 'Unavailable';
            $sheet->fromArray([
                $row['f_name'] . ' ' . $row['l_name'],
                $row['email'],
                $row['phone'],
                $statusText,
                date("F j, Y", strtotime($row['created_at']))
            ], null, 'A' . $rowNumber);
            break;
    }

    $rowNumber++;
}

// Set headers
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$reportType.'_report.xlsx"');
header('Cache-Control: max-age=0');

// Output file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
