<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// include_once 'product-action.php'; 
$cartId = $_GET['cartId'];


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
// details here
$result = $index->viewCheckOutDetails($cartId);
while ($row = mysqli_fetch_object($result)) {
    $dishesName    = $row->dishesName ?? 'N/A';
    $totalPrice    = $row->totalPrice ?? 'No Data';
    $restauName    = $row->restauName ?? '';
    $userAddress   = $row->userAddress ?? 'No Address';
}

if (isset($_POST['submit'])) {
    // Collect form data
    $total_price    = $_POST['total_price'] ?? 0;
    $mod            = $_POST['mod'] ?? '';
    $delivery_type  = $_POST['delivery_type'] ?? 'standard';

    // Handle GCash Proof Upload (only if GCash is selected)
    $gcash_proof = null;
    if ($mod == "GCash" && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] == 0) {
        $uploadDir = "uploads/"; // Make sure this folder exists and is writable
        $gcash_proof = $uploadDir . basename($_FILES['gcash_proof']['name']);
        move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $gcash_proof);
    }

    // Assume these variables are available / get them properly:
    $user_id = $_SESSION['user_id'] ?? null; // Adjust depending on your login/session logic
    $quantity = 1; // or however you define it
    $d_id = $cartId; // assuming cart ID is dish ID or however you track

    if ($user_id && $d_id) {
        // Call the model function
        $result = $index->checkoutOrder(
            $user_id,
            $quantity,
            $d_id,
            $gcash_proof,
            $mod,
            $delivery_type,
            $total_price,
            $dishesName,
            $restauName,
            $userAddress
        );

        if ($result) {
            $_SESSION['message'] = ['type' => 'success', 'message' => 'Order placed successfully!'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to place the order.'];
        }

        header("Location: dishes.php?res_id=" . $_GET['res_id']);
        exit();
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid user or item.'];
    }
}






?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">Checkout
                            <?php echo $cartId ?>

                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">

                            <h5 class="mb-3">Cart Summary</h5>

                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Cart Subtotal</td>
                                        <td id="cartSubtotal" class="text-end">₱<?php echo number_format($totalPrice, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Charges</td>
                                        <td id="deliveryCharges" class="text-end">₱30.00</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end">
                                            ₱<span id="totalPriceText"><?php echo number_format($totalPrice + 30, 2); ?></span>
                                            <input type="hidden" name="total_price" id="totalPriceInput" value="<?php echo $totalPrice + 30; ?>">
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <div class="mb-3">
                                <label class="form-label">Restaurant Name:</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($restauName); ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address:</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($userAddress); ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Payment Options</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mod" value="COD" id="cod" checked onclick="toggleUpload()">
                                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mod" value="GCash" id="gcash" onclick="toggleUpload()">
                                    <label class="form-check-label" for="gcash">GCash</label>
                                </div>
                            </div>

                            <div id="gcash-upload" class="mb-3" style="display: none;">
                                <label for="gcash-proof" class="form-label">Upload GCash Payment Proof</label>
                                <input type="file" name="gcash_proof" id="gcash-proof" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Delivery Type</label>
                                <select name="delivery_type" class="form-select">
                                    <option value="standard">Standard Delivery (₱30)</option>
                                    <option value="rush">Rush Delivery (₱50)</option>
                                </select>
                            </div>

                            <div class="d-grid my-3">
                                <button type="submit" name="submit" class="btn btn-success" onclick="return confirm('Confirm the order?');">
                                    Order Now
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

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