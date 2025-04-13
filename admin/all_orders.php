<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;

?>


<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rider Details</li>
                    </ol>
                </nav>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Orders</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <!-- <th>Dish </th> removed for the meantime adding it later-->
                                            <th>Total Price</th>
                                            <th>Stall</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $index->getOrders();
                                        while ($row = mysqli_fetch_array($result)) {
                                            $status = $row['status'] ?? '';
                                            $orderDate = !empty($row['order_date']) ? date("F j, Y g:i A", strtotime($row['order_date'])) : 'N/A';

                                            // Status badge logic
                                            switch ($status) {
                                                case 'order_confirmation':
                                                    $statusText = 'Confirmed';
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'Order_Canceled':
                                                case 'Order_Cancelled': // cover both spellings if used
                                                    $statusText = 'Cancelled';
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                case 'Order_Received':
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
                                                default:
                                                    $statusText = ucfirst(str_replace('_', ' ', $status));
                                                    $badgeClass = 'bg-secondary';
                                                    break;
                                            }

                                            echo '<tr>';
                                            $fName = !empty($row['f_name']) ? htmlspecialchars($row['f_name']) : 'No Data';
                                            $lName = !empty($row['l_name']) ? htmlspecialchars($row['l_name']) : '';

                                            echo '<td>' . $fName . ' ' . $lName . '</td>';

                                            echo '<td>₱ ' . number_format((float) ($row['total_price'] ?? 0), 2) . '</td>';

                                                 echo '<td>' . htmlspecialchars($row['title'] ?? '') . '</td>';
                                            echo '<td><span class="badge ' . $badgeClass . '">' . $statusText . '</span></td>';
                                            echo '<td>' . $orderDate . '</td>';
                                            echo '<td><a href="view_order.php?id=' . urlencode($row['transacId']) . '" class="btn btn-sm btn-success">View Details</a></td>';

                                            echo '</tr>';
                                        }
                                        ?>

                                    </tbody>

                                </table>
                            </div>
                            <!-- Modal for User Details -->
                            <div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog"
                                aria-labelledby="userDetailsLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="userDetailsLabel">User Information</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="userDetailsContent">
                                            <!-- User information will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    $(".view-details-btn").click(function () {
                                        var transId = $(this).data('trans-id'); // Get the trans_id
                                        $.ajax({
                                            url: "get_user_details.php", // PHP file to fetch user details
                                            type: "GET",
                                            data: {
                                                trans_id: transId
                                            },
                                            success: function (response) {
                                                $('#userDetailsContent').html(response); // Display user details in modal
                                                $('#userDetailsModal').modal('show'); // Show the modal
                                            },
                                            error: function () {
                                                alert("Error fetching user details.");
                                            }
                                        });
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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

    <?php include 'layouts/footer.php' ?>