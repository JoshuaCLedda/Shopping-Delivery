<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "admin/Main.php";

$index = new Index;

// Check if cart ID is provided and valid
if (isset($_GET['cartId']) && is_numeric($_GET['cartId'])) {
    $cartId = intval($_GET['cartId']);

    // Run DELETE query directly
    $query = mysqli_query($db, "DELETE FROM carts WHERE id = '$cartId'");


    if ($query) {
        $_SESSION['alert'] = "Item removed from your cart successfully.";
    } else {
        $_SESSION['alert'] = "Failed to remove item from your cart.";
    }
} else {
    $_SESSION['alert'] = "Invalid cart item ID.";
}

// Redirect to cart page
header("Location: carts.php");
exit();
?>
