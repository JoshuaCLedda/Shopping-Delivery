<?php
error_reporting(0);
session_start();
include("../connection/connect.php");

// Query to fetch all orders (DataTables will handle pagination client-side)
$sql = "SELECT t.id, t.total_price, t.status, t.order_date, t.rs_id, 
               r.title AS restaurant_name, 
               u.f_name AS user_name 
        FROM transaction t
        LEFT JOIN restaurant r ON t.rs_id = r.rs_id
        LEFT JOIN users u ON t.u_id = u.u_id
        ORDER BY t.order_date DESC";
$query = mysqli_query($db, $sql);

if (!$query) {
    die("Error fetching data: " . mysqli_error($db));
}
?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Stall Owner</a></li>
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
                            <h5 class="mb-0 text-white">Customer Orders</h5>
                        </div>

                        <div class="card-body">
                    

                            <div class="table-responsive">
                           
                           



                                <table class="table datatable table-striped table-hover" id="datatable">
                                  
                                
                                <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!mysqli_num_rows($query) > 0): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No Orders</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php while ($rows = mysqli_fetch_assoc($query)): ?>
                                                <?php
                                                $status = $rows['status'];
                                                switch ($status) {
                                                    case 'order_confirmation':
                                                        $statusText = 'Confirmed';
                                                        $badgeClass = 'bg-success';
                                                        break;
                                                    case 'Order_Canceled':
                                                    case 'Order_Cancelled':
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
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($rows['user_name']) ?></td>
                                                    <td>₱ <?= number_format($rows['total_price'], 2) ?></td>
                                                    <td class="status-cell">
                                                        <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                                    </td>
                                                    <td><?= date("F j, Y, g:i a", strtotime($rows['order_date'])) ?></td>
                                                    <td>
                                                        <form action="update_order_status.php" method="POST"
                                                            class="d-inline update-status-form">
                                                            <input type="hidden" name="trans_id"
                                                                value="<?= htmlspecialchars($rows['id']) ?>">
                                                            <select name="status"
                                                                class="form-select form-select-sm d-inline w-auto">
                                                                <option value="place_order" <?= $rows['status'] == 'place_order' ? 'selected' : '' ?>>Placed</option>
                                                                <option value="order_confirmation"
                                                                    <?= $rows['status'] == 'order_confirmation' ? 'selected' : '' ?>>Confirmed</option>
                                                                <option value="in_process" <?= $rows['status'] == 'in_process' ? 'selected' : '' ?>>In Process</option>
                                                                <option value="cancelled" <?= $rows['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                            </select>
                                                            <noscript>
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-primary ms-2">Update</button>
                                                            </noscript>
                                                        </form>
                                                        <a href="view_order.php?id=<?= urlencode($rows['id']) ?>" 
                                                            class="btn btn-info btn-sm view-details-btn">
                                                            <i class="bx bx-show"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </tbody>

                                </table>

                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <script>
        $(document).ready(function () {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            // Status-to-badge HTML generator
            function getBadgeHTML(status) {
                let badgeClass = 'bg-secondary';
                let statusText = status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                switch (status) {
                    case 'order_confirmation':
                        badgeClass = 'bg-success';
                        statusText = 'Confirmed';
                        break;
                    case 'Order_Canceled':
                    case 'Order_Cancelled':
                        badgeClass = 'bg-danger';
                        statusText = 'Cancelled';
                        break;
                    case 'Order_Received':
                        badgeClass = 'bg-primary';
                        statusText = 'Received';
                        break;
                    case 'in_process':
                        badgeClass = 'bg-warning text-dark';
                        statusText = 'In Process';
                        break;
                    case 'place_order':
                        badgeClass = 'bg-info text-dark';
                        statusText = 'Placed';
                        break;
                }

                return `<span class="badge ${badgeClass}">${statusText}</span>`;
            }

            // Handle status change
            $(document).on('change', 'select[name="status"]', function () {
                var form = $(this).closest('form');
                var transId = form.find('input[name="trans_id"]').val();
                var newStatus = $(this).val();
                var statusCell = form.closest('tr').find('.status-cell');
                var selectElement = $(this);

                // Show loading indicator
                selectElement.prop('disabled', true);
                var loader = $('<span class="ms-2"><i class="bx bx-loader bx-spin"></i></span>');
                selectElement.after(loader);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        trans_id: transId,
                        status: newStatus
                    },
                    success: function (response) {
                        if (response.success) {
                            // Use dynamic badge instead of plain text
                            statusCell.html(getBadgeHTML(newStatus));
                            toastr.success(response.message);
                        } else {
                            selectElement.val(selectElement.data('original')); // fallback
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        selectElement.val(selectElement.data('original'));
                        toastr.error('Error updating status: ' + error);
                    },
                    complete: function () {
                        selectElement.prop('disabled', false);
                        loader.remove();
                    }
                });
            });

            // Prevent form submission (we're handling via AJAX)
            $(document).on('submit', '.update-status-form', function (e) {
                e.preventDefault();
            });
        });
    </script>

<script src="../assets/js/selectFilter.js"></script>
<?php include '../admin/layouts/footer.php' ?>