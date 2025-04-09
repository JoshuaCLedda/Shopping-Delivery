<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {
?>
    <?php include 'layouts/header.php' ?>
    <?php include 'layouts/navbar.php' ?>



    <div class="page-wrapper">
        <section class="restaurants-page">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="bg-gray">
                            <div class="row">
                                <table class="table table-bordered table-hover">
                                    <thead style="background: #404040; color:white;">
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

                                        ?>
                                                <tr>
                                                    <td data-column="Item"><?php echo $date; ?></td>
                                                    <td data-column="Total Price"><?php echo $total_price; ?></td>
                                                    <td data-column="Stall"><?php echo $stall; ?></td>
                                                    <td data-column="Status"><?php echo $status; ?></td>
                                                    <td data-column="Action">
                                                        <?php if ($status !== 'Order_Received' && $status !== 'Order_Canceled') { ?>
                                                            <a href="update_order_status.php?order_id=<?php echo $id; ?>"
                                                                onclick="return confirm('Are you sure you have received your order?');"
                                                                class="btn btn-success btn-flat btn-xs m-b-10">
                                                                Order Received
                                                            </a>
                                                            <a href="cancel_order.php?order_id=<?php echo $id; ?>"
                                                                onclick="return confirm('Are you sure you want to cancel this order?');"
                                                                class="btn btn-danger btn-flat btn-xs m-b-10">
                                                                Cancel Order
                                                            </a>
                                                        <?php } else if ($status === 'Order_Received') { ?>

                                                            <a href="rate_rider.php?rider_id=<?php echo $rider_id; ?>" class="badge badge-success custom-badge">Rate Rider
                                                            <?php echo $rider_id; ?>

                                                            </a>


                                                            <a href="rate_stall.php" class="badge badge-success custom-badge">Rate Stall</a>
                                                        <?php } else if ($status === 'Order_Canceled') { ?>
                                                            <span class="badge badge-danger">Order Canceled</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5">
                                                    <center>You have no orders placed yet.</center>
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

        <footer class="footer">
            <div class="row bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>Payment Options</h5>
                            <ul>
                                <li><a href="#"> <img src="images/paypal.png" alt="Paypal"> </a></li>
                                <li><a href="#"> <img src="images/gcash.png" alt="GCash"> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script src="js/jquery.min.js"></script>
        <script src="js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/animsition.min.js"></script>
    </div>
    </body>

    </html>
<?php } ?>