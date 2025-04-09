<?php
include("connection/connect.php");
error_reporting(E_ALL);
session_start();

// Check if the transaction_id is passed in the URL
if (isset($_GET['transaction_id'])) {
    $transaction_id = mysqli_real_escape_string($db, $_GET['transaction_id']);

    // Fetch transaction details
    $SQL_transaction = "SELECT * FROM transaction WHERE id = '$transaction_id'"; // Use 'id' instead of 'transaction_id'
    $transaction_result = mysqli_query($db, $SQL_transaction);

    if ($transaction_result && mysqli_num_rows($transaction_result) > 0) {
        $transaction = mysqli_fetch_assoc($transaction_result);

        // Fetch the order details for the transaction
        $SQL_orders = "SELECT * FROM users_orders WHERE transaction_id = '$transaction_id'";
        $order_result = mysqli_query($db, $SQL_orders);
    } else {
        echo "Transaction not found!";
        exit;
    }
} else {
    echo "No transaction found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order Receipt</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container m-t-30">
        <h4>Your Order Receipt</h4>
        <div class="widget clearfix">
            <div class="widget-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p><strong>Transaction ID:</strong> <?php echo $transaction['id']; ?></p>
                        <p><strong>User ID:</strong> <?php echo $transaction['u_id']; ?></p>
                        <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($transaction['address']); ?></p>
                        <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i:s', strtotime($transaction['order_date'])); ?></p>
                        <p><strong>Total Quantity:</strong> <?php echo $transaction['total_quantity']; ?></p>
                        <p><strong>Total Price:</strong> ₱<?php echo number_format($transaction['total_price'], 2); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($transaction['status']); ?></p>
                        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($transaction['payment_status']); ?></p>

                        <h5>Ordered Items:</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item Title</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($order_result)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['title']); ?></td>
                                        <td><?php echo $order['quantity']; ?></td>
                                        <td>₱<?php echo number_format($order['price'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($transaction['payment_status']); ?></p>

                        <p class="text-xs-center">
                            <a href="your_orders.php" class="btn btn-primary btn-block">View Your Orders</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="row bottom-footer">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 payment-options color-gray">
                        <h5>Payment Options</h5>
                        <ul>
                            <li><a href="#"> <img src="images/paypal.png" alt="Paypal"> </a></li>
                            <li><a href="#"> <img src="images/mastercard.png" alt="Master Card"> </a></li>
                            <li><a href="#"> <img src="images/visa.png" alt="Visa"> </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>