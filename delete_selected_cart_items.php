<?php
session_start();
error_reporting(E_ALL);
include "admin/Main.php";
$index = new Index;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $selectedItems = $_POST['selected_items'];

    foreach ($selectedItems as $cartId) {
        $cartId = intval($cartId);
        $index->deleteCartItem($cartId);
    }
    $_SESSION['message'] = [
        'type' => 'success',
        'message' => 'Cart Items Deleted Successfuly.'
    ];
    
} else {
    $_SESSION['message'] = [
        'type' => 'warning',
        'message' => 'No items selected.'
    ];
}

header('Location: carts.php'); 
exit;
