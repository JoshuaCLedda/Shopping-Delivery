<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include "../admin/Main.php";
$index = new Index;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../login.php");
    exit();
}


$transacId = $_GET['order_upd'];

// View Orders
$result = $index->viewOrderDetails($transacId);
while ($row = mysqli_fetch_object($result)) {
    $transacId = $row->transacId ?? 'N/A';
    $first_name = $row->f_name ?? 'No Data';
    $address = $row->address ?? 'No Available Address';
    $last_name = $row->l_name ?? '';
    $restaurant = $row->Restaurant ?? '';
    $total_price = $row->total_price ?? '₱0.00';
    $order_status = $row->status ?? 'Unknown';
    $order_date = !empty($row->order_date) ? date("F j, Y, g:i A", strtotime($row->order_date)) : 'No Date';
    $dishesOrder = $row->dishesOrder ?? 'Unknown';
    $dishesOrderFormatted = implode("\n", array_map('trim', explode(',', $dishesOrder)));
    $payMethod = $row->payMethod ?? 'Unknown';
    $total_quantity = $row->total_quantity ?? 'Unknown';



    // Optional switch for human-readable status
    switch ($order_status) {
        case 'order_confirmation':
            $statusText = 'Confirmed';
            break;
        case 'Order_Canceled':
            $statusText = 'Canceled';
            break;
        case 'Order_Received':
            $statusText = 'Received';
            break;
        case 'in_process':
            $statusText = 'In Process';
            break;
        default:
            $statusText = 'Unknown';
    }
}

?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/rider/sidebar.php' ?>
<?php include '../layouts/rider/navbar.php' ?>
<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="index.php" class="btn btn-primary">Back
            </a>
        </div>

        <?php include '../layouts/sweetalert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Order Details
                        </h5>
                    </div>


                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="../process_order.php" method="POST">
                                <div class="row">
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($transacId) ?>">

                                    <!-- Order Details -->
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="orderId">Order ID</label>
                                        <input type="text" class="form-control" id="orderId"
                                            value="<?= htmlspecialchars($transacId) ?>" disabled>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="customerName">Customer Name</label>
                                        <input type="text" class="form-control" id="customerName"
                                            value="<?= htmlspecialchars($first_name . ' ' . $last_name) ?>" disabled>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="stallName">Stall</label>
                                        <input type="text" class="form-control" id="stallName"
                                            value="<?= htmlspecialchars($restaurant) ?>" disabled>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="orderDate">Order Date</label>
                                        <input type="text" class="form-control" id="orderDate"
                                            value="<?= htmlspecialchars($order_date) ?>" disabled>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="orderTotal">Total Price</label>
                                        <input type="text" class="form-control" id="orderTotal"
                                            value="₱ <?= htmlspecialchars(number_format((float) $total_price, 2)) ?>"
                                            disabled>
                                    </div>


                                    <div class="form-group col-md-6 mb-3">
                                        <label for="orderStatus">Order Status</label>
                                        <select class="form-control" id="orderStatus" name="status" disabled>
                                            <option value="place_order" <?= $order_status == 'place_order' ? 'selected' : '' ?>>Placed</option>
                                            <option value="order_confirmation" <?= $order_status == 'order_confirmation' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="in_process" <?= $order_status == 'in_process' ? 'selected' : '' ?>>In Process</option>
                                            <option value="order_received" <?= $order_status == 'order_received' ? 'selected' : '' ?>>Received</option>
                                            <option value="order_canceled" <?= $order_status == 'order_canceled' ? 'selected' : '' ?>>Canceled</option>
                                            <option value="order_delivered" <?= $order_status == 'order_delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="paymentMethod">Payment Method</label>
                                        <select class="form-control" id="paymentMethod" name="payment_method" disabled>
                                            <option value="COD" <?= $payMethod == 'COD' ? 'selected' : '' ?>>Cash on
                                                Delivery</option>
                                            <option value="GCASH" <?= $payMethod == 'GCASH' ? 'selected' : '' ?>>Gcash
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <label for="deliveryAddress">Delivery Address</label>
                                        <textarea class="form-control" id="deliveryAddress" rows="3"
                                            disabled><?= htmlspecialchars($address) ?></textarea>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="col-12">
                                        <label class="form-label mb-3">Order Items</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Dish Name</th>
                                                        <th>Quantity</th>
                                                        <th>Total Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $result = $index->viewOrderItems($transacId);
                                                    while ($row = mysqli_fetch_array($result)): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['dishName'] ?? 'No Data') ?></td>
                                                            <td><?= htmlspecialchars($row['orderQuantity'] ?? '0') ?></td>
                                                            <td>₱
                                                                <?= htmlspecialchars(number_format((float) ($row['orderTotalPrice'] ?? 0), 2)) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>



                </div>
            </div>
        </div>

    </div>
</div>