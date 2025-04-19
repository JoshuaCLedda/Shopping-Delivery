<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include "../admin/Main.php";
$index = new Index;


$transacId = $_GET['order_upd'];

// View Orders
$result = $index->viewOrderDetails($transacId);
while ($row = mysqli_fetch_object($result)) {
    $transacId = $row->transacId ?? 'N/A';
    $first_name = $row->f_name ?? 'No Data';
    $last_name = $row->l_name ?? '';
    $total_price = $row->total_price ?? '₱0.00';
    $restaurant = $row->title ?? 'No Data';
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
            <a href="index.php" class="btn btn-primary">Back</a>
        </div>

        <?php include '../layouts/alert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Delivered Order Details
                        </h5>
                    </div>



                    <div class="widget card-body shadow-sm">
                        <div class="widget-body">
                            <form action="../process_order.php" method="POST">
                                <div class="row">

                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($transacId) ?>">

                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="orderId">Order ID</label>
                                        <input class="form-control" type="text" id="orderId"
                                            value="<?= htmlspecialchars($transacId) ?>" disabled>
                                    </div>

                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="customerName">Customer Name</label>
                                        <input class="form-control" type="text" id="customerName"
                                            value="<?= htmlspecialchars($first_name . ' ' . $last_name) ?>" disabled>
                                    </div>



                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="stallName">Stall</label>
                                        <input class="form-control" type="text" id="stallName"
                                            value="<?= htmlspecialchars($restaurant) ?>" disabled>
                                    </div>

                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="orderDate">Order Date</label>
                                        <input class="form-control" type="text" id="orderDate"
                                            value="<?= htmlspecialchars($order_date) ?>" disabled>
                                    </div>
                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="orderTotal">Total Price</label>
                                        <input class="form-control" type="text" id="orderTotal"
                                            value="₱ <?= htmlspecialchars(number_format((float) $total_price, 2)) ?>"
                                            disabled>
                                    </div>


                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="orderTotal">Total Quantity</label>
                                        <input class="form-control" type="text" id="orderTotal"
                                            value="<?= htmlspecialchars($total_quantity) ?>" disabled>
                                    </div>

                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="orderStatus">Order Status</label>
                                        <select disabled class="form-control" name="status" id="orderStatus">
                                            <option value="place_order" <?= $order_status == 'place_order' ? 'selected' : '' ?>>Placed</option>
                                            <option value="order_confirmation" <?= $order_status == 'order_confirmation' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="in_process" <?= $order_status == 'in_process' ? 'selected' : '' ?>>In Process</option>
                                            <option value="order_received" <?= $order_status == 'order_received' ? 'selected' : '' ?>>Received</option>
                                            <option value="order_canceled" <?= $order_status == 'order_canceled' ? 'selected' : '' ?>>Canceled</option>
                                            <option value="order_delivered" <?= $order_status == 'order_delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>
                                    </div>



                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="orderStatus">Payment Method</label>
                                        <select disabled class="form-control" name="status" id="orderStatus">
                                            <option value="COD" <?= $payMethod == 'CODE' ? 'selected' : '' ?>>Cash on
                                                Delivery</option>
                                            <option value="GCASH" <?= $payMethod == 'GCASH' ? 'selected' : '' ?>>Gcash
                                            </option>
                                        </select>
                                    </div>




                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="deliveryAddress">Delivery Address</label>
                                        <textarea class="form-control" id="deliveryAddress" name="delivery_address"
                                            rows="3"
                                            disabled><?= htmlspecialchars($row->address ?? 'No Address') ?></textarea>
                                    </div>

                                    <div class="form-group col-sm-6 mb-3">
                                        <label for="dishesOrder">Dishes Ordered</label>
                                        <textarea disabled class="form-control" id="dishesOrder" rows="4"
                                            readonly><?= $dishesOrderFormatted ?></textarea>

                                    </div>




                                </div>
                            </form>
                        </div>
                    </div>






                </div>
            </div>



            <?php
            $overall = $index->getDeliveredRating($transacId);
            ?>

            <div class="col-12 mb-4">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Delivery Rating
                        </h5>
                    </div>


                    <div class="card shadow-sm border-0 rounded-4 bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class='bx bx-bar-chart-alt-2'></i> Overall Rating</h5>

                            <div class="mb-2">
                                <?php if (!empty($overall) && isset($overall['avg_rating']) && $overall['total'] > 0): ?>
                                    <?php
                                    $avg = round($overall['avg_rating'], 1);
                                    $whole = floor($avg);
                                    $has_half = ($avg - $whole) >= 0.5;

                                    // Star color logic
                                    $starClass = ($avg <= 1) ? 'text-danger' : 'text-warning';

                                    // Stars display
                                    for ($i = 1; $i <= 5; $i++):
                                        if ($i <= $whole):
                                            echo "<i class='bx bxs-star $starClass'></i>";
                                        elseif ($has_half && $i == $whole + 1):
                                            echo "<i class='bx bxs-star-half $starClass'></i>";
                                        else:
                                            echo "<i class='bx bx-star text-muted'></i>";
                                        endif;
                                    endfor;

                                    // Feedback message
                                    if ($avg >= 4.5) {
                                        $feedback = "Outstanding performance! Keep exceeding expectations.";
                                    } elseif ($avg >= 4) {
                                        $feedback = "Great job! You're on the right track, keep it up!";
                                    } elseif ($avg >= 3) {
                                        $feedback = "Satisfactory work, but there's room for improvement.";
                                    } elseif ($avg >= 2) {
                                        $feedback = "Effort is needed to meet expectations. Focus on improving key areas.";
                                    } else {
                                        $feedback = "Significant improvement needed. A more focused approach is required.";
                                    }
                                    ?>

                                    <p class="mt-2 text-muted"><?= $feedback ?></p>
                                    <p class="mb-1"><strong>Average:</strong> <?= $avg ?>/5</p>
                                    <p class="mb-0"><strong>Total Reviews:</strong> <?= $overall['total'] ?></p>
                                <?php else: ?>
                                    <div class="card border-0 bg-danger shadow-sm rounded-4 p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class='text-white bx bx-info-circle fs-4 text-primary me-2'></i>
                                            <div>
                                                <p class="mb-0 text-white">This rider hasn't received any ratings yet.</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>





                            </div>
                        </div>
                    </div>








                    <div class="row">
                        <!-- starts here -->

                        <?php
                        $result = $index->getDeliveredOrderRating($transacId);

                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card shadow-sm rounded-4">
                                        <div class="card-body">
                                            <!-- Star Rating -->
                                            <div class="mb-2">
                                                <?php
                                                $rating = intval($row['rating']);
                                                for ($i = 1; $i <= 5; $i++):
                                                    if ($i <= $rating): ?>
                                                        <i class='bx bxs-star text-warning'></i>
                                                    <?php else: ?>
                                                        <i class='bx bx-star text-muted'></i>
                                                    <?php endif;
                                                endfor;
                                                ?>
                                            </div>

                                            <!-- Comment -->
                                            <p class="mb-2 text-muted fst-italic">"<?= htmlspecialchars($row['complaint']) ?>"
                                            </p>

                                            <!-- Rater and Rider Info -->
                                            <p class="mb-1"><strong>Rider:</strong>
                                                <?= htmlspecialchars($row['rider_name']) ?></p>

                                            <p class="mb-1"><strong>Customer:</strong>
                                                <?= htmlspecialchars($row['rider_name']) ?>
                                            </p>

                                            <!-- Date -->
                                            <p class="text-end text-muted small mb-0">
                                                <i class='bx bx-calendar'></i>
                                                <?= date('F j, Y', strtotime($row['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        else:
                            ?>

                            <?php
                        endif;
                        ?>





                    </div>



























                </div>

            </div>
        </div>