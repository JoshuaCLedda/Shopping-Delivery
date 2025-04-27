<?php
session_start();
error_reporting(E_ALL);
include "../admin/Main.php";
$index = new Index;

// get the id from the rider
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['submit'])) {
    // Collect form data
    $rider_id = $_POST['rider_id'];
    $transaction_id = $_POST['transaction_id'];


    // Call the model function with image path
    $result = $index->acceptOrderRider(
        $rider_id,
        $transaction_id

    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Order Accepted Succesfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Please Try Again.'];
    }

    header("Location: received_orders.php");
    exit();
}

?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/rider/sidebar.php' ?>
<?php include '../layouts/rider/navbar.php' ?>


<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Rider</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riders Rating</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">



            <div class="col-xx-6 col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class='bx bxs-cart-alt text-primary display-5 me-3'></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">In Process Order</h6>
                                    <small class="text-muted">Total In Process Orders</small>
                                </div>
                            </div>
                            <h1 class="fw-bold text-primary mb-0">
                                <?php
                                $sql = "SELECT * FROM transaction
                                    WHERE status = 'in_process'";
                                $result = mysqli_query($index->con, $sql);
                                echo mysqli_num_rows($result);
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dishes -->
            <div class="col-xx-6 col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-receipt text-success display-5 me-3'></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Received Orders</h6>
                                    <small class="text-muted">Total Received Orders</small>
                                </div>
                            </div>
                            <h1 class="fw-bold text-success mb-0">
                                <?php
                                $sql = "SELECT * FROM transaction
                                    WHERE status = 'Order_Received'
                                    AND rider_id = '$user_id'";
                                $result = mysqli_query($index->con, $sql);
                                echo mysqli_num_rows($result);
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="col-xx-6 col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-package text-info display-5 me-3'></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Delivered Orders</h6>
                                    <small class="text-muted">Total Delivered Orders</small>
                                </div>
                            </div>
                            <h1 class="fw-bold text-info mb-0">
                                <?php
                                $sql = "SELECT * FROM transaction
                                    WHERE status = 'order_delivered'
                                    AND rider_id = '$user_id'";
                                $result = mysqli_query($index->con, $sql);
                                echo mysqli_num_rows($result);
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Rider Rating -->
            <div class="col-xx-6 col-md-6">
                <div class="card info-card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class='bx bxs-star text-warning display-5 me-3'></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Rider Rating</h6>
                                    <small class="text-muted">Average from Customer Reviews</small>
                                </div>
                            </div>
                            <h1 class="fw-bold text-warning mb-0">
                                <?php
                                $query = mysqli_query($index->con, "SELECT AVG(rating) AS avg_rating FROM
                    rating_rider WHERE rider_id = '$user_id'");
                                $data = mysqli_fetch_assoc($query);
                                $avgRating = $data['avg_rating'] ? round($data['avg_rating'], 1) : 0;
                                echo $avgRating . " ★";
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>








            <div class="col-lg-6 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-4">Recent Rider Ratings</h5>
                        <div class="table">
                            <table class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Rater's Name</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Rating</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Date</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $result = mysqli_query($index->con, "
            SELECT rating_rider.rating, rating_rider.created_at, CONCAT(users.f_name, ' ', users.l_name) AS rater_name
            FROM rating_rider
            LEFT JOIN users ON rating_rider.user_id = users.u_id
            WHERE rating_rider.rider_id = '$user_id'
            ORDER BY rating_rider.created_at DESC
            LIMIT 5
        ");

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">
                                                    <?php echo htmlspecialchars($row['rater_name'] ?? 'Anonymous'); ?>
                                                </h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">
                                                    <?php echo htmlspecialchars($row['rating']); ?> ★
                                                </h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">
                                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                                </h6>
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




        <?php include '../admin/layouts/footer.php' ?>