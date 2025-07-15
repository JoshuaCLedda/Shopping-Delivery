<?php
session_start();
error_reporting(E_ALL);
include "../admin/Main.php";
$index = new Index;


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../login.php");
    exit();
}


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Use $index->con for database connection
    $stmt = $index->con->prepare("SELECT restaurant_id, f_name, l_name, username, email, phone, address, role, status FROM users WHERE u_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
            header("Location: ../login.php"); // Correct redirect
            exit();
        }
    } else {
        // Handle prepare error
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Database error.'];
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}

// for tables
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly'; // Default to monthly
$conditions = ''; // Initialize conditions for SQL queries

// Apply filter conditions
switch ($filter) {
    case 'weekly':
        $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $end = date('Y-m-d 23:59:59', strtotime('sunday this week'));
        $conditions .= " AND order_date BETWEEN '$start' AND '$end'";
        break;
    case 'quarterly':
        $month = date('n');
        $quarterStartMonth = floor(($month - 1) / 3) * 3 + 1;
        $start = date('Y') . '-' . str_pad($quarterStartMonth, 2, '0', STR_PAD_LEFT) . '-01 00:00:00';
        $end = date('Y-m-t 23:59:59');
        $conditions .= " AND order_date BETWEEN '$start' AND '$end'";
        break;
    case 'monthly':
    default:
        $start = date('Y-m-01 00:00:00'); // Start of this month
        $end = date('Y-m-t 23:59:59'); // End of this month
        $conditions .= " AND order_date BETWEEN '$start' AND '$end'";
        break;
}

?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>
<?php include '../layouts/sweetalert.php' ?>


<div id="main">
    <div class="main-container">

    <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Stall Owner</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="col-lg-12">
            <div class="mb-4">
                <select id="filter" onchange="changeFilter()" class="form-select">
                    <option value="monthly" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'monthly')
                                                echo 'selected'; ?>>Monthly</option>
                    <option value="quarterly" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'quarterly')
                                                    echo 'selected'; ?>>Quarterly</option>
                </select>
            </div>
            <div class="row g-4">

                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-loader-alt text-primary display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Processing</h6>
                                        <small class="text-muted">Orders In Process


                                        </small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-primary mb-0">
                                    <?php
                                    $sql = "SELECT * FROM transaction 
                                  WHERE status = 'in_process' 
                                  AND rs_id = '" . $user['restaurant_id'] . "' 
                                  $conditions";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);

                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivered Orders -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-check-circle text-success display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Delivered</h6>
                                        <small class="text-muted">Completed Orders</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-success mb-0">
                                    <?php
                                    $sql = "SELECT * FROM transaction
                                 WHERE status = 'order_delivered' 
                                 $conditions";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);

                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-x-circle text-danger display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Cancelled</h6>
                                        <small class="text-muted">Rejected Orders</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-danger mb-0">
                                    <?php
                                    $sql = "SELECT * FROM transaction 
                                 WHERE status = 'order_cancelled' 
                                 $conditions";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);

                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dishes -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-restaurant text-success display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Dishes</h6>
                                        <small class="text-muted">Total Menu Items</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-success mb-0">
<?php
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM dishes 
        WHERE rs_id = (
            SELECT restaurant_id 
            FROM users 
            WHERE u_id = '$user_id'
        )";

$result = mysqli_query($index->con, $sql);
echo mysqli_num_rows($result);
?>

                                </h1>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="col-xx-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bxs-star text-warning display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Restaurant Rating</h6>
                                        <small class="text-muted">Average from Customer Reviews</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-warning mb-0">
                                    <?php
                          
$sql = "SELECT AVG(rating) AS avg_rating 
        FROM restaurant_ratings 
        WHERE restaurant_id = (
            SELECT restaurant_id 
            FROM users 
            WHERE u_id = '$user_id'
        )";

$query = mysqli_query($index->con, $sql);
$data = mysqli_fetch_assoc($query);
$avgRating = $data['avg_rating'] ? round($data['avg_rating'], 1) : 0;
echo $avgRating . " ★";
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>






                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-wallet text-success display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Total Earnings This Month</h6>
                                        <small class="text-muted">Income from completed orders</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-success mb-0">
                                    <?php
                                    $month = date('m'); // Get the current month as a number (01 to 12)
                                    $year = date('Y'); // Get the current year

                                    $query = mysqli_query($index->con, "
        SELECT SUM(transaction.total_price) AS total_earning 
        FROM transaction 
        WHERE transaction.rs_id = '{$user['restaurant_id']}'
        AND transaction.status = 'order_delivered' 
        AND MONTH(transaction.order_date) = '$month'
        AND YEAR(transaction.order_date) = '$year'
    ");

                                    // user_id
                                    $data = mysqli_fetch_assoc($query);
                                    $totalEarnings = $data['total_earning'] ? number_format($data['total_earning'], 2) : "0.00";
                                    echo "₱" . $totalEarnings;
                                    ?>
                                </h1>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-wallet text-primary display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Total Earnings This Year
                                        </h6>
                                        <small class="text-muted">Income from completed orders</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-primary mb-0">
                                    <?php
                                    $year = date('Y');
                                    $query = mysqli_query($index->con, "
                                    SELECT SUM(transaction.total_price) AS total_earning 
                                    FROM transaction 
                                    WHERE transaction.rs_id = '" . $user['restaurant_id'] . "'
                                    AND transaction.status = 'order_delivered' 
                                    AND YEAR(transaction.order_date) = '$year'
                                ");

                                    // user_id
                                    $data = mysqli_fetch_assoc($query);
                                    $totalEarnings = $data['total_earning'] ? number_format($data['total_earning'], 2) : "0.00";
                                    echo "₱" . $totalEarnings;
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Earnings</h5>

                            <canvas id="earningsChart"></canvas>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold mb-4">Recent Orders</h5>
                            <div class="table">

                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th>Customer Name

                                            </th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rs_id = $user['restaurant_id'];
                                        $sql = "SELECT 
                                        transaction.id, 
                                        transaction.total_price, 
                                        transaction.status, 
                                        transaction.order_date, 
                                        transaction.rs_id, 
                                        restaurant.title AS restaurant_name, 
                                        CONCAT(users.f_name, ' ', users.l_name) AS fullname
                                    FROM transaction
                                    LEFT JOIN restaurant ON transaction.rs_id = restaurant.rs_id
                                    LEFT JOIN users ON transaction.u_id = users.u_id
                                    WHERE transaction.rs_id = $rs_id
                                      AND transaction.order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                    ORDER BY transaction.order_date DESC
                                    LIMIT 5";


                                        $query = mysqli_query($index->con, $sql);

                                        if (!$query) {
                                            die("Error fetching data: " . mysqli_error($index->con));
                                        }

                                        if (!mysqli_num_rows($query) > 0): ?>
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
                                                    case 'order_delivered':
                                                        $statusText = 'Delivered';
                                                        $badgeClass = 'bg-success text-dark';
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
                                                    <td><?= isset($rows['fullname']) ? htmlspecialchars($rows['fullname']) : 'No Data' ?>
                                                    </td>
                                                    <?php $rowStatus = strtolower($rows['status']); ?>
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
                                                                <option value="place_order" <?= $rowStatus == 'place_order' ? 'selected' : '' ?>>Placed</option>
                                                                <option value="order_confirmation" <?= $rowStatus == 'order_confirmation' ? 'selected' : '' ?>>Confirmed</option>
                                                                <option value="in_process" <?= $rowStatus == 'in_process' ? 'selected' : '' ?>>In Process</option>
                                                                <option value="order_cancelled" <?= in_array($rowStatus, ['order_canceled', 'order_cancelled', 'cancelled']) ? 'selected' : '' ?>>Cancelled</option>
                                                                <option value="order_received" <?= $rowStatus == 'order_received' ? 'selected' : '' ?>>Received</option>
                                                                <option value="order_delivered" <?= $rowStatus == 'order_delivered' ? 'selected' : '' ?>>Delivered</option>
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







    <?php

    $stall_id = $_SESSION['user_id'];

    // Get monthly earnings (₱) for the entire year
    $query = "SELECT 
    MONTH(order_date) AS month_number,
    MONTHNAME(order_date) AS month_name,
    SUM(total_price) AS total_earnings
    FROM transaction
    WHERE status = 'order_delivered'
    AND YEAR(order_date) = YEAR(CURDATE())
    GROUP BY month_number
    ORDER BY month_number
    ";

    $stmt = $index->con->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $earnings = array_fill(1, 12, 0); // Initialize all months with 0 earnings

    while ($row = $result->fetch_assoc()) {
        $earnings[(int)$row['month_number']] = (float)$row['total_earnings'];
    }

    $chartData = json_encode(array_values($earnings), JSON_NUMERIC_CHECK);
    ?>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data from PHP
        let chartData = <?php echo $chartData; ?>;

        // Month labels
        const monthLabels = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Create Chart.js Line or Bar Chart
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(ctx, {
            type: 'bar', // You can change to 'line' if you prefer
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Monthly Earnings (₱)',
                    data: chartData,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Smooth curve if line chart
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Handle filter change if needed
        function changeFilter() {
            let selectedFilter = document.getElementById('filter').value;
            window.location.href = "?filter=" + selectedFilter;
        }
    </script>








</div>
<?php include '../admin/layouts/footer.php' ?>