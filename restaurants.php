<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>

<?php include "layouts/header.php"; ?>
<?php include "layouts/navbar.php"; ?>

<div class="page-wrapper">

    <!-- Top Links Navigation -->
    <div class="top-links py-3 bg-light border-bottom">
        <div class="container">
            <div class="row text-center">
                <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                    <div class="link-item active">
                        <span class="d-block fw-bold mb-1">1</span>
                        <a href="#" class="text-decoration-none text-dark">Choose Stall</a>
                    </div>
                </div>
                <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                    <div class="link-item">
                        <span class="d-block fw-bold mb-1">2</span>
                        <a href="#" class="text-decoration-none text-dark">Pick Your Favorite Food</a>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="link-item">
                        <span class="d-block fw-bold mb-1">3</span>
                        <a href="#" class="text-decoration-none text-dark">Order and Pay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Image Section -->
    <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
        <div class="container">
            <!-- Optional Hero Content -->
        </div>
    </div>

    <!-- Restaurant Listings Section -->
    <section class="restaurants-page py-4">
        <div class="container">
            <div class="row">
                <!-- Sidebar (optional) -->
                <div class="col-lg-3 mb-4">
                    <!-- Add Filters or Search Bar if needed -->
                </div>

                <!-- Main Content (Restaurant Listings) -->
                <div class="col-lg-12">
                    <div class="bg-light rounded shadow-sm p-4">
                        <div class="row g-4">
                            <?php
                            $ress = mysqli_query($db, "SELECT * FROM restaurant");
                            while ($rows = mysqli_fetch_array($ress)) {
                            ?>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 bg-white h-100 d-flex flex-column justify-content-between">
                                        <!-- Restaurant Image, Title, and Address -->
                                        <div class="d-flex align-items-start gap-3 mb-3">
                                            <div class="flex-shrink-0" style="width: 120px; height: 120px;">
                                                <?php
                                                $imagePath = "admin/" . htmlspecialchars($rows['image']);
                                                if (!empty($rows['image']) && file_exists($imagePath)) {
                                                ?>
                                                    <img src="admin/<?= htmlspecialchars($rows['image']) ?>"
                                                        class="img-fluid rounded shadow-sm object-fit-cover w-100 h-100" />
                                                <?php } else { ?>
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted w-100 h-100 rounded shadow-sm"
                                                        style="font-size: 12px; height: 120px;">
                                                        No Data
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">
                                                    <a href="dishes.php?res_id=<?= $rows['rs_id'] ?>" class="text-decoration-none text-dark">
                                                        <?= htmlspecialchars($rows['title']) ?>
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-0 small"><?= htmlspecialchars($rows['address']) ?></p>
                                                <p class="text-muted mb-0 small">Stall Link:<?= htmlspecialchars($rows['url']) ?></p>

                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="text-end mt-auto">
                                            <a href="dishes.php?res_id=<?= $rows['rs_id'] ?>" class="btn btn-primary btn-sm">
                                                View Menu
                                            </a>
                                        </div>
                                    </div>
                                </div>






                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'layouts/footer.php'; ?>