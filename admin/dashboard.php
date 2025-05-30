<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Get filters
$filter = $_GET['filter'] ?? 'monthly';
$selectedYear = $_GET['year'] ?? date('Y');
$restaurantId = $_GET['restaurant'] ?? '';

// Date range for filter
$startDate = "$selectedYear-01-01 00:00:00";
$endDate = "$selectedYear-12-31 23:59:59";

if ($filter == 'monthly') {
    $startDate = "$selectedYear-" . date('m') . "-01 00:00:00";
    $endDate = date("Y-m-t 23:59:59", strtotime($startDate));
} elseif ($filter == 'quarterly') {
    $currentMonth = date('n');
    $quarterStartMonth = floor(($currentMonth - 1) / 3) * 3 + 1;
    $quarterEndMonth = $quarterStartMonth + 2;

    $startDate = "$selectedYear-" . str_pad($quarterStartMonth, 2, '0', STR_PAD_LEFT) . "-01 00:00:00";
    $endDate = date("Y-m-t 23:59:59", strtotime("$selectedYear-$quarterEndMonth-01"));
}

// SQL WHERE conditions
$conditions = "status = 'order_delivered' AND order_date BETWEEN '$startDate' AND '$endDate'";
if (!empty($restaurantId)) {
    $conditions .= " AND rs_id = '$restaurantId'";
}
?>
<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>
<?php include 'layouts/sweetalert.php' ?>

<div id="main">
    <div class="main-container">
        <div class="row mb-3">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Select Year:</label>
                <select id="year" class="form-select" onchange="applyFilters()">
                    <?php for ($y = 2024; $y <= 2027; $y++): ?>
                        <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Select Filter:</label>
                <select id="filter" class="form-select" onchange="applyFilters()">
                    <option value="monthly" <?= $filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="quarterly" <?= $filter == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Select Stall:</label>
                <select id="restaurant" class="form-select" onchange="applyFilters()">
                    <option value="">All Stalls</option>
                    <?php
                    $result = $index->getActiveRestaurant();
                    while ($row = mysqli_fetch_array($result)) {
                        $selected = $restaurantId == $row['rs_id'] ? 'selected' : '';
                        echo "<option value=\"{$row['rs_id']}\" $selected>{$row['title']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4">
            <?php
            $restaurantWhere = !empty($restaurantId) ? " AND rs_id = '$restaurantId'" : '';
            ?>

            <!-- Total Stalls -->
            <div class="col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-home text-primary display-5 me-3'></i>
                            <div>
                                <h6 class="mb-1 fw-semibold">Stalls</h6>
                                <small class="text-muted">Total Restaurants</small>
                            </div>
                        </div>
                        <h1 class="fw-bold text-primary mb-0">
                            <?php
                            $sql = "SELECT * FROM restaurant WHERE date BETWEEN '$startDate' AND '$endDate'";
                            echo mysqli_num_rows(mysqli_query($index->con, $sql));
                            ?>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Restaurant Categories -->
            <div class="col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-category text-secondary display-5 me-3'></i>
                            <div>
                                <h6 class="mb-1 fw-semibold">Restro Categories</h6>
                                <small class="text-muted">Food Categories</small>
                            </div>
                        </div>
                        <h1 class="fw-bold text-secondary mb-0">
                            <?= mysqli_num_rows(mysqli_query($index->con, "SELECT * FROM res_category")) ?>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Dishes -->
            <div class="col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-restaurant text-success display-5 me-3'></i>
                            <div>
                                <h6 class="mb-1 fw-semibold">Dishes</h6>
                                <small class="text-muted">Total Menu Items</small>
                            </div>
                        </div>
                        <h1 class="fw-bold text-success mb-0">
                            <?php
                            $sql = "SELECT * FROM dishes WHERE created_at BETWEEN '$startDate' AND '$endDate' $restaurantWhere";
                            echo mysqli_num_rows(mysqli_query($index->con, $sql));
                            ?>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-cart text-warning display-5 me-3'></i>
                            <div>
                                <h6 class="mb-1 fw-semibold">Total Orders</h6>
                                <small class="text-muted">Orders in Filter</small>
                            </div>
                        </div>
                        <h1 class="fw-bold text-warning mb-0">
                            <?php
                            $sql = "SELECT * FROM transaction WHERE order_date BETWEEN '$startDate' AND '$endDate' $restaurantWhere";
                            echo mysqli_num_rows(mysqli_query($index->con, $sql));
                            ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts + Transactions -->
        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Earnings</h5>
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Recent Transactions</h5>
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Customer</th>
                                    <th>Food Item</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $index->getRecentTransactions();
                                while ($row = mysqli_fetch_array($result)) {
                                    $statusMap = [
                                        'order_confirmation' => ['Confirmed', 'bg-success'],
                                        'Order_Canceled' => ['Cancelled', 'bg-danger'],
                                        'Order_Cancelled' => ['Cancelled', 'bg-danger'],
                                        'Order_Received' => ['Received', 'bg-primary'],
                                        'in_process' => ['In Process', 'bg-warning text-dark'],
                                        'place_order' => ['Placed', 'bg-info text-dark']
                                    ];
                                    $status = $row['status'];
                                    [$label, $badge] = $statusMap[$status] ?? [ucfirst(str_replace('_', ' ', $status)), 'bg-secondary'];
                                    echo "<tr>
                                        <td>{$row['transacId']}</td>
                                        <td>{$row['f_name']} {$row['l_name']}</td>
                                        <td>{$row['dishesOrder']}</td>
                                        <td><span class='badge $badge'>$label</span></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Data Setup -->
        <?php
        $earningsQuery = "SELECT 
            MONTH(order_date) AS month_number,
            SUM(total_price) AS total_earnings
        FROM transaction
        WHERE $conditions
        GROUP BY month_number
        ORDER BY month_number";

        $result = mysqli_query($index->con, $earningsQuery);
        $earnings = array_fill(1, 12, 0);
        while ($row = mysqli_fetch_assoc($result)) {
            $earnings[(int)$row['month_number']] = (float)$row['total_earnings'];
        }
        $chartData = json_encode(array_values($earnings), JSON_NUMERIC_CHECK);
        ?>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartData = <?= $chartData ?>;
            const monthLabels = ['January', 'February', 'March', 'April', 'May', 'June',
                                 'July', 'August', 'September', 'October', 'November', 'December'];

            const ctx = document.getElementById('earningsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Monthly Earnings (₱)',
                        data: chartData,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '₱' + value.toLocaleString()
                            }
                        }
                    }
                }
            });

            function applyFilters() {
                const year = document.getElementById('year').value;
                const filter = document.getElementById('filter').value;
                const restaurant = document.getElementById('restaurant').value;
                window.location.href = `?year=${year}&filter=${filter}&restaurant=${restaurant}`;
            }
        </script>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>
