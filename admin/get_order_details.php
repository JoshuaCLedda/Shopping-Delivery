<?php
include("../connection/connect.php");
error_reporting(0);
$trans_id = isset($_GET['trans_id']) ? $_GET['trans_id'] : '';

// Fetch order details
$sql = "SELECT transaction.id, transaction.total_price, transaction.status, transaction.order_date, user.username, stall.name AS stall_name 
        FROM transaction
        JOIN user ON transaction.user_id = user.id
        JOIN stall ON transaction.stall_id = stall.id
        WHERE transaction.id = '$trans_id'";

$query = mysqli_query($db, $sql);

if ($query) {
    $order = mysqli_fetch_assoc($query);
    if ($order) {
        echo "<h4>User: " . $order['username'] . "</h4>";
        echo "<p><strong>Total Price:</strong> ₱" . number_format($order['total_price'], 2) . "</p>";
        echo "<p><strong>Stall:</strong> " . $order['stall_name'] . "</p>";
        echo "<p><strong>Status:</strong> " . $order['status'] . "</p>";
        echo "<p><strong>Order Date:</strong> " . date("F j, Y, g:i a", strtotime($order['order_date'])) . "</p>";
        // Add more details as needed
    } else {
        echo "<p>No order details found.</p>";
    }
} else {
    echo "<p>Error fetching order details.</p>";
}
?>
