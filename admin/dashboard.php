<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
// if(empty($_SESSION["adm_id"]))
// {
//     header('location:index.php');
// }
?>
    <?php include 'layouts/header.php' ?>
    <?php include 'layouts/sidebar.php' ?>
    <?php include 'layouts/navbar.php' ?>

    <div id="main">
    <div class="main-container">


                <div class="col-lg-12">

                <div class="d-flex align-items-center gap-2">
    <i class='bx bx-grid-alt text-success fs-5'></i>
    <h4 class="mb-0 text-success">Dashboard</h4>
</div>



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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
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
                        $result = mysqli_query($db, $sql); 
                        echo mysqli_num_rows($result);
                    ?>
                </h1>
            </div>
        </div>
    </div>
</div>


<div class="col-md-6">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Charts</h5>

            <div class="mb-4">
                <label for="filter" class="form-label">Select Filter:</label>
                <select id="filter" onchange="changeFilter()" class="form-select">
                    <option value="monthly" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'monthly') echo 'selected'; ?>>Monthly</option>
                    <option value="quarterly" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'quarterly') echo 'selected'; ?>>Quarterly</option>
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
              <th class="border-bottom-0"><h6 class="fw-semibold mb-0">ID</h6></th>
              <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Customer</h6></th>
              <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Food Item</h6></th>
              <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Status</h6></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border-bottom-0"><h6 class="fw-semibold mb-0">1</h6></td>
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
              <td class="border-bottom-0"><h6 class="fw-semibold mb-0">2</h6></td>
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
              <td class="border-bottom-0"><h6 class="fw-semibold mb-0">3</h6></td>
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
              <td class="border-bottom-0"><h6 class="fw-semibold mb-0">4</h6></td>
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

























                        <?php 
                        // Get filter type (monthly or quarterly)
                        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';

                        // Choose SQL query based on filter selection
                        if ($filter == 'quarterly') {
                            $query = "SELECT rs_id , COUNT(*) AS total_orders, CONCAT(YEAR(order_date), '-Q', QUARTER(order_date)) AS period 
                                      FROM transaction 
                                      WHERE status = 'Order_Received' 
                                      GROUP BY rs_id, period 
                                      ORDER BY period DESC";
                        } else {
                            $query = "SELECT rs_id, COUNT(*) AS total_orders, DATE_FORMAT(order_date, '%Y-%m') AS period 
                                      FROM transaction 
                                      WHERE status = 'Order_Received' 
                                      GROUP BY rs_id, period 
                                      ORDER BY period DESC";
                        }

                        $result = mysqli_query($db, $query);
                        $data = [];

                        while ($row = mysqli_fetch_assoc($result)) {
                            $data[] = $row;
                        }

                        // Convert PHP array to JSON
                        $chartData = json_encode($data, JSON_NUMERIC_CHECK);
                        ?>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                        <script>
                            // Parse PHP JSON data
                            let chartData = <?php echo $chartData; ?>;

                            // Extract Labels (Periods) and Data
                            let labels = chartData.map(row => row.period);
                            let data = chartData.map(row => row.total_orders);

                            // Create Chart.js Bar Chart
                            let ctx = document.getElementById('earningsChart').getContext('2d');
                            let earningsChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Total Orders Received',
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
