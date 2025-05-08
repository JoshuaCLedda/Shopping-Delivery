<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>

<?php include "layouts/header.php"; ?>
<?php include "layouts/navbar.php"; ?>

  <!-- Top Links Navigation -->
<div class="top-links 
bg-light border-bottom">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                <div class="link-item active">
                    <i class='bx bx-store-alt fs-3 mb-1 text-primary'></i> <!-- Icon for Choose Stall -->
                    <a href="#" class="d-block text-decoration-none text-dark">Choose Stall</a>
                </div>
            </div>
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                <div class="link-item">
                    <i class='bx bx-food-menu fs-3 mb-1'></i> <!-- Icon for Pick Your Favorite Food -->
                    <a href="#" class="d-block text-decoration-none text-dark">Pick Your Favorite Food</a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="link-item">
                    <i class='bx bx-cart fs-3 mb-1'></i> <!-- Icon for Order and Pay -->
                    <a href="#" class="d-block text-decoration-none text-dark">Order and Pay</a>
                </div>
            </div>
        </div>
    </div>
</div>


             
<section class="popular py-5 bg-light">
    <div class="container">
        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="fw-bold">List of Stalls</h2>
            <p class="lead text-muted">Easiest way to order your favourite food among these top 6 dishes</p>
        </div>

        <div class="row g-4">
            <?php
            date_default_timezone_set('Asia/Manila'); // Set your timezone accordingly
            $current_time = date('H:i'); // Current time in 24-hour format (e.g. 13:30)

            $query_res = mysqli_query($db, "SELECT * FROM restaurant WHERE status = 0");
            while ($r = mysqli_fetch_array($query_res)):
                $restaurantId = htmlspecialchars($r['rs_id']);
                $restaurantTitle = htmlspecialchars($r['title']);
                $restaurantImage = htmlspecialchars($r['image']);
                $restaurantAddress = htmlspecialchars($r['address']);
                $o_hr = htmlspecialchars($r['o_hr']);
                $c_hr = htmlspecialchars($r['c_hr']);

                // Convert opening and closing hours to 24-hour format
                $open_time = date('H:i', strtotime($o_hr));
                $close_time = date('H:i', strtotime($c_hr));

                // Check if current time is within operating hours
                $is_open = ($current_time >= $open_time && $current_time <= $close_time);
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0 position-relative">
                        <?php if ($is_open): ?>
                            <a href="dishes.php?res_id=<?= $restaurantId ?>" class="text-decoration-none text-dark">
                            <?php else: ?>
                                <div class="text-decoration-none text-muted" style="cursor: not-allowed;">
                                <?php endif; ?>

                                <div class="card-img-top"
                                    style="background-image: url('admin/<?= $restaurantImage ?>');
                                background-size: cover;
                                background-position: center;
                                height: 160px;
                                border-top-left-radius: .5rem;
                                border-top-right-radius: .5rem;
                                opacity: <?= $is_open ? '1' : '0.6' ?>;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-1"><?= $restaurantTitle ?></h5>
                                    <p class="card-text text-muted small mb-1">
                                        <i class='bx bx-map-pin'></i>
                                        <?= !empty($restaurantAddress) ? $restaurantAddress : '<em>No Address</em>' ?>
                                    </p>
                                    <p class="card-text text-uppercase text-secondary small">
                                        <i class='bx bx-time'></i> <?= $o_hr ?> - <?= $c_hr ?>
                                    </p>
                                    <?php if (!$is_open): ?>
                                        <span class="badge bg-danger">Store Closed</span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($is_open): ?>
                            </a>
                        <?php else: ?>
                    </div>
                <?php endif; ?>
                </div>
        </div>
    <?php endwhile; ?>
    </div>


    </div>
</section>
            </div>
        </div>
    </section>

    <?php include 'layouts/footer.php'; ?>