<?php
session_start();
error_reporting(E_ALL);
include "../admin/Main.php";
$index = new Index;

// get the id from the rider
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['submit'])) {
    // Collect form data
    $rider_id = $_POST['rider_id'];
    $transaction_id = $_POST['transaction_id'];


    // Call the model function with image path
    $result = $index->acceptOrderRider(
        $rider_id,
        $transaction_id

    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Order Accepted Succesfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Please Try Again.'];
    }

    header("Location: index.php");
    exit();
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
                        <li class="breadcrumb-item active" aria-current="page">Riders Rating</li>
                    </ol>
                </nav>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Delivered Orders

                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Total Price</th>
                                            <th>Stall</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $result = $index->ridersDeliveredOrder($user_id);
                                        ?>

                                        <?php while ($row = mysqli_fetch_array($result)):
                                            // Format date
                                            $formattedDate = !empty($row['order_date']) ? date("F j, Y, g:i A", strtotime($row['order_date'])) : 'No Date';

                                            // Normalize status string
                                            $status = strtolower(trim($row['status']));
                                            switch ($status) {
                                                case 'order_confirmation':
                                                    $statusText = 'Confirmed';
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'order_canceled':
                                                    $statusText = 'Canceled';
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                case 'order_received':
                                                    $statusText = 'Received';
                                                    $badgeClass = 'bg-primary';
                                                    break;
                                                case 'in_process':
                                                    $statusText = 'In Process';
                                                    $badgeClass = 'bg-warning text-dark';
                                                    break;
                                                case 'place_order':
                                                    $statusText = 'Placed';
                                                    $badgeClass = 'bg-info text-dark';
                                                    break;
                                                case 'order_delivered':
                                                    $statusText = 'Delivered';
                                                    $badgeClass = 'bg-success text-white';
                                                    break;
                                                default:
                                                    $statusText = $status ?: 'Unknown';
                                                    $badgeClass = 'bg-secondary';
                                                    break;
                                            }

                                            $UserId = $_SESSION['user_id'];
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['customerName'] ?? 'No Data') ?></td>
                                                <td>₱<?= number_format($row['total_price'] ?? 0, 2) ?></td>
                                                <td><?= htmlspecialchars($row['stall_id'] ?? 'No Data') ?></td>
                                                <td><?= htmlspecialchars($row['customerPhone'] ?? 'No Data') ?></td>
                                               
                                                <td>
                                                    <span class="badge rounded-pill <?= $badgeClass ?>">
                                                        <?= $statusText ?>
                                                    </span>
                                                </td>
                                                <td><?= $formattedDate ?? 'No Date' ?></td>
                                                <td>
                                                    <form action="" method="POST" class="d-inline">
                                                        <input type="hidden" name="rider_id"
                                                            value="<?= htmlspecialchars($UserId ?? '') ?>">
                                                        <input type="hidden" name="transaction_id"
                                                            value="<?= htmlspecialchars($row['transacID'] ?? '') ?>">
                                                    </form>

                                                    <a href="view_delivered_orders.php?order_upd=<?= htmlspecialchars($row['transacID'] ?? '') ?>"
                                                        class="btn btn-sm btn-info ms-2">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </td>
                                            </tr>


                                        <?php endwhile; ?>


                                    </tbody>


                                </table>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            </d>
        </div>



        <!-- Modal for Order Details -->
        <script>
            $(document).ready(function () {
                $(".view-details-btn").click(function () {
                    var transId = $(this).data('trans-id');
                    $.ajax({
                        url: "get_order_details.php",
                        type: "GET",
                        data: {
                            trans_id: transId
                        },
                        success: function (response) {
                            $('#orderDetailsContent').html(response);
                            $('#orderDetailsModal').modal('show');
                        },
                        error: function () {
                            alert("Error fetching order details.");
                        }
                    });
                });
            });
        </script>

        <?php include '../admin/layouts/footer.php' ?>