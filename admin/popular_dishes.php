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
                       
                        <li class="breadcrumb-item active" aria-current="page">Popular Dishes</li>
                    </ol>
                </nav>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Popular Dishes</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Dish</th>
                                            <th>Stall</th>
                                            <th>Total Sold</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "
SELECT 
    dishes.d_id,
    dishes.title AS dish_title,
    dishes.price AS dish_price,
    dishes.img AS dish_image,
    restaurant.title AS stall_title,
    SUM(order_items.quantity) AS total_quantity_sold
FROM dishes
JOIN restaurant ON dishes.rs_id = restaurant.rs_id
JOIN order_items ON order_items.dishes_id = dishes.d_id
WHERE dishes.status = 0
GROUP BY dishes.d_id, dishes.title, dishes.price, dishes.img, restaurant.title
HAVING total_quantity_sold > 0
ORDER BY total_quantity_sold DESC


";


                                        $result = mysqli_query($index->con, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<td>';
                                            echo '<div class="d-flex align-items-center">';
                                            if (!empty($row['dish_image']) && file_exists("Res_img/" . $row['dish_image'])) {
                                                echo '<img src="Res_img/' . htmlspecialchars($row['dish_image']) . '" class="rounded me-2" width="50" height="50" style="object-fit:cover;">';
                                            } else {
                                                echo '<div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="fa fa-image text-muted"></i></div>';
                                            }
                                            echo '<span>' . htmlspecialchars($row['dish_title']) . '</span>';
                                            echo '</div>';
                                            echo '</td>';
                                            echo '<td>' . htmlspecialchars($row['stall_title']) . '</td>';
                                            echo '<td><span class="badge bg-success">' . $row['total_quantity_sold'] . '</span></td>';
                                            echo '<td>₱' . number_format($row['dish_price'], 2) . '</td>';
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