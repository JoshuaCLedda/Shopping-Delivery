<?php
session_start();
error_reporting(E_ALL);
include "../admin/Index.php";
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
        $transaction_id,
    
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Panel</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome (for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</head>

<body class="fix-header fix-sidebar">


    <div id="main-wrapper">
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

                        <li><a href="index.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>

    <div class="page-wrapper">
        
    <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header my-2">
                                <h4 class="m-b-0 text-white">All Orders</h4>
                            </div>

                            <?php
            include '../layouts/alert.php';
            ?>

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