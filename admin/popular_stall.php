<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}
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
                        <li class="breadcrumb-item active" aria-current="page">Popular Stalls</li>
                    </ol>
                </nav>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Popular Stalls</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Stall</th>
                                            <th>Total Orders</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                  <tbody>
    <?php
    $query = "
        SELECT 
            restaurant.rs_id,
            restaurant.title AS stall_title,
            restaurant.status,
            restaurant.image AS stall_image,
            COUNT(DISTINCT transaction.id) AS total_orders
        FROM restaurant
        JOIN dishes ON dishes.rs_id = restaurant.rs_id
        JOIN order_items ON order_items.dishes_id = dishes.d_id
        JOIN transaction ON transaction.id = order_items.transaction_id
        GROUP BY restaurant.rs_id, restaurant.title, restaurant.status, restaurant.image
        ORDER BY total_orders DESC
    ";

    $result = mysqli_query($index->con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';

        echo '<td>';
        echo '<div class="d-flex align-items-center">';
        if (!empty($row['stall_image']) && file_exists("" . $row['stall_image'])) {
            echo '<img src="' . htmlspecialchars($row['stall_image']) . '" class="rounded me-2" width="50" height="50" style="object-fit:cover;">';
        } else {
            echo '<div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="fa fa-store text-muted"></i></div>';
        }
        echo '<span>' . htmlspecialchars($row['stall_title']) . '</span>';
        echo '</div>';
        echo '</td>';

        echo '<td><span class="badge bg-primary">' . $row['total_orders'] . '</span></td>';

        if ($row['status'] == 0) {
            echo '<td><span class="badge bg-success">Active</span></td>';
        } else {
            echo '<td><span class="badge bg-danger">Inactive</span></td>';
        }

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
            // Initialize your DataTable first
            const table = $('#datatable').DataTable();

            // Append status filter to DataTables search container
            $('#datatable_filter').append(`
            <label class="ms-3 d-inline-flex align-items-center">
                <select id="statusFilter" class="form-select form-select-sm" style="min-width: 160px;">
                    <option value="">All</option>
                    <option value="Placed">Placed</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="In Process">In Process</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Received">Received</option>
                </select>
            </label>
        `);

            // Filter logic based on visible status text (badge or plain text)
            $('#statusFilter').on('change', function() {
                const selected = $(this).val();
                const column = 3; // Column 3 = Status, where we display the badge text

                // If a status is selected, filter based on status text
                if (selected) {
                    table.column(column).search(selected, true, false).draw();
                } else {
                    // Clear filter and show all data
                    table.column(column).search('').draw();
                }
            });
        });
    </script>


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