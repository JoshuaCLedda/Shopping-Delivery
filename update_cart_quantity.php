<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin/Main.php';
$index = new Index();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = $_POST['cart_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$cart_id || !in_array($action, ['increase', 'decrease'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get current cart item
$query = "SELECT quantity, dishes_id FROM carts WHERE id = ? AND user_id = ?";
$stmt = $index->con->prepare($query);
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    exit;
}
$cartItem = $result->fetch_assoc();
$currentQty = (int)$cartItem['quantity'];
$dishId = (int)$cartItem['dishes_id']; // ✅ fixed here

// Get available quantity of the dish
$dishQuery = "SELECT available_quantity FROM dishes WHERE d_id = ?";
$dishStmt = $index->con->prepare($dishQuery);
$dishStmt->bind_param("i", $dishId);
$dishStmt->execute();
$dishResult = $dishStmt->get_result();

if ($dishResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Dish not found']);
    exit;
}

$dishData = $dishResult->fetch_assoc();
$maxAvailable = (int)$dishData['available_quantity'];

// Calculate new quantity
$newQty = $action === 'increase' ? $currentQty + 1 : $currentQty - 1;
if ($newQty < 1) $newQty = 1;
if ($newQty > $maxAvailable) {
    echo json_encode(['success' => false, 'message' => "Only $maxAvailable available"]);
    exit;
}

// Update cart
$update = "UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?";
$updateStmt = $index->con->prepare($update);
$updateStmt->bind_param("iii", $newQty, $cart_id, $user_id);
$updateStmt->execute();


// Fetch the price as well
$priceQuery = "SELECT price FROM dishes WHERE d_id = ?";
$priceStmt = $index->con->prepare($priceQuery);
$priceStmt->bind_param("i", $dishId);
$priceStmt->execute();
$priceResult = $priceStmt->get_result();
$priceRow = $priceResult->fetch_assoc();
$unitPrice = (float)$priceRow['price'];


echo json_encode([
    'success' => true,
    'new_quantity' => $newQty,
    'price' => $unitPrice
]);
exit;