<?php
include("connection/connect.php");
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $user_id = $_SESSION['user_id'];

    // Make sure the order belongs to the logged-in user
    $check_query = mysqli_query($db, "SELECT * FROM transaction WHERE id = '$order_id' AND u_id = '$user_id'");
    
    if (mysqli_num_rows($check_query) > 0) {
        // Update the order status to 'Order_Canceled'
        $update_query = mysqli_query($db, "UPDATE transaction SET status = 'Order_Canceled' WHERE id = '$order_id'");

        if ($update_query) {
            header("Location: your_orders.php?status=canceled");
            exit;
        } else {
            echo "Error canceling order. Please try again.";
        }
    } else {
        echo "You don't have permission to cancel this order.";
    }
} else {
    echo "Order ID not provided.";
}
?>
