<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);  // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include("../connection/connect.php");


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../login.php");
    exit();
}

$stall_id = $_SESSION['user_id'];
$stmt = $db->prepare("
  SELECT 
    MONTHNAME(order_date) AS month,
    SUM(total_price) AS earnings
  FROM transaction
  WHERE stall_id = ? AND status = 'completed' AND YEAR(order_date) = YEAR(CURDATE())
  GROUP BY MONTH(order_date)
  ORDER BY MONTH(order_date)
");
$stmt->bind_param("i", $stall_id);
$stmt->execute();
$result = $stmt->get_result();
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

                                <h4 class="mb-3">Monthly Earnings - <?php echo date("Y"); ?></h4>
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Month</th>
                                            <th>Total Earnings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['month']; ?></td>
                                                <td>₱<?php echo number_format($row['earnings'], 2); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php include '../admin/layouts/footer.php' ?>