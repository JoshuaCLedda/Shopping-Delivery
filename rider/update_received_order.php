<?php
session_start();
include "../admin/Main.php"; 

$index = new Index;

if (!isset($_SESSION['user_id']) || !isset($_GET['order_upd'])) {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Unauthorized access or missing transaction ID.'];
    header("Location: received_orders.php");
    exit();
}

$rider_id = $_SESSION['user_id'];
$transacID = intval($_GET['order_upd']);

$query = "
    UPDATE transaction 
    SET status = 'order_delivered', 
        updated_at = NOW() 
    WHERE id = $transacID AND rider_id = $rider_id
";

if (mysqli_query($index->con, $query)) {
    $_SESSION['message'] = ['type' => 'success', 'message' => 'Order marked as delivered.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to update order.'];
}

header("Location: delivered_orders.php");
exit();
