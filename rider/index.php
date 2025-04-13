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
    $transaction_id    = $_POST['transaction_id'];


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
                            <h5 class="mb-0 text-white">Recent Orders</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover"
                                    id="datatable">
                                    <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Total Price</th>
                                                <th>Stall</th>
                                                <th>Status</th>
                                                <th>Order Date</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = $index->getInProcessTransac();
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo '<tr>';
                                                echo '<td>' . $row['u_id'] . '</td>';
                                                echo '<td>' . $row['total_price'] . '</td>';
                                                echo '<td>' . $row['stall_id'] . '</td>';
                                                echo '<td>' . $row['status'] . '</td>';
                                                echo '<td>' . $row['order_date'] . '</td>';

                                                // Use session user_id
                                                $UserId = $_SESSION['user_id'];

                                                echo '<td>';
                                                echo '<form action="" method="POST">';
                                                echo '<input type="hidden" name="rider_id" value="' . $UserId . '">';
                                                echo '<input type="hidden" name="transaction_id" value="' . $row['transacID'] . '">';

                                                echo '<button type="submit" name="submit" class="btn btn-sm btn-success">Accept</button>';

                                                echo '</form>';
                                                echo '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                </d>
                <footer class="footer">© 2024-DMMMSU-NLUC-BSIS-STUDENT</footer>
            </div>



            <!-- Modal for Order Details -->
            <script>
                $(document).ready(function() {
                    $(".view-details-btn").click(function() {
                        var transId = $(this).data('trans-id');
                        $.ajax({
                            url: "get_order_details.php",
                            type: "GET",
                            data: {
                                trans_id: transId
                            },
                            success: function(response) {
                                $('#orderDetailsContent').html(response);
                                $('#orderDetailsModal').modal('show');
                            },
                            error: function() {
                                alert("Error fetching order details.");
                            }
                        });
                    });
                });
            </script>
            
<?php include '../admin/layouts/footer.php' ?>
