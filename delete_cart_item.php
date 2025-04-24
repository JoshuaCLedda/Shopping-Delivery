<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "admin/Main.php";

$index = new Index;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if cart ID is provided and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cartId = intval($_GET['id']);
    $userId = intval($_SESSION['user_id']);

    // Run DELETE query directly
    $query = mysqli_query($db, "DELETE FROM carts WHERE id = '$cartId' AND user_id = '$userId'");

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
