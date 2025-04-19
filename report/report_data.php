<style>
    body {
        font-family: DejaVu Sans;
    }

    .report-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #ddd;
    }

    .report-title {
        font-size: 26px;
        font-weight: bold;
        margin-top: 10px;
        color: #333;
    }

    .report-date {
        font-size: 14px;
        color: #777;
        margin-top: 5px;
    }

    .header-info {
        font-size: 18px;
        color: #333;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .border-bottom {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px; /* Adjusted font size for table */
    }

    th, td {
        border: 1px solid #ccc;
        padding: 8px 5px;
        text-align: center;
        font-size: 12px; /* Adjusted font size for table content */
    }

    th {
        background-color: #f2f2f2;
    }




</style>

<div class="report-header">
    <div class="report-title">Order Report</div>
    <div class="report-date"><?= date('F j, Y') ?></div>
    <div class="header-info">This report contains the order details of all placed orders.</div>
</div>

<?php
// Dummy Data
$orders = [
    [
        'f_name' => 'Juan',
        'l_name' => 'Dela Cruz',
        'transacId' => 'TXN123456',
        'title' => 'Cheesy Burger x2, Fries x1',
        'total_price' => 255.00,
        'status' => 'order_confirmation',
        'order_date' => '2025-04-12 10:35:00',
    ],
    [
        'f_name' => 'Maria',
        'l_name' => 'Santos',
        'transacId' => 'TXN123457',
        'title' => 'Carbonara x1, Milk Tea x1',
        'total_price' => 185.00,
        'status' => 'in_process',
        'order_date' => '2025-04-12 11:15:00',
    ],
    [
        'f_name' => 'Pedro',
        'l_name' => 'Penduko',
        'transacId' => 'TXN123458',
        'title' => 'Chicken Wings x6, Rice x2',
        'total_price' => 320.00,
        'status' => 'Order_Cancelled',
        'order_date' => '2025-04-11 14:22:00',
    ],
    [
        'f_name' => 'Ana',
        'l_name' => 'Lopez',
        'transacId' => 'TXN123459',
        'title' => 'Tapsilog x1, Iced Coffee x1',
        'total_price' => 140.00,
        'status' => 'Order_Received',
        'order_date' => '2025-04-10 09:45:00',
    ],
];
?>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Customer</th>
            <th>Order ID</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($orders as $order): ?>
            <?php
                switch ($order['status']) {
                    case 'order_confirmation': $statusText = 'Confirmed'; $class = 'bg-success'; break;
                    case 'Order_Canceled':
                    case 'Order_Cancelled': $statusText = 'Cancelled'; $class = 'bg-danger'; break;
                    case 'Order_Received': $statusText = 'Received'; $class = 'bg-info'; break;
                    case 'in_process': $statusText = 'In Process'; $class = 'bg-warning'; break;
                    case 'place_order': $statusText = 'Placed'; $class = 'bg-secondary'; break;
                    default: $statusText = ucfirst(str_replace('_', ' ', $order['status'])); $class = 'bg-secondary';
                }
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($order['f_name'] . ' ' . $order['l_name']) ?></td>
                <td><?= htmlspecialchars($order['transacId']) ?></td>
                <td><?= htmlspecialchars($order['title']) ?></td>
                <td>₱ <?= number_format($order['total_price'], 2) ?></td>
                <td><span class="badge <?= $class ?>"><?= $statusText ?></span></td>
                <td><?= date('F j, Y g:i A', strtotime($order['order_date'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
