<?php
session_start();
error_reporting(E_ALL);
include "../admin/Main.php";
$index = new Index;

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
?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>

<div id="main">
    <div class="main-container">



        <div class="col-lg-12">
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
                                    $sql = "SELECT * FROM transaction WHERE status = 'in_process' AND rs_id = '" . $user['restaurant_id'] . "'";
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
                                    $sql = "SELECT * FROM users_orders WHERE status = 'closed'";
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
                                    $sql = "SELECT * FROM users_orders WHERE status = 'rejected'";
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
                                    $sql = "SELECT * FROM dishes";
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
                                    $query = mysqli_query($index->con, "SELECT AVG(rating) AS avg_rating FROM
                                    restaurant_ratings WHERE restaurant_user_id = '$user_id'");
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
                                    $month = date('M');

                                    $query = mysqli_query($index->con, "
                                        SELECT SUM(transaction.total_price) AS total_earning 
                                        FROM transaction 
                                        WHERE transaction.rs_id = '{$user['restaurant_id']}'
                                        AND transaction.status = 'order_delivered' 
                                        AND YEAR(transaction.order_date) = '$month'
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
                                        <?php echo $user['restaurant_id']; ?>

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
                            <div class="mb-4">
                                <label for="filter" class="form-label">Select Filter:</label>
                                <select id="filter" onchange="changeFilter()" class="form-select">
                                    <option value="monthly" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'monthly')
                                                                echo 'selected'; ?>>Monthly</option>
                                    <option value="quarterly" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'quarterly')
                                                                    echo 'selected'; ?>>Quarterly</option>
                                </select>
                            </div>
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
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">ID</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Customer</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Food Item</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Status</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">1</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">Ana Reyes</h6>
                                                <span class="fw-normal">#ORD-1001</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Cheeseburger Combo</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-primary rounded-3 fw-semibold">Pending</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">2</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">Mark Villanueva</h6>
                                                <span class="fw-normal">#ORD-1002</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Spaghetti & Garlic Bread</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-secondary rounded-3 fw-semibold">On Hold</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">3</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">Maria Lopez</h6>
                                                <span class="fw-normal">#ORD-1003</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Beef Tapa with Rice</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-danger rounded-3 fw-semibold">Cancelled</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">4</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">John Santos</h6>
                                                <span class="fw-normal">#ORD-1004</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Chicken Wings & Fries</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-success rounded-3 fw-semibold">Completed</span>
                                            </td>
                                        </tr>
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