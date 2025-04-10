<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
}
?>

<?php include 'layouts/header.php' ?>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<?php include 'layouts/navbar.php' ?>

<div class="page-wrapper py-4">
    <section class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="mb-4">My Orders</h3>
                <div class="card shadow m-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="p-4 table table-bordered table-hover align-middle text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Total Price</th>
                                        <th>Stall</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_res = mysqli_query($db, "SELECT * FROM transaction WHERE u_id = '" . $_SESSION['user_id'] . "'");

                                    if (mysqli_num_rows($query_res) > 0) {
                                        while ($row = mysqli_fetch_array($query_res)) {
                                            $id = $row['id'];
                                            $date = $row['order_date'];
                                            $total_price = '₱' . number_format($row['total_price'], 2);
                                            $stall = $row['stall'];
                                            $status = $row['status'];
                                            $rider_id = $row['rider_id'];
                                            $rs_id = $row['rs_id'];
                                    ?>
                                            <tr>
                                                <td><?php echo $date; ?></td>
                                                <td><?php echo $total_price; ?></td>
                                                <td><?php echo $stall; ?></td>
                                                <td>
                                                    <?php if ($status === 'Order_Received') : ?>
                                                        <span class="badge bg-success">Order Received</span>
                                                    <?php elseif ($status === 'Order_Canceled') : ?>
                                                        <span class="badge bg-danger">Order Canceled</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-warning text-dark"><?php echo $status; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($status !== 'Order_Received' && $status !== 'Order_Canceled') { ?>
                                                        <a href="update_order_status.php?order_id=<?php echo $id; ?>"
                                                           onclick="return confirm('Are you sure you have received your order?');"
                                                           class="btn btn-success btn-sm mb-1">Received</a>

                                                        <a href="cancel_order.php?order_id=<?php echo $id; ?>"
                                                           onclick="return confirm('Are you sure you want to cancel this order?');"
                                                           class="btn btn-danger btn-sm mb-1">Cancel</a>
                                                    <?php } elseif ($status === 'Order_Received') { ?>
                                                        <a href="rate_rider.php?rider_id=<?php echo $rider_id; ?>" class="btn btn-outline-success btn-sm mb-1">Rate Rider</a>
                                                        <a href="rate_stall.php?rider_id=<?php echo $rs_id; ?>" class="btn btn-outline-success btn-sm mb-1">Rate Stall</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center text-muted">You have no orders placed yet.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div> <!-- /.table-responsive -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- /.row -->
    </section>
</div> <!-- /.page-wrapper -->

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "pageLength": 10,
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": 4 } // Disable sorting for "Action"
            ]
        });
    });
</script>

<footer class="footer mt-5">
    <div class="row bottom-footer bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h5>Payment Options</h5>
                    <ul class="list-unstyled d-flex gap-2">
                        <li><a href="#"><img src="images/paypal.png" alt="Paypal" width="60"></a></li>
                        <li><a href="#"><img src="images/gcash.png" alt="GCash" width="60"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php include 'layouts/footer.php' ?>
