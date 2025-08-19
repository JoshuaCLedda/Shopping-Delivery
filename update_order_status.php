<?php
include("connection/connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $trans_id = isset($_POST['trans_id']) ? intval($_POST['trans_id']) : intval($_GET['order_id']);

    if ($trans_id <= 0) {
        die("Error: Invalid Transaction ID.");
    }

    // Check current status
    $check_query = "SELECT status FROM transaction WHERE id = $trans_id";
    $result = mysqli_query($db, $check_query);
    $row = mysqli_fetch_assoc($result);

    if ($row && $row['status'] === 'order_received') {
        $_SESSION['message'] = ['type' => 'warning', 'message' => 'Order is already marked as received.'];
        header("Location: your_orders.php");
        exit();
    }

    // Update status to "Order Received"
    $sql = "UPDATE transaction SET status = 'order_received' WHERE id = $trans_id";

    if (mysqli_query($db, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Order marked as received!'];
        header("Location: your_orders.php");
        exit();
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Error updating order: ' . mysqli_error($db)];
        header("Location: your_orders.php");
        exit();
    }
} else {
    die("Invalid request method.");
}
?>
