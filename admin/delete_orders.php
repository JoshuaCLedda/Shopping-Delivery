<?php
include("../connection/connect.php");

if (isset($_GET['trans_del'])) {
    $trans_id = $_GET['trans_del'];

    // Delete the order from the transaction table
    $sql = "DELETE FROM transaction WHERE id = $trans_id";
    
    if (mysqli_query($db, $sql)) {
        // Redirect back to the orders page after successful deletion
        header('Location: all_orders.php');
        exit();
    } else {
        echo "Error deleting order: " . mysqli_error($db);
    }
} else {
    echo "Transaction ID not provided!";
}
