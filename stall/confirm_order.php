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
$sql = "SELECT t.id, t.total_price, t.status, t.order_date, t.rs_id, 
                 r.title AS restaurant_name, 
                 u.f_name AS user_name 
          FROM transaction t
          LEFT JOIN restaurant r ON t.rs_id = r.rs_id
          LEFT JOIN users u ON t.u_id = u.u_id
          WHERE t.status = 'order_confirmation' 
          LIMIT $limit OFFSET $offset";
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Stall Owner</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome (for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- jQuery and Bootstrap JS -->
  

</head>

<body class="fix-header fix-sidebar">

<div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <span><img src="../admin/images/icon.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0"></ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="../admin/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                       
                        <li class="nav-label">Log</li>
                        <li><a href="Confirm_order.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Confirm order</span></a></li>
                        <li><a href="index.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                        <li><a href="cancel_order.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Cancel order</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>

<div class="page-wrapper">
    
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">All Orders</h4>
                            </div>
                            <div class="table-responsive m-t-40">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead class="thead-dark">
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
                                                echo '<tr>
                                                            <td>' . $rows['username'] . '</td>
                                                            <td>₱' . number_format($rows['total_price'], 2) . '</td>
                                                            <td>' . $rows['stall_id'] . '</td>
                                                            <td class="status-cell">' . $rows['status'] . '</td>
                                                            <td>' . date("F j, Y, g:i a", strtotime($rows['order_date'])) . '</td>
                                                            <td>
                                                                <button class="btn btn-info btn-sm view-details-btn" data-trans-id="' . $rows['id'] . '"><i class="fa fa-eye"></i> View Details</button>
                                                                <form action="update_order_status.php" method="POST" style="display: inline-block;">
                                                                    <input type="hidden" name="trans_id" value="' . $rows['id'] . '">
                                                                    <select name="status" class="form-control" style="width: auto; display: inline-block;">
                                                                        <option value="place_order" ' . ($rows['status'] == 'place_order' ? 'selected' : '') . '>Place Order</option>
                                                                        <option value="order_confirmation" ' . ($rows['status'] == 'order_confirmation' ? 'selected' : '') . '>Order Confirmation</option>
                                                          
                                                                        <option value="in_process" ' . ($rows['status'] == 'in_process' ? 'selected' : '') . '>In Process</option>
                                                                        <option value="cancelled" ' . ($rows['status'] == 'cancelled' ? 'selected' : '') . '>Cancelled</option>
                                                                    </select>
                                                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button>
                                                                </form>
                                                                <a href="delete_orders.php?trans_del=' . $rows['id'] . '" onclick="return confirm(\'Are you sure?\');" class="btn btn-danger btn-flat btn-xs m-b-10">
                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                </a>
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
<script>$(document).ready(function () {
    $(".view-details-btn").click(function () {
        var transId = $(this).data('trans-id'); // Get the trans_id
        $.ajax({
            url: "get_user_details.php", // PHP file to fetch user details
            type: "GET",
            data: { trans_id: transId },
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
                            <!-- Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item"><a class="page-link" href="?page=1">First</a></li>
                                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php } ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>">Last</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">© 2024-DMMMSU-NLUC-BSIS-STUDENT</footer>
    </div>
        <footer class="footer">© 2024-DMMMSU-NLUC-BSIS-STUDENT</footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

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

</body>

</html>