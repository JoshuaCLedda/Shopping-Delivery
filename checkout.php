<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// include_once 'product-action.php'; 
// $cartId = $_GET['cartId'];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    include "admin/Main.php";
    $index = new Index;

    $query = "SELECT f_name, l_name, username, email, phone, address FROM users WHERE u_id = '$user_id'";
    $result = mysqli_query($index->con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

$selectedCarts = $_POST['selected_items'] ?? $_SESSION['selected_items'] ?? [];

if (!empty($_POST['selected_items'])) {
    $_SESSION['selected_items'] = $_POST['selected_items'];
}

$totalPrice = 0;
$restauName = '';
$userAddress = $user['address'] ?? '';
// Carts View/Details
if (!empty($selectedCarts)) {
    foreach ($selectedCarts as $cartId) {
        $result = mysqli_query(
            $index->con,
            "SELECT carts.*, dishes.price, dishes.title AS dishName, carts.dishes_id AS dishId,
            restaurant.title AS restauName
            FROM carts
            LEFT JOIN dishes ON carts.dishes_id = dishes.d_id
            LEFT JOIN restaurant ON dishes.rs_id = restaurant.rs_id
            WHERE carts.id = '$cartId'"
        );

        if ($row = mysqli_fetch_assoc($result)) {
            $totalPrice += ($row['price'] * $row['quantity']);
            $restauName = $row['restauName'];
        }
    }
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'No cart selected.'];
    exit();
}

////Submit the form
if (isset($_POST['submit'])) {
    // Collect form data
    $total_price    = $_POST['total_price'] ?? 0;
    $mod            = $_POST['mod'] ?? '';
    $address            = $_POST['address'] ?? '';
    $delivery_type  = $_POST['delivery_type'] ?? 'standard';
    $selectedCarts  = $_POST['selected_items'] ?? [];

    $gcash_proof = null;
    if ($mod == "GCash" && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] == 0) {
        $uploadDir = "uploads/";
        $gcash_proof = $uploadDir . basename($_FILES['gcash_proof']['name']);
        move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $gcash_proof);
    }

    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id && !empty($selectedCarts)) {
        $all_success = true;

        // Fetch first cart just to get user address (you can also separately get it)
        $cartResultFirst = mysqli_query(
            $index->con,
            "SELECT users.address AS userAddress
            FROM carts
            LEFT JOIN users ON carts.user_id = users.u_id
            WHERE carts.id = '" . $selectedCarts[0] . "'"
        );
        $cartFirstRow = mysqli_fetch_assoc($cartResultFirst);
        $userAddress = $cartFirstRow['userAddress'] ?? '';

        // 🔥 Create only ONE transaction first
        $transaction_id = $index->checkoutOrder(
            $user_id,
            $gcash_proof,
            $mod,
            $delivery_type,
            $total_price,
            $userAddress,
            $address

        );
        if ($transaction_id) {
            $all_success = true;

            foreach ($selectedCarts as $cartId) {
                $cartResult = mysqli_query(
                    $index->con,
                    "SELECT carts.*, (dishes.price * carts.quantity) AS dishTotal
                    FROM carts
                    LEFT JOIN dishes ON carts.dishes_id = dishes.d_id
                    WHERE carts.id = '$cartId'"
                );

                if ($cartRow = mysqli_fetch_assoc($cartResult)) {
                    $quantity     = $cartRow['quantity'];
                    $dishesId     = $cartRow['dishes_id'];
                    $dishTotal    = $cartRow['dishTotal'];

                    $insertOrderItem = mysqli_query(
                        $index->con,
                        "INSERT INTO order_items (transaction_id, dishes_id, quantity, total_price)
                         VALUES ('$transaction_id', '$dishesId', '$quantity', '$dishTotal')"
                    );

                    if ($insertOrderItem) {
                        // Delete the cart item since it was successfully added to order_items
                        mysqli_query($index->con, "DELETE FROM carts WHERE id = '$cartId'");
                    } else {
                        $all_success = false;
                    }
                }
            }

            if ($all_success) {
                $_SESSION['message'] = ['type' => 'success', 'message' => 'All orders placed successfully!'];
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'message' => 'Some items failed to be placed.'];
            }
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Transaction creation failed.'];
        }

        header("Location: your_orders.php");
        exit();
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'No items selected or invalid user.'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourfontawesomekit.js" crossorigin="anonymous"></script> <!-- Add your FA Kit -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .cart-card {
            background: #f8f9fa;
            /* light gray background */
            border-radius: 1rem;
            /* rounded corners */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            /* softer shadow */
            transition: all 0.3s ease;
            /* smooth animation */
        }

        .cart-card:hover {
            transform: translateY(-5px);
            /* lift up */
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            /* deeper shadow on hover */
        }

        .cart-badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 50rem;
            /* pill shape */
        }
    </style>

<body>



    <?php include 'layouts/navbar.php'; ?>

    <body class="bg-light">
        <div class="bg-light py-3 border-bottom">
            <div class="container">
                <div class="row text-center">
                    <!-- Step 1 -->
                    <div class="col-md-4 mb-2">
                        <div class="p-2 rounded text-dark">
                            <span class="fw-bold me-2">1</span>
                            <a href="restaurants.php" class="text-decoration-none text-dark">Choose Stall</a>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-md-4 mb-2">
                        <div class="p-2 rounded text-dark">
                            <span class="fw-bold me-2">2</span>
                            <a href="dishes.php" class="text-decoration-none text-dark">Pick Your Favorite Food</a>
                        </div>
                    </div>

                    <!-- Step 3 (Active) -->
                    <div class="col-md-4 mb-2">
                        <div class="p-2 rounded bg-primary text-white">
                            <span class="fw-bold me-2">3</span>
                            <a href="#" class="text-decoration-none text-white">Order and Pay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <h3 class="mb-4 fw-bold text-center">🛍️ Checkout</h3>

                    <form action="" method="post" enctype="multipart/form-data">
                        <?php foreach ($selectedCarts as $cartId): ?>
                            <input type="hidden" name="selected_items[]" value="<?php echo htmlspecialchars($cartId); ?>">
                        <?php endforeach; ?>

                        <!-- Cart Summary -->
                        <div class="card cart-card border-0 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">🛒 Cart Summary</h5>

                                <div class="list-group list-group-flush">
                                    <?php
                                    foreach ($selectedCarts as $cartId):
                                        $dishResult = mysqli_query(
                                            $index->con,
                                            "SELECT dishes.title AS dishName, (dishes.price * carts.quantity) AS dishTotal
                                    FROM carts
                                    LEFT JOIN dishes ON carts.dishes_id = dishes.d_id
                                    WHERE carts.id = '$cartId'"
                                        );
                                        if ($dish = mysqli_fetch_assoc($dishResult)):
                                    ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                <div class="fw-semibold"><?php echo htmlspecialchars($dish['dishName']); ?></div>
                                                <span class="badge bg-primary cart-badge">₱<?php echo number_format($dish['dishTotal'], 2); ?></span>
                                            </div>
                                    <?php endif;
                                    endforeach; ?>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <span>Cart Subtotal</span>
                                    <strong>₱<?php echo number_format($totalPrice, 2); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Delivery Charges</span>
                                    <strong id="deliveryCharges">₱30.00</strong>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                                    <span>Total</span>
                                    <span>₱<span id="totalPriceText"><?php echo number_format($totalPrice + 30, 2); ?></span></span>
                                </div>

                                <input type="hidden" name="total_price" id="totalPriceInput" value="<?php echo $totalPrice + 30; ?>">
                            </div>
                        </div>

                        <!-- Address & Restaurant Info -->
                        <div class="card cart-card border-0 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">📍 Delivery Info</h5>

                                <p class="mb-2">
                                    <strong>Restaurant:</strong> <?php echo htmlspecialchars($restauName); ?>
                                </p>
                                <div class="d-flex align-items-center mb-3">
                                    <strong class="me-2" style="white-space: nowrap;">Address:</strong>
                                    <input type="text" class="form-control flex-grow-1" name="address" required ?>
                                </div>

                            </div>
                        </div>

                        <!-- Payment Options -->
                        <div class="card cart-card border-0 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">💳 Payment Options</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="mod" value="COD" id="cod" checked onclick="toggleUpload()">
                                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mod" value="GCash" id="gcash" onclick="toggleUpload()">
                                    <label class="form-check-label" for="gcash">GCash</label>
                                </div>

                                <div id="gcash-upload" class="mt-3" style="display: none;">
                                    <label for="gcash-proof" class="form-label">Upload GCash Payment Proof</label>
                                    <input type="file" name="gcash_proof" id="gcash-proof" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Type -->
                        <div class="card cart-card border-0 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">🚚 Delivery Type</h5>
                                <select name="delivery_type" class="form-select" onchange="updateDeliveryCharge()">
                                    <option value="standard" selected>Standard Delivery (₱30)</option>
                                    <option value="rush">Rush Delivery (₱50)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid my-4">
                            <button type="submit" name="submit" class="btn btn-success btn-lg rounded-pill fw-bold" onclick="return confirm('Confirm the order?');">
                                <i class="bx bx-cart me-2"></i>Order Now
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <script>
            function toggleUpload() {
                const gcashUpload = document.getElementById('gcash-upload');
                const gcashRadio = document.getElementById('gcash');
                gcashUpload.style.display = gcashRadio.checked ? 'block' : 'none';
            }

            function updateDeliveryCharge() {
                const deliveryType = document.querySelector('select[name="delivery_type"]').value;
                const deliveryCharges = document.getElementById('deliveryCharges');
                const totalPriceText = document.getElementById('totalPriceText');
                const totalPriceInput = document.getElementById('totalPriceInput');

                let newCharge = (deliveryType === 'rush') ? 50 : 30;
                let cartSubtotal = <?php echo $totalPrice; ?>;
                let newTotal = cartSubtotal + newCharge;

                deliveryCharges.innerText = `₱${newCharge.toFixed(2)}`;
                totalPriceText.innerText = newTotal.toFixed(2);
                totalPriceInput.value = newTotal.toFixed(2);
            }
        </script>


















<?php include 'layouts/sweetalert.php'; ?>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                let basePrice = <?php echo $totalPrice ? (float)$totalPrice : 0; ?>;

                function updateTotal() {
                    let deliveryCharge = 0;

                    if ($('select[name="delivery_type"]').val() === 'standard') {
                        deliveryCharge = 30;
                    } else if ($('select[name="delivery_type"]').val() === 'rush') {
                        deliveryCharge = 50;
                    }

                    let finalTotal = basePrice + deliveryCharge;

                    // Update display
                    $('#totalPriceText').text(finalTotal.toFixed(2));

                    // Update hidden input value
                    $('#totalPriceInput').val(finalTotal.toFixed(2));

                    // Update delivery charges display too (optional)
                    $('#deliveryCharges').text('₱' + deliveryCharge.toFixed(2));
                }

                $('select[name="delivery_type"]').on('change', updateTotal);

                updateTotal(); // trigger on page load
            });
        </script>


        <script>
            function toggleUpload() {
                const gcashUpload = document.getElementById('gcash-upload');
                const gcashRadio = document.getElementById('gcash');

                if (gcashRadio.checked) {
                    gcashUpload.style.display = 'block';
                } else {
                    gcashUpload.style.display = 'none';
                }
            }
        </script>

        <script>
            function toggleUpload() {
                const gcashUpload = document.getElementById('gcash-upload');
                const gcashRadio = document.querySelector('input[name="mod"][value="GCash"]');

                if (gcashRadio.checked) {
                    gcashUpload.style.display = 'block';
                } else {
                    gcashUpload.style.display = 'none';
                }
            }
        </script>


        <script>
            function toggleUpload() {
                let gcashUpload = document.getElementById("gcash-upload");
                let selectedValue = document.querySelector('input[name="mod"]:checked').value;

                if (selectedValue === "GCash") {
                    gcashUpload.style.display = "block";
                } else {
                    gcashUpload.style.display = "none";
                }
            }
        </script>

    </body>

</html>