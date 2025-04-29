<?php
session_start();
include "../admin/Main.php";
$index = new Index;

if (!isset($_SESSION['user_id']) || !isset($_GET['order_upd'])) {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Unauthorized access or missing transaction.'];
    header("Location: pending_orders.php");
    exit();
}

$rider_id = (int) $_SESSION['user_id'];
$transacID = (int) $_GET['order_upd']; // Cast to integer to prevent SQL injection

// Simple SQL update

$query = "
    UPDATE `transaction`
    SET status = 'order_confirmed',
        rider_id = $rider_id,
    WHERE transacID = $transacID
";


if (mysqli_query($index->con, $query)) {
    $_SESSION['message'] = ['type' => 'success', 'message' => 'Order has been confirmed and assigned to you.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to confirm order.'];
}

header("Location: received_orders.php");
exit();
?>
