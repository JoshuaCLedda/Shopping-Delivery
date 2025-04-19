<?php
session_start();
include("connection/connect.php");
error_reporting(0);

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
}
?>

<?php include 'layouts/header.php' ?>
<?php include 'layouts/navbar.php' ?>


<div class="page-wrapper py-4">
    <section class="container">
        <div class="row">
            <div class="col-lg-12">

            <?php include 'layouts/alert.php' ?>

                <div class="card shadow-sm rounded-4">


                    <div class="card-body p-4">
                        <h3>My Orders</h3>

                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered table-hover align-middle text-center">
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
                                                <td><?php echo $id; ?>
                                                </td>
                                                <td><?php echo $total_price; ?></td>
                                                <td><?php echo $stall; ?></td>
                                                <td>
                                                    <?php if ($status === 'Order_Received'): ?>
                                                        <span class="badge bg-success">Order Received</span>
                                                    <?php elseif ($status === 'Order_Canceled' || $status === 'Order_Cancelled'): ?>
                                                        <span class="badge bg-danger">Order Cancelled</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <?php echo ucwords(str_replace('_', ' ', strtolower($status))); ?>
                                                        </span>
                                                    <?php endif; ?>

                                                </td>
                                                <td>
                                                    <?php if ($status !== 'Order_Received' && $status !== 'Order_Canceled' && $status !== 'Order_Cancelled') { ?>

                                                        <!-- Check if rider_id is not null or not 0 -->
                                                        <?php if (!empty($rider_id) && $rider_id != 0): ?>
                                                            <a href="update_order_status.php?order_id=<?php echo $id; ?>"
                                                                onclick="return confirm('Are you sure you have received your order?');"
                                                                class="btn btn-success btn-sm mb-1">Received</a>
                                                        <?php else: ?>
                                                            <button class="btn btn-success btn-sm mb-1" disabled>Received</button>
                                                        <?php endif; ?>

                                                        <a href="cancel_order.php?order_id=<?php echo $id; ?>"
                                                            onclick="return confirm('Are you sure you want to cancel this order?');"
                                                            class="btn btn-danger btn-sm mb-1">Cancel</a>

                                                    <?php } elseif ($status === 'order_delivered') { ?>
                                                        <a href="rate_rider.php?rider_id=<?php echo $rider_id; ?>"
                                                            class="btn btn-outline-success btn-sm mb-1">Rate
                                                            Rider
                                                        <a href="rate_stall.php?rider_id=<?php echo $rs_id; ?>"
                                                            class="btn btn-outline-success btn-sm mb-1">Rate
                                                            Stall
                                                    <?php } ?>
                                                </td>

                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center text-muted py-3">You have no orders placed yet.
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "pageLength": 10,
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": 4
            }
            ]
        });
    });
</script>

<style>
    /* CARD STYLING */
    .card {
        margin: 15px;
        background-color: #fff;
        border: none;
        box-shadow: 0 0 1.25rem rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0 1.75rem rgba(0, 0, 0, 0.08);
    }

    /* CARD BODY PADDING */
    .card-body {
        padding: 2rem;
    }

    /* HEADING STYLING */
    h3.mb-4 {
        font-weight: 600;
        color: #333;
    }

    /* TABLE STYLING */
    .table {
        border-radius: 1rem;
        overflow: hidden;
    }

    .table thead th {
        background-color: #343a40 !important;
        color: #fff;
        vertical-align: middle;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        padding: 1rem;
        border: none;
    }

    .table tbody td {
        padding: 0.95rem 1rem;
        vertical-align: middle;
        font-size: 0.95rem;
        color: #444;
        border-color: #dee2e6;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }

    /* BADGE STYLING */
    .badge {
        font-size: 0.8rem;
        padding: 0.5em 0.50em;
    }

    /* BUTTONS */
    .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
        border-radius: 0.4rem;
    }

    /* ACTION COLUMN WIDTH */
    td:last-child {
        min-width: 180px;
    }

    /* RESPONSIVE TEXT CENTERING ON SMALL SCREENS */
    @media (max-width: 576px) {
        .table thead {
            font-size: 0.8rem;
        }

        .table tbody td {
            font-size: 0.85rem;
        }

        h3.mb-4 {
            font-size: 1.25rem;
        }
    }
</style>