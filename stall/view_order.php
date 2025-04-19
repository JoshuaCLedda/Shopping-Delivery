<?php
session_start();
error_reporting(E_ALL);
include "../admin/Main.php";
$index = new Index;

// View Orders
$result = $index->viewOrderDetails($_GET['id']);
while ($row = mysqli_fetch_object($result)) {
    $transacId     = $row->transacId ?? 'N/A';
    $first_name    = $row->f_name ?? 'No Data';
    $last_name     = $row->l_name ?? '';
    $total_price   = $row->total_price ?? '₱0.00';
    $restaurant    = $row->title ?? 'No Data';
    $order_status  = $row->status ?? 'Unknown';
    $order_date    = !empty($row->order_date) ? date("F j, Y, g:i A", strtotime($row->order_date)) : 'No Date';
    $dishesOrder  = $row->dishesOrder ?? 'Unknown';
    $dishesOrderFormatted = implode("\n", array_map('trim', explode(',', $dishesOrder)));
    $payMethod  = $row->payMethod ?? 'Unknown';
    $total_quantity  = $row->total_quantity ?? 'Unknown';



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
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>
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
            <a href="orders.php" class="btn btn-primary">Back</a>
        </div>



        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Order Details</h5>
                    </div>



                    <div class="widget card-body shadow-sm">
    <div class="widget-body">
        <form action="process_order.php" method="POST">
            <div class="row">

                <input type="hidden" name="order_id" value="<?= htmlspecialchars($transacId) ?>">

                <div class="form-group col-sm-6 mb-3">
                    <label for="orderId">Order ID</label>
                    <input class="form-control" type="text" id="orderId" value="<?= htmlspecialchars($transacId) ?>" disabled>
                </div>

                <div class="form-group col-sm-6 mb-3">
                    <label for="customerName">Customer Name</label>
                    <input class="form-control" type="text" id="customerName" 
                        value="<?= htmlspecialchars($first_name . ' ' . $last_name) ?>" disabled>
                </div>

               

                <div class="form-group col-sm-6 mb-3">
                    <label for="stallName">Stall</label>
                    <input class="form-control" type="text" id="stallName" value="<?= htmlspecialchars($restaurant) ?>" disabled>
                </div>

                <div class="form-group col-sm-6 mb-3">
                    <label for="orderDate">Order Date</label>
                    <input class="form-control" type="text" id="orderDate" value="<?= htmlspecialchars($order_date) ?>" disabled>
                </div>
                <div class="form-group col-sm-6 mb-3">
    <label for="orderTotal">Total Price</label>
    <input class="form-control" type="text" id="orderTotal" value="₱ <?= htmlspecialchars(number_format((float) $total_price, 2)) ?>" disabled>
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
                        <option value="Order_Received" <?= $order_status == 'Order_Received' ? 'selected' : '' ?>>Received</option>
                        <option value="Order_Canceled" <?= $order_status == 'Order_Canceled' ? 'selected' : '' ?>>Canceled</option>
                    </select>
                </div>

                <div class="form-group col-sm-6 mb-3">
                    <label for="orderStatus">Payment Method</label>
                    <select disabled class="form-control" name="status" id="orderStatus">
                        <option value="COD" <?= $payMethod == 'CODE' ? 'selected' : '' ?>>Cash on Delivery</option>
                        <option value="GCASH" <?= $payMethod == 'GCASH' ? 'selected' : '' ?>>Gcash</option>
                    </select>
                </div>




                <div class="form-group col-sm-6 mb-3">
                    <label for="deliveryAddress">Delivery Address</label>
                    <textarea class="form-control" id="deliveryAddress" name="delivery_address" rows="3" disabled><?= htmlspecialchars($row->address ?? 'No Address') ?></textarea>
                </div>

                <div class="form-group col-sm-6 mb-3">
    <label for="dishesOrder">Dishes Ordered</label>
    <textarea disabled class="form-control" id="dishesOrder" rows="4" readonly><?= $dishesOrderFormatted ?></textarea>

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


<?php include '../admin/layouts/footer.php' ?>
