<?php
session_start();
include "../admin/Main.php"; 

$index = new Index;if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../login.php");
    exit();
}

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
    // Insert income record
    $incomeQuery = "
        INSERT INTO rider_income (rider_id, transaction_id, amount) 
        VALUES ($rider_id, $transacID, 40.00)
    ";
    mysqli_query($index->con, $incomeQuery);

    $_SESSION['message'] = ['type' => 'success', 'message' => 'Order marked as delivered and rider income recorded.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to update order.'];
}

header("Location: delivered_orders.php");
exit();
