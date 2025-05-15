<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'assets/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'assets/vendor/phpmailer/phpmailer/src/Exception.php';
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
                    "SELECT carts.*, (dishes.price * carts.quantity) AS dishTotal, dishes.available_quantity
                     FROM carts
                     LEFT JOIN dishes ON carts.dishes_id = dishes.d_id
                     WHERE carts.id = '$cartId'"
                );

                if ($cartRow = mysqli_fetch_assoc($cartResult)) {
                    $quantity     = $cartRow['quantity'];
                    $dishesId     = $cartRow['dishes_id'];
                    $dishTotal    = $cartRow['dishTotal'];
                    $availableQty = $cartRow['available_quantity'];

                    // Optional: Check if there's enough stock
                    if ($availableQty < $quantity) {
                        // Not enough stock, handle error
                        $all_success = false;
                        continue;
                    }

                    $insertOrderItem = mysqli_query(
                        $index->con,
                        "INSERT INTO order_items (transaction_id, dishes_id, quantity, total_price)
                         VALUES ('$transaction_id', '$dishesId', '$quantity', '$dishTotal')"
                    );

                    if ($insertOrderItem) {
                        // 1. Deduct quantity from dishes.available_quantity
                        mysqli_query(
                            $index->con,
                            "UPDATE dishes 
                             SET available_quantity = available_quantity - $quantity 
                             WHERE d_id = '$dishesId'"
                        );

                        // 2. Delete the cart item
                        mysqli_query($index->con, "DELETE FROM carts WHERE id = '$cartId'");
                    } else {
                        $all_success = false;
                    }
                }
            }

            // email parts
            if ($all_success) {
                $_SESSION['message'] = ['type' => 'success', 'message' => 'All orders placed successfully!'];

                // ✅ Send confirmation email
                require 'assets/vendor/autoload.php'; // Composer autoload


                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'kirararararara09@gmail.com';        // Your Gmail
                    $mail->Password   = 'cytr leyz qwbg zooh';               // App password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom('kirararararara09@gmail.com', 'Your Store');
                    $mail->addAddress($user['email'], 'Customer');           // Assuming $user['email'] is fetched earlier

                    // html body
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Order Has Been Placed – #' . $transaction_id;
                    $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;'>
                        <h2 style='color: #2E86C1; text-align: center;'>🎉 Order Confirmation</h2>
                        <p style='font-size: 16px;'>Hello,</p>
                        <p style='font-size: 16px;'>Thank you for shopping with us! We're happy to confirm your order has been placed successfully.</p>

                        <table style='width: 100%; margin-top: 20px; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 8px; font-weight: bold;'>🆔 Transaction ID:</td>
                                <td style='padding: 8px;'>#{$transaction_id}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; font-weight: bold;'>💰 Total Amount:</td>
                                <td style='padding: 8px;'>₱{$total_price}</td>
                            </tr>
                        </table>

                        <p style='margin-top: 20px; font-size: 15px;'>We’ve received your order and will begin processing it shortly. You’ll receive another update when your order is ready to be delivered.</p>
                        
                        <p style='margin-top: 30px; font-size: 14px; color: #777; text-align: center;'>If you have any questions, just reply to this email. We're here to help!</p>
                        <hr style='margin: 20px 0; border: none; border-top: 1px solid #ccc;'>
                        <p style='text-align: center; font-size: 14px; color: #aaa;'>© " . date('Y') . " Your Store. All rights reserved.</p>
                    </div>
                ";














                    $mail->AltBody = "Your order #{$transaction_id} has been placed. Total: ₱{$total_price}.";
                    $mail->send();
                } catch (Exception $e) {
                    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
                }
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


    <?php include 'layouts/header.php'; ?>
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
    <div class="top-links mt-2
bg-light border-bottom">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                <div class="link-item active">
                    <i class='bx bx-store-alt fs-3 mb-1'></i> <!-- Icon for Choose Stall -->
                    <a href="#" class="d-block text-decoration-none text-dark">Choose Stall</a>
                </div>
            </div>
            <div class="col-12 col-sm-4 mb-2 mb-sm-0 ">
                <div class="link-item">
                    <i class='bx bx-food-menu fs-3 mb-1 '></i> <!-- Icon for Pick Your Favorite Food -->
                    <a href="#" class="d-block text-decoration-none text-dark">Pick Your Favorite Food</a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="link-item">
                    <i class='bx bx-cart fs-3 mb-1 text-primary'></i> <!-- Icon for Order and Pay -->
                    <a href="#" class="d-block text-decoration-none text-dark">Order and Pay</a>
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