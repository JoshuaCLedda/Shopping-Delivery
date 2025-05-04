<?php
session_start();
include("connection/connect.php");
error_reporting(0);

if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-card {
            background: #f8f9fa;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .order-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body>

    <?php include 'layouts/navbar.php'; ?>

    <div class="page-wrapper py-4">
        <section class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <h2 class="text-center mb-4 fw-bold">🛒 My Orders</h2>

                    <!-- Filter Dropdown -->
                    <div class="mb-4 text-end">
                        <select id="statusFilter" class="form-select w-auto d-inline-block">
                            <option value="all">Show All</option>
                            <option value="Order_Received">Order Received</option>
                            <option value="Order_Canceled">Order Canceled</option>
                            <option value="Order_Cancelled">Order Cancelled</option>
                            <option value="Preparing">Preparing</option>
                            <option value="Delivering">Delivering</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                    </div>

                    <?php
                    $query_res = mysqli_query($db, "SELECT * FROM `transaction` WHERE u_id = '" . $_SESSION['user_id'] . "' ORDER BY order_date DESC");
                    if (mysqli_num_rows($query_res) > 0) {
                        while ($row = mysqli_fetch_assoc($query_res)) {
                            $id = $row['id'];
                            $total_price = '₱' . number_format($row['total_price'], 2);
                            $stall = htmlspecialchars($row['stall']);
                            $status = $row['status'];
                            $rider_id = $row['rider_id'];
                            $rs_id = $row['rs_id'];
                            $order_date = $row['order_date'];
                            // Status Pill
                            $badgeClass = 'bg-warning text-dark';
                            $statusText = ucwords(str_replace('_', ' ', strtolower($status)));
                            $progressValue = 30;
                            $progressColor = 'bg-warning';

                            if ($status === 'Order_Received') {
                                $badgeClass = 'bg-success';
                                $statusText = 'Order Received';
                                $progressValue = 100;
                                $progressColor = 'bg-success';
                            } elseif ($status === 'Order_Canceled' || $status === 'Order_Cancelled') {
                                $badgeClass = 'bg-danger';
                                $statusText = 'Order Cancelled';
                                $progressValue = 0;
                                $progressColor = 'bg-danger';
                            } elseif ($status === 'Delivering') {
                                $progressValue = 70;
                                $progressColor = 'bg-primary';
                            } elseif ($status === 'Delivered') {
                                $progressValue = 90;
                                $progressColor = 'bg-primary';
                            } elseif ($status === 'Preparing') {
                                $progressValue = 50;
                                $progressColor = 'bg-warning';
                            }
                    ?>

                            <div class="order-card p-4 mb-4 order-item" data-status="<?php echo $status; ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-8 text-start">
                                        <h5 class="fw-bold"><?php echo $stall; ?></h5>
                                        <p class="mb-2">Order ID: <span class="text-muted"><?php echo $id; ?></span></p>
                                        <p class="mb-2">Order Date: <span class="text-muted"><?php echo date("F j, Y - g:i A", strtotime($order_date)); ?></span></p>
                                        <p class="mb-2">Total: <span class="fw-bold"><?php echo $total_price; ?></span></p>
                                        <span class="badge rounded-pill <?php echo $badgeClass; ?>">
                                            <?php echo $statusText; ?>
                                        </span>

                                        <!-- Progress Bar -->
                                        <div class="progress mt-3" style="height: 8px;">
                                            <div class="progress-bar <?php echo $progressColor; ?>" role="progressbar"
                                                style="width: <?php echo $progressValue; ?>%;"
                                                aria-valuenow="<?php echo $progressValue; ?>" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-end d-flex flex-column gap-2">
                                        <?php if ($status !== 'order_delivered' && $status !== 'Order_Canceled' && $status !== 'Order_Cancelled') : ?>
                                            <?php if (!empty($rider_id) && $rider_id != 0) : ?>
                                                <a href="update_order_status.php?order_id=<?php echo $id; ?>"
                                                    onclick="return confirm('Confirm you have received your order?');"
                                                    class="btn btn-success btn-sm">Mark Received</a>
                                            <?php else : ?>
                                                <button class="btn btn-secondary btn-sm" disabled>Awaiting Rider</button>
                                            <?php endif; ?>

                                            <a href="cancel_order.php?order_id=<?php echo $id; ?>"
                                                onclick="return confirm('Are you sure you want to cancel this order?');"
                                                class="btn btn-danger btn-sm">Cancel Order</a>

                                        <?php elseif ($status === 'order_delivered') : ?>
                                           <a href="rate_rider.php?rider_id=<?= $rider_id ?>&transaction_id=<?= $id ?>" 
                                            class="btn btn-outline-success btn-sm">Rate Rider</a>

                                            <a href="rate_stall.php?restaurant_id=<?php echo $rs_id; ?>"
                                                class="btn btn-outline-primary btn-sm">Rate Stall</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>


                    <?php
                        }
                    } else {
                        echo '<div class="text-center text-muted py-5">You have no orders placed yet.</div>';
                    }
                    ?>

                </div>
            </div>
        </section>
    </div>
    <?php include 'layouts/sweetalert.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter Function
        document.getElementById('statusFilter').addEventListener('change', function() {
            var selectedStatus = this.value.toLowerCase();
            var orders = document.querySelectorAll('.order-item');

            orders.forEach(function(order) {
                var orderStatus = order.getAttribute('data-status').toLowerCase();

                if (selectedStatus === 'all' || orderStatus.includes(selectedStatus)) {
                    order.style.display = 'block';
                } else {
                    order.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>