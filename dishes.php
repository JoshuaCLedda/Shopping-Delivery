<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// include_once 'product-action.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    include "admin/Main.php";
    $index = new Index;

    $query = "SELECT f_name, l_name, username, email, phone, address FROM users WHERE u_id = '$user_id'";
    $result = mysqli_query($index->con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}


if (isset($_POST['submit'])) {
    // Collect form data
    $user_id = $_POST['user_id'];
    $d_id = $_POST['dishes_id'];
    $quantity = $_POST['quantity'];

    // Call the model function
    $result = $index->addToCart(
        $user_id,
        $quantity,
        $d_id
    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Item added to cart successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to add item to cart.'];
    }
    
    header("Location: dishes.php?res_id=" . $_GET['res_id']);
    exit();
    
    
}


?>

<?php include 'layouts/header.php'; ?>
<?php include 'layouts/navbar.php'; ?>


<div class="page-wrapper">
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <div class="row text-center">

                <div class="col-md-4 mb-2">
                    <div class="p-2 rounded <?= !isset($_GET['res_id']) ? 'bg-primary text-white' : '' ?>">
                        <span class="fw-bold me-2">1</span>
                        <a href="restaurants.php" class="text-decoration-none <?= !isset($_GET['res_id']) ? 'text-white' : 'text-dark' ?>">Choose Stall</a>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="p-2 rounded bg-primary text-white">
                        <span class="fw-bold me-2">2</span>
                        <a href="dishes.php?res_id=<?= $_GET['res_id'] ?>" class="text-decoration-none text-white">Pick Your Favorite Food</a>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="p-2 rounded <?= !isset($_GET['order']) ? 'text-dark' : 'bg-primary text-white' ?>">
                        <span class="fw-bold me-2">3</span>
                        <a href="#" class="text-decoration-none <?= !isset($_GET['order']) ? 'text-dark' : 'text-white' ?>">Order and Pay</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<section class="inner-page-hero bg-image" data-image-src="images/img/rest.png">
    <?php $ress = mysqli_query($index->con, "select * from restaurant where rs_id='$_GET[res_id]'");
    $rows = mysqli_fetch_array($ress);

    ?>

    <div class="profile">
        <div class="container">
            <div class="row align-items-center" style="min-height: 250px;">
                <!-- Restaurant Logo Section -->
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
                    <a class="restaurant-logo" href="dishes.php?res_id=<?= $restaurantId ?>">
                        <div style="
                            background-image: url('admin/<?= htmlspecialchars($rows['image']) ?>');
                            background-size: cover;
                            background-position: center;
                            width: 100%;
                            height: 220px;
                            border-radius: 8px;">
                        </div>
                    </a>
                </div>

                <!-- Restaurant Info Section -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 profile-desc d-flex flex-column justify-content-center p-4" style="border-radius: 0 10px 10px 0;">
                    <div class="right-text">
                        <h6 class="mb-2">
                            <p style="color: black;"><?= htmlspecialchars($rows['title']) ?></a=>
                        </h6>
                        <p style="color: black;">
                            <?= htmlspecialchars($rows['address'] ?? 'No address available') ?>
                        </p>

                        <span class="product-name" style="text-transform: uppercase; color: #333"> Open From:
                            <?= htmlspecialchars($rows['o_hr']) ?> - <?= htmlspecialchars($rows['c_hr']) ?>
                        </span>
                    </div
                        </div>
                </div>
            </div>
        </div>
</section>


<div class="container m-t-30">

    <div class="row">
        <div class="col-md-12">
            <div class="menu-widget" id="2">
                <div class="widget-heading d-flex justify-content-between align-items-center">
                    <h3 class="widget-title text-dark mb-0">Available Menu</h3>
                    <a class="btn btn-link" data-bs-toggle="collapse" href="#popular2" role="button" aria-expanded="true" aria-controls="popular2">
                        <i class="fa fa-angle-down"></i>
                    </a>
                </div>

                <div class="collapse show" id="popular2">
                    <?php
                    $stmt = $index->con->prepare("SELECT * FROM dishes WHERE status = 1 AND rs_id = ?");
                    $stmt->bind_param("i", $_GET['res_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($product = $result->fetch_assoc()) {
                    ?>


                            <div class="food-item py-4 border-bottom">
                                <form method="POST" action="">
                                    <div class="row align-items-center">
                                        <!-- Image -->
                                        <div class="col-12 col-sm-3 col-lg-2 mb-3 mb-sm-0">
                                            <div class="rest-logo">
                                                <a class="restaurant-logo d-block" href="#">


                                                    <div class="ratio ratio-4x3 rounded shadow-sm" style="width: 100%; max-width: 120px;">
                                                        <?php
                                                        $imagePath = "admin/Res_img/dishes/" . htmlspecialchars($product['img']);
                                                        if (!empty($product['img']) && file_exists($imagePath)) {
                                                        ?>
                                                            <img
                                                                src="<?= $imagePath ?>"
                                                                alt="Dish Image"
                                                                class="img-fluid w-100 h-100 object-fit-cover rounded"
                                                                style="border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" />
                                                        <?php } else { ?>
                                                            <div class="d-flex align-items-center justify-content-center bg-light text-muted w-100 h-100"
                                                                style="border-radius: 12px; font-size: 13px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                                                <i class="fa fa-image me-2"></i> No Image
                                                            </div>
                                                        <?php } ?>
                                                    </div>

                                                </a>
                                            </div>
                                        </div>

                                        <!-- Dish Info -->
                                        <div class="col-12 col-sm-6 col-lg-7">
                                            <div class="rest-descr">
                                                <h5 class="mb-1 fw-semibold text-dark"><?= htmlspecialchars($product['title']) ?></h5>
                                                <p class="text-muted small mb-0"><?= htmlspecialchars($product['slogan']) ?></p>
                                            </div>
                                        </div>

                                        <!-- Price + Quantity + Button -->
                                        <div class="col-12 col-sm-3 col-lg-3 text-sm-end">
                                            <div class="item-cart-info d-flex flex-column align-items-start align-items-sm-end">
                                                <span class="price mb-2 fw-bold text-success fs-5">₱<?= htmlspecialchars($product['price']) ?></span>

                                                <input
                                                    class="form-control form-control-sm mb-2"
                                                    type="number"
                                                    name="quantity"
                                                    value="1"
                                                    min="1"
                                                    style="width: 150px;" />

                                                <!-- Hidden fields -->
                                                <input type="hidden" name="dishes_id" value="<?= htmlspecialchars($product['d_id']) ?>">
                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">

                                                <input
                                                    type="submit"
                                                    name="submit"
                                                    class="btn btn-sm btn-primary"
                                                    value="Add to Cart" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>




                        <?php
                        }
                    } else {
                        ?>
                        <div class="py-5 text-center">
                            <p class="text-muted fs-5">No dishes available at the moment.</p>
                        </div>
                    <?php
                    }
                    ?>
                </div>





            </div>
        </div>
    </div>



</div>

<?php include 'layouts/sweetalert.php'; ?>



<?php include 'layouts/footer.php'; ?>
