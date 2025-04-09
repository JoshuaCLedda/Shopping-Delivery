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

    if ($row && $row['status'] === 'Order_Received') {
        echo "Order is already marked as received.";
        exit();
    }

    // Update status to "Order Received"
    $sql = "UPDATE transaction SET status = 'Order_Received' WHERE id = $trans_id";

    if (mysqli_query($db, $sql)) {
        header("Location: your_orders.php"); // Redirect after update
        exit();
    } else {
        echo "Error updating order: " . mysqli_error($db);
    }
} else {
    die("Invalid request method.");
}
?>
