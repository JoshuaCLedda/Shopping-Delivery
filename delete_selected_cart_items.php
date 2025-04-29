<?php
session_start();
require_once 'admin/Main.php'; // Adjust path

$index = new Index();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $selectedItems = $_POST['selected_items'];

    foreach ($selectedItems as $cartId) {
        $cartId = intval($cartId);
        $index->deleteCartItem($cartId);
    }

    $_SESSION['message'] = [
        'type' => 'success',
        'message' => 'Selected cart items deleted successfully.'
    ];
} else {
    $_SESSION['message'] = [
        'type' => 'warning',
        'message' => 'No items selected.'
    ];
}

header('Location: carts.php'); 
exit;
