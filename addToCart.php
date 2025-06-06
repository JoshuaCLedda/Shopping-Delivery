<?php
session_start();
include "admin/Main.php";

$index = new Index;
$db = $index->con; // ✅ Use the correct connection from the class

$userId = $_POST['user_id'] ?? 0;
$selectedDishes = $_POST['selected_dishes'] ?? [];

if ($userId && !empty($selectedDishes)) {
    foreach ($selectedDishes as $dishId) {
        $stmt = $db->prepare("INSERT INTO carts (user_id, dishes_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $userId, $dishId);
        $stmt->execute();
    }

    // ✅ Set session message
    $_SESSION['success'] = count($selectedDishes) . " item(s) added to your cart.";

    header("Location: carts.php");
    exit;
} else {
    $_SESSION['error'] = "No dishes selected or user not logged in.";
    header("Location: carts.php");
    exit;
}
