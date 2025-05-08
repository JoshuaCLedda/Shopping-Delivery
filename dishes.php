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

    header("Location: stall_menus.php?res_id=" . $_GET['res_id']);
    exit();
}


?>

<?php include 'layouts/header.php'; ?>
<?php include 'layouts/navbar.php'; ?>

<div class="top-links 
bg-light border-bottom">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                <div class="link-item active">
                    <i class='bx bx-store-alt fs-3 mb-1'></i> <!-- Icon for Choose Stall -->
                    <a href="#" class="d-block text-decoration-none text-dark">Choose Stall</a>
                </div>
            </div>
            <div class="col-12 col-sm-4 mb-2 mb-sm-0 ">
                <div class="link-item">
                    <i class='bx bx-food-menu fs-3 mb-1 text-primary'></i> <!-- Icon for Pick Your Favorite Food -->
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="featured-dishes py-5 bg-light">
    <div class="container">

        <!-- Header -->
        <div class="row align-items-center mb-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Available Dishes</h2>
                <p class="lead text-muted">Browse the dishes available at the restaurant</p>
            </div>
        </div>

        <!-- Dish Cards -->
        <div class="row g-4">
            <?php
            $stmt = $index->con->prepare("SELECT * FROM dishes WHERE status = 1 AND rs_id = ?");
            $stmt->bind_param("i", $_GET['res_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    $dishId = htmlspecialchars($product['d_id']);
                    $dishTitle = htmlspecialchars($product['title']);
                    $dishSlogan = htmlspecialchars($product['slogan']);
                    $dishPrice = htmlspecialchars($product['price']);
                    $dishImage = htmlspecialchars($product['img']);
            ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card shadow-sm h-100 border-0 position-relative dish-card">
                            <div class="card-img-top"
                                style="background-image: url('admin/Res_img/<?= $dishImage ?>');
                                    background-size: cover;
                                    background-position: center;
                                    height: 150px;
                                    border-top-left-radius: .5rem;
                                    border-top-right-radius: .5rem;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-1"><?= $dishTitle ?></h5>
                                <p class="card-text text-muted small mb-1"><?= $dishSlogan ?></p>
                                <p class="card-text text-success fw-bold fs-5">₱<?= $dishPrice ?></p>

                                <form method="POST" action="">
                                    <div class="d-flex align-items-center">
                                        <input class="form-control form-control-sm me-2" type="number" name="quantity" value="1" min="1" style="width: 100px;">
                                        <input type="hidden" name="dishes_id" value="<?= $dishId ?>">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
                                        <button type="submit" name="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="py-5 text-center bg-light border rounded shadow-sm col-12">
                    <img src="assets/images/icons/cancel.svg" alt="No Dishes" class="mb-4" style="width: 200px;">
                    <h5 class="text-secondary">No dishes available at the moment sorry.</h5>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
</section>

<style>
    .card {
        max-width: 90%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dish-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        transition: transform 0.3s ease;
    }

    .dish-card:hover .card-body {
        transform: scale(1.05);
    }
</style>

<?php include 'layouts/sweetalert.php'; ?>



<?php include 'layouts/footer.php'; ?>