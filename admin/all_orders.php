<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Pagination setup
$limit = 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);  // Ensure page is at least 1
$offset = ($page - 1) * $limit;

// Query to fetch orders with pagination
$sql = "SELECT id, total_price, status, order_date, stall_id FROM transaction LIMIT $limit OFFSET $offset";
$query = mysqli_query($db, $sql);

if (!$query) {
    die("Error fetching data: " . mysqli_error($db));
}

// Total number of orders
$count_sql = "SELECT COUNT(*) AS total FROM transaction";
$count_query = mysqli_query($db, $count_sql);
$total_orders = mysqli_fetch_assoc($count_query)['total'];
$total_pages = ceil($total_orders / $limit);
?>

<?php include 'layouts/header.php' ?>


<body class="fix-header fix-sidebar">

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <?php include 'layouts/navbar.php' ?>


        <?php include 'layouts/sidebar.php' ?>


        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <h4>All Orders</h4>
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-bordered table-hover align-middle">
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
                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="6"><center>No Orders</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    // Get the status value
                                                    $status = $rows['status'];

                                                    // Define the status message and badge class
                                                    switch ($status) {
                                                        case 'order_confirmation':
                                                            $statusText = 'Confirmed';
                                                            $badgeClass = 'badge-success'; // Green for Confirmed
                                                            break;
                                                        case 'Order_Canceled':
                                                            $statusText = 'Cancel';
                                                            $badgeClass = 'badge-danger'; // Red for Cancel
                                                            break;
                                                        case 'Order_Received':
                                                            $statusText = 'Received';
                                                            $badgeClass = 'badge-primary'; // Blue for Received
                                                            break;
                                                        default:
                                                            $statusText = 'Unknown';
                                                            $badgeClass = 'badge-secondary'; // Gray for Unknown status
                                                            break;
                                                    }

                                                    // Output the row
                                                    echo '<tr>
                    <td>' . $rows['username'] . '</td>
                    <td>₱' . number_format($rows['total_price'], 2) . '</td>
                    <td>' . $rows['stall_id'] . '</td>
                    <td class="status-cell">
                        <span class="badge ' . $badgeClass . '">' . $statusText . '</span>
                    </td>
                    <td>' . date("F j, Y, g:i a", strtotime($rows['order_date'])) . '</td>
                    <td>
                        <button class="btn btn-info btn-sm view-details-btn" data-trans-id="' . $rows['id'] . '"><i class="fa fa-eye"></i> View Details</button>
                    </td>
                </tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                                <!-- Modal for User Details -->
                                <div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsLabel" aria-hidden="true">
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
                                    $(document).ready(function() {
                                        $(".view-details-btn").click(function() {
                                            var transId = $(this).data('trans-id'); // Get the trans_id
                                            $.ajax({
                                                url: "get_user_details.php", // PHP file to fetch user details
                                                type: "GET",
                                                data: {
                                                    trans_id: transId
                                                },
                                                success: function(response) {
                                                    $('#userDetailsContent').html(response); // Display user details in modal
                                                    $('#userDetailsModal').modal('show'); // Show the modal
                                                },
                                                error: function() {
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

        <?php include 'layouts/footer.php' ?>