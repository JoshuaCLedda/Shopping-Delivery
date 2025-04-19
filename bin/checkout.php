<?php
include("connection/connect.php");
error_reporting(E_ALL);
session_start();

// Initialize total values to prevent undefined variable errors
$item_total = 0;
$delivery_charge = 0;

// Function to display alert and redirect to orders page
function function_alert_redirect($transaction_id) {
    echo "<script>window.location.replace('receipt.php?transaction_id=$transaction_id');</script>";
}

// Check if cart is not empty
if (isset($_SESSION["cart_item"]) && count($_SESSION["cart_item"]) > 0) {
    $total_quantity = 0;
    $combined_titles = "";
    $user_address = isset($_SESSION["user_address"]) ? $_SESSION["user_address"] : '';

    foreach ($_SESSION["cart_item"] as $item) {
        $item_total += ($item["price"] * $item["quantity"]);
        $total_quantity += $item["quantity"];
        $combined_titles .= $item["title"] . ", ";
    }

    $combined_titles = rtrim($combined_titles, ", ");

    // Get selected delivery type
    if (isset($_POST['delivery_type'])) {
        $delivery_charge = ($_POST['delivery_type'] == 'rush') ? 50 : 30;
    }

    $item_total += $delivery_charge;

    if (isset($_POST['submit'])) {
        $user_id = mysqli_real_escape_string($db, $_SESSION["user_id"]);
        $restaurant_id = mysqli_real_escape_string($db, $_POST["restaurant_id"]);
        $combined_titles = mysqli_real_escape_string($db, $combined_titles);
        $user_address = mysqli_real_escape_string($db, $user_address);
        $payment_method = mysqli_real_escape_string($db, $_POST["mod"]);

        // Handle GCash file upload
        $gcash_proof = "";
        if ($payment_method === "GCash" && isset($_FILES["gcash_proof"])) {
            $target_dir = "uploads/"; // Create 'uploads' folder in your project root
            $target_file = $target_dir . basename($_FILES["gcash_proof"]["name"]);

            if (move_uploaded_file($_FILES["gcash_proof"]["tmp_name"], $target_file)) {
                $gcash_proof = mysqli_real_escape_string($db, $target_file);
            } else {
                echo "Error uploading GCash proof.";
                exit;
            }
        }

        // Insert into 'transaction' table
        $SQL_transaction = "INSERT INTO transaction (u_id, titles, total_quantity, total_price, address, rs_id, status, payment_method, gcash_proof)
                            VALUES('$user_id', '$combined_titles', '$total_quantity', '$item_total', '$user_address', '$restaurant_id', 'place_order', '$payment_method', '$gcash_proof')";
        $transaction_result = mysqli_query($db, $SQL_transaction);

        if ($transaction_result) {
            $transaction_id = mysqli_insert_id($db);

            foreach ($_SESSION["cart_item"] as $item) {
                $title = mysqli_real_escape_string($db, $item["title"]);
                $quantity = (int)$item["quantity"];
                $price = (float)$item["price"];
                $stall_name = mysqli_real_escape_string($db, $item["stall"]); // Get the stall name

                // Insert into users_orders table including stall name
                $SQL_orders = "INSERT INTO users_orders(u_id, title, quantity, price, address, rs_id, status, transaction_id, payment_method, gcash_proof, stall)
                               VALUES('$user_id', '$title', '$quantity', '$price', '$user_address', '$restaurant_id', 'place_order', '$transaction_id', '$payment_method', '$gcash_proof', '$stall_name')";

                $order_result = mysqli_query($db, $SQL_orders);
                if (!$order_result) {
                    echo "Error inserting order item: " . mysqli_error($db);
                    exit;
                }
            }

            unset($_SESSION["cart_item"]);
            function_alert_redirect($transaction_id);
        } else {
            echo "Error inserting transaction: " . mysqli_error($db);
        }
    }
} else {
    echo "<script>alert('Your cart is empty. Please add items to proceed.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Checkout</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container m-t-30">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="widget clearfix">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Cart Summary</h4>
                            <table class="table">
                                <tbody>
                                    <tr><td>Cart Subtotal</td><td>₱<?php echo number_format($item_total - $delivery_charge, 2); ?></td></tr>
                                    <tr><td>Delivery Charges</td><td>₱<?php echo number_format($delivery_charge, 2); ?></td></tr>
                                    <tr><td><strong>Total</strong></td><td><strong>₱<?php echo number_format($item_total, 2); ?></strong></td></tr>
                                </tbody>
                            </table>

                            <h5>Select Restaurant</h5>
                            <select name="restaurant_id" class="form-control" required>
                                <option value="">-- Select a Restaurant --</option>
                                <?php
                                $query = "SELECT rs_id, title FROM restaurant ORDER BY title ASC";
                                $result = mysqli_query($db, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='".$row['rs_id']."'>".$row['title']."</option>";
                                }
                                ?>
                            </select>

                            <h5>Your Address</h5>
                            <?php if (empty($user_address)): ?>
                                <p>Please provide a valid address.</p>
                            <?php else: ?>
                                <p><?php echo htmlspecialchars($user_address); ?></p>
                            <?php endif; ?>

                            <h5>Payment Options</h5>
                            <label><input name="mod" value="COD" type="radio" checked onclick="toggleUpload()"> Cash on Delivery</label><br>
                            <label><input name="mod" value="GCash" type="radio" onclick="toggleUpload()"> GCash</label>

                            <!-- Hidden File Input for GCash Proof -->
                            <div id="gcash-upload" style="display: none; margin-top: 10px;">
                                <label for="gcash-proof">Upload GCash Payment Proof:</label>
                                <input type="file" name="gcash_proof" id="gcash-proof" accept="image/*">
                            </div>

                            <h5>Delivery Type</h5>
                            <select name="delivery_type" class="form-control">
                                <option value="standard">Standard Delivery (₱30)</option>
                                <option value="rush">Rush Delivery (₱50)</option>
                            </select>

                            <p class="text-xs-center">
                                <input type="submit" onclick="return confirm('Confirm the order?');" name="submit" class="btn btn-success btn-block" value="Order Now">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

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