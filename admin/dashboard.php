<!DOCTYPE html>
<html lang="en">
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
<body class="fix-header">
    <div id="main-wrapper">
    <?php include 'layouts/navbar.php' ?>

    <?php include 'layouts/sidebar.php' ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Admin Dashboard</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-home f-s-40 "></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM restaurant";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Stalls</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-cutlery f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM dishes";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Dishes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-users f-s-40"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM users";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Users</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-shopping-cart f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM users_orders";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Total Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>                     
                        </div>     

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-th-large f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM res_category";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Restro Categories</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-spinner f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM users_orders WHERE status = 'in process'";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Processing Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-check f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM users_orders WHERE status = 'closed'";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Delivered Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        


                            <div class="col-md-3">
                                <div class="card p-30">  
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-times f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php 
                                                $sql = "SELECT * FROM users_orders WHERE status = 'rejected'";
                                                $result = mysqli_query($db, $sql); 
                                                $rws = mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Cancelled Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="col-md-6">
                                <div class="card p-100">
                                    <!-- Dropdown for Filter -->
                                    <label for="filter">Select Filter: </label>
                                    <select id="filter" onchange="changeFilter()" class="form-control">
                                        <option value="monthly" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'monthly') echo 'selected'; ?>>Monthly</option>
                                        <option value="quarterly" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'quarterly') echo 'selected'; ?>>Quarterly</option>
                                    </select>

                                    <canvas id="earningsChart"></canvas>
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

        <script src="js/lib/jquery/jquery.min.js"></script>
        <script src="js/lib/bootstrap/js/popper.min.js"></script>
        <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
        <script src="js/jquery.slimscroll.js"></script>
        <script src="js/sidebarmenu.js"></script>
        <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="js/custom.min.js"></script>
    </body>
</html>