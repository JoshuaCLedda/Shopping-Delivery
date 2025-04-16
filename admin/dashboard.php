<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;

?>
<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>

<div id="main">
    <div class="main-container">


        <div class="col-lg-12">


            <div class="row g-4">

                <!-- Stalls -->
                <div class="col-xxl-3 col-md-6">
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
                <div class="col-xxl-3 col-md-6">
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

                <!-- Users -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-user text-info display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Users</h6>
                                        <small class="text-muted">Registered Users</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-info mb-0">
                                    <?php
                                    $sql = "SELECT * FROM users";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-xxl-3 col-md-6">
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
                <div class="col-xxl-3 col-md-6">
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

                <!-- Processing Orders -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-loader-alt text-primary display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Processing</h6>
                                        <small class="text-muted">Orders In Process</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-primary mb-0">
                                    <?php
                                    $sql = "SELECT * FROM users_orders WHERE status = 'in process'";
                                    $result = mysqli_query($index->con, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivered Orders -->
                <div class="col-xxl-3 col-md-6">
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

                <!-- Cancelled Orders -->
                <div class="col-xxl-3 col-md-6">
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


                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Charts</h5>

                            <div class="mb-4">
                                <label for="filter" class="form-label">Select Filter:</label>
                                <select id="filter" onchange="changeFilter()" class="form-select">
                                    <option value="monthly" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'monthly')
                                        echo 'selected'; ?>>Monthly</option>
                                    <option value="quarterly" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'quarterly')
                                        echo 'selected'; ?>>Quarterly</option>
                                </select>
                            </div>

                            <!-- Chart Canvas -->
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

        <?php
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';

        // Choose SQL query based on filter selection
        if ($filter == 'quarterly') {
            // Query for top 5 restaurants by order_confirmation status, grouped by quarters
            $query = "SELECT restaurant.title, COUNT(*) AS total_orders, CONCAT(YEAR(transaction.order_date), '-Q', QUARTER(transaction.order_date)) AS period
              FROM transaction
              LEFT JOIN restaurant ON restaurant.rs_id = transaction.rs_id
              WHERE transaction.status = 'order_confirmation'
              GROUP BY restaurant.rs_id, period
              ORDER BY total_orders DESC
              LIMIT 5";
        } else {
            // Query for top 5 restaurants by order_confirmation status, grouped by months
            $query = "SELECT restaurant.title, COUNT(*) AS total_orders, DATE_FORMAT(transaction.order_date, '%Y-%m') AS period
              FROM transaction
              LEFT JOIN restaurant ON restaurant.rs_id = transaction.rs_id
              WHERE transaction.status = 'order_confirmation'
              GROUP BY restaurant.rs_id, period
              ORDER BY total_orders DESC
              LIMIT 5";
        }

        $result = mysqli_query($index->con, $query);
        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        // Convert PHP array to JSON
        $chartData = json_encode($data, JSON_NUMERIC_CHECK);

        ?>
        <script>
            // Parse PHP JSON data
            let chartData = <?php echo $chartData; ?>;

            // Extract Labels (Restaurant Names) and Data (Total Orders)
            let labels = chartData.map(row => row.title); // Restaurant Names
            let data = chartData.map(row => row.total_orders); // Total Orders for each restaurant

            // Create Chart.js Bar Chart
            let ctx = document.getElementById('earningsChart').getContext('2d');
            let earningsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,  // Now using restaurant names as labels
                    datasets: [{
                        label: 'Confirmed Orders',  // Change this label as needed
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Function to Change Filter via URL
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