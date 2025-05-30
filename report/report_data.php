<?php
if (!isset($reportType))
    exit("No report type selected.");

echo "
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 30px;
        color: #2c3e50;
        background-color: #fff;
    }
    h2 {
        text-align: center;
        text-transform: uppercase;
        color: #2980b9;
        margin-bottom: 10px;
    }
    p {
        text-align: center;
        font-size: 13px;
        margin-bottom: 30px;
        color: #555;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    th, td {
        border: 1px solid #e0e0e0;
        padding: 10px 8px;
        text-align: center;
        font-size: 12px;
    }
    th {
        background-color: #ecf0f1;
        color: #2c3e50;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>

<h2>{$reportType} Report</h2>
<p>From: " . date("F j, Y", strtotime($startDate)) . " &nbsp;&nbsp; To: " . date("F j, Y", strtotime($endDate)) . "</p>
<table>
<tr>
";

if ($reportType === 'orders') {
    echo "<th>Name</th><th>User ID</th><th>Total</th><th>Status</th><th>Date</th></tr>";
    $stmt = $conn->con->prepare("SELECT transaction.id, transaction.u_id, transaction.total_price, transaction.status, transaction.order_date, 
    CONCAT(users.f_name, ' ', users.l_name) AS Name
    FROM transaction
    LEFT JOIN users ON transaction.u_id = users.u_id
    WHERE order_date BETWEEN ? AND ?");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $status = $row['status'];
        switch ($status) {
            case 'order_confirmation': $statusText = 'Confirmed'; break;
            case 'Order_Canceled':
            case 'Order_Cancelled': $statusText = 'Cancelled'; break;
            case 'Order_Received': $statusText = 'Received'; break;
            case 'in_process': $statusText = 'In Process'; break;
            case 'order_delivered': $statusText = 'Delivered'; break;
            case 'place_order': $statusText = 'Placed'; break;
            default: $statusText = ucfirst(str_replace('_', ' ', $status)); break;
        }

        echo "<tr>
            <td>{$row['Name']}</td>
            <td>{$row['u_id']}</td>
            <td>{$row['total_price']}</td>
            <td>$statusText</td>
            <td>" . date("F j, Y", strtotime($row['order_date'])) . "</td>
        </tr>";
    }

} elseif ($reportType === 'sales') {
    echo "<th>Name</th><th>Total Price</th><th>Payment Status</th><th>Order Date</th></tr>";
    $stmt = $conn->con->prepare("
        SELECT transaction.id, transaction.total_quantity, transaction.order_date, transaction.payment_status, transaction.total_price,
               CONCAT(users.f_name, ' ', users.l_name) AS Name
        FROM transaction
        LEFT JOIN users ON transaction.u_id = users.u_id
        WHERE transaction.status = 'order_delivered' AND transaction.order_date BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Name']}</td>
            <td>{$row['total_price']}</td>
            <td>{$row['payment_status']}</td>
            <td>" . date("F j, Y", strtotime($row['order_date'])) . "</td>
        </tr>";
    }

} elseif ($reportType === 'users') {
    echo "<th>Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Registered Date</th></tr>";
    $stmt = $conn->con->prepare("
        SELECT u_id, CONCAT(f_name, ' ', l_name) AS NAME, username, email, phone, created_at 
        FROM users 
        WHERE created_at BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['NAME']}</td>
            <td>{$row['username']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>" . date("F j, Y", strtotime($row['created_at'])) . "</td>
        </tr>";
    }

} elseif ($reportType === 'stalls') {
    echo "<th>Stall Name</th><th>Phone</th><th>Status</th><th>Date Registered</th></tr>";
    $stmt = $conn->con->prepare("
        SELECT 
            restaurant.title, 
            restaurant.phone,
            restaurant.status, 
            restaurant.created_at 
        FROM restaurant 
        WHERE restaurant.created_at BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $statusText = $row['status'] == 0 ? 'Active' : 'Inactive';
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['phone']}</td>
            <td>{$statusText}</td>
            <td>" . date("F j, Y", strtotime($row['created_at'])) . "</td>
        </tr>";
    }

} elseif ($reportType === 'riders') {
    echo "<th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Registered</th></tr>";
    $stmt = $conn->con->prepare("
        SELECT users.f_name, users.l_name, users.email, users.phone, users.status, users.created_at 
        FROM users 
        WHERE role = 2
        AND users.created_at BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $statusText = $row['status'] == 0 ? 'Available' : 'Unavailable';
        echo "<tr>
            <td>" . htmlspecialchars($row['f_name'] . ' ' . $row['l_name']) . "</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$statusText}</td>
            <td>" . date("F j, Y", strtotime($row['created_at'])) . "</td>
        </tr>";
    }

} else {
    echo "<td colspan='5'>Invalid Report Type</td></tr>";
}





echo "</table>";
?>