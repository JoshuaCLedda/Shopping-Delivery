<?php
include("connection/connect.php");

if (isset($_GET['order_del'])) {
    $order_id = $_GET['order_del'];
    $query = "DELETE FROM transaction WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: your_orders.php");
}
?>
