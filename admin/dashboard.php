<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;

// Redirect if already logged in
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


        <div class="col-lg-12">


            <div class="row g-4">

                <!-- Stalls -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-home text-primary display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Stalls</h6>
                                        <small class="text-muted">Total Restaurants</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-primary mb-0">
                                    <?php
                                    $sql = "SELECT * FROM restaurant";
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

                <!-- Total Orders -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-cart text-warning display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Total Orders</h6>
                                        <small class="text-muted">All Time Orders</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-warning mb-0">
                                    <?php
                                    $sql = "SELECT * FROM users_orders";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restro Categories -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-category text-secondary display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Restro Categories</h6>
                                        <small class="text-muted">Food Categories</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-secondary mb-0">
                                    <?php
                                    $sql = "SELECT * FROM res_category";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- end row -->
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
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



                <div class="col-lg-6 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold mb-4">Recent Transactions</h5>
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

                                        <?php
                                        $result = $index->getRecentTransactions();
                                        while ($row = mysqli_fetch_array($result)) {
                                            $fullName = $row['f_name'] . ' ' . $row['l_name'];
                                            $status = $row['status'];

                                            // Custom status formatting
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
                                                <td class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0"><?= $row['transacId'] ?></h6>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-1"><?= $fullName ?></h6>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal"><?= $row['dishesOrder'] ?></p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <span
                                                        class="badge rounded-3 fw-semibold <?= $badgeClass ?>"><?= $statusText ?></span>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

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
</div>
</div>
</div>
<?php include 'layouts/footer.php' ?>