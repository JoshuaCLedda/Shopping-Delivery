<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dishId = $_GET['dish_id'];
$restaurantId = $_GET['restaurant_id'];

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

// add to cart function
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
                    <i class='bx bx-store-alt fs-3 mb-1'></i>
                    <a href="#" class="d-block text-decoration-none text-dark">Choose Stall</a>
                </div>
            </div>
            <div class="col-12 col-sm-4 mb-2 mb-sm-0 ">
                <div class="link-item">
                    <i class='bx bx-food-menu fs-3 mb-1 text-primary'></i>
                    <a href="#" class="d-block text-decoration-none text-dark">Pick Your Favorite Food</a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="link-item">
                    <i class='bx bx-cart fs-3 mb-1'></i>
                    <a href="#" class="d-block text-decoration-none text-dark">Order and Pay


                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="featured-dishes py-5 bg-light" id="menu">
    <div class="container">

        <!-- Header Section -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-5 text-pink">
                <i class='bx bxs-bowl-hot align-middle me-1'></i> Explore Dishes
            </h2>
            <p class="text-muted fs-5">
                Hungry? Start with your selected item and customize your meal with more tasty dishes!
            </p>
        </div>

        <!-- Main Dish Card -->
        <div class="row justify-content-center mb-5">
            <?php
            if (!$dishId || !is_numeric($dishId)) {
                echo "<div class='text-center text-danger'>Invalid or missing dish ID.</div>";
                exit;
            }

            $query = "SELECT * FROM dishes WHERE status = 0 AND d_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $dishId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $mainDishId = htmlspecialchars($product['d_id']);
                $dishTitle = htmlspecialchars($product['title']);
                $dishSlogan = htmlspecialchars($product['slogan']);
                $dishPrice = htmlspecialchars($product['price']);
                $dishImage = htmlspecialchars($product['img']);
                ?>
                <div class="col-md-4 col-lg-4">
                    <div class="card card-sm border-0 shadow-sm rounded-4 overflow-hidden main-dish-card">
                        <div class="main-dish-image" style="
                            background-image: url('admin/Res_img/<?= $dishImage ?>');
                            background-size: cover;
                            background-position: center;
                            height: 200px;"> 
                        </div>
                        <div class="card-body p-4">
                            <h3 class="fw-bold text-dark">
                                <i class='bx bxs-bowl-rice text-warning me-2'></i><?= $dishTitle ?>
                            </h3>
                            <p class="text-muted fs-6">
                                <i class='bx bx-info-circle'></i> <?= $dishSlogan ?>
                            </p>
                            <p class="text-success fw-bold fs-4 mb-0">
                                <i class='bx bx-money'></i> ₱<?= $dishPrice ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>


        <!-- Add-ons Section -->
        <div class="bg-white p-4 p-md-5 border rounded-4 shadow-sm">
            <form method="POST" action="addToCart.php">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id'] ?? 0) ?>">
                <input type="hidden" name="selected_dishes[]" value="<?= $mainDishId ?>">

                <div class="text-center mb-4">
                    <h4 class="fw-bold text-pink">
                        Customize Your Order
                    </h4>
                    <p class="text-muted">Add more delicious dishes to go with your main course.</p>
                </div>

                <div class="row g-4 justify-content-center">
                    <?php
                    $query = "SELECT * FROM dishes WHERE status = 0 AND rs_id = ? AND d_id != ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("ii", $restaurantId, $dishId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($product = $result->fetch_assoc()) {
                            $id = htmlspecialchars($product['d_id']);
                            $title = htmlspecialchars($product['title']);
                            $slogan = htmlspecialchars($product['slogan']);
                            $price = htmlspecialchars($product['price']);
                            $image = htmlspecialchars($product['img']);
                            ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div
                                    class="card h-100 shadow-sm border-0 rounded-4 dish-card position-relative transition-hover">
                                    <div class="card-img-top rounded-top-4 dish-image" style="
                  background-image: url('admin/Res_img/<?= $image ?>');">
                                    </div>
                                    <div class="card-body d-flex flex-column px-3 pb-3">
                                        <h5 class="fw-semibold text-dark mb-1">
                                            <i class='bx bxs-bowl-rice text-warning me-1'></i><?= $title ?>
                                        </h5>
                                        <p class="text-muted small mb-2"><?= $slogan ?></p>
                                        <p class="text-success fw-bold fs-6 mb-3">
                                            <i class='bx bx-money'></i> ₱<?= $price ?>
                                        </p>

                                        <div class="form-check mt-auto">
                                            <input class="form-check-input" type="checkbox" name="selected_dishes[]"
                                                value="<?= $id ?>" id="dish<?= $id ?>">
                                            <label class="form-check-label small" for="dish<?= $id ?>">Add this dish</label>
                                        </div>

                                        <a href="viewDishes.php?dish_id=<?= $id ?>&restaurant_id=<?= $restaurantId ?>"
                                            class="btn btn-sm btn-outline-pink mt-2 w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '
              <div class="col-12 text-center">
                  <img src="assets/images/icons/emptycart.svg" alt="No Dishes" style="width:180px;" class="mb-3">
                  <h6 class="text-muted">No other dishes available at the moment 🍽️</h6>
              </div>';
                    }
                    ?>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-pink btn-lg px-5 rounded-pill shadow-sm">
                        <i class="bx bx-cart"></i> Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#dishCategorySelect').on('change', function () {
        var catId = $(this).val();
        var resId = <?= json_encode($_GET['res_id']) ?>;

        $.ajax({
            url: 'fetch_dish.php',
            type: 'POST',
            data: {
                cat_id: catId,
                res_id: resId
            },
            beforeSend: function () {
                $('#dishContainer').html('<div class="text-center">Loading dishes...</div>');
            },
            success: function (response) {
                $('#dishContainer').html(response);
            },
            error: function () {
                $('#dishContainer').html('<div class="text-danger">Failed to load dishes.</div>');
            }
        });
    });
</script>

<?php include 'layouts/sweetalert.php'; ?>



<?php include 'layouts/footer.php'; ?>
<style>


    .text-pink {
        color: #d70f64;
    }

    .btn-pink {
        background-color: #d70f64;
        color: #fff;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-pink:hover {
        background-color: #b70d56;
    }

    .btn-outline-pink {
        color: #d70f64;
        border-color: #d70f64;
        transition: all 0.3s ease;
    }

    .btn-outline-pink:hover {
        background-color: #d70f64;
        color: #fff;
    }

    .dish-image {
        background-size: cover;
        background-position: center;
        height: 170px;
        transition: transform 0.3s ease;
    }

    .transition-hover:hover .dish-image {
        transform: scale(1.05);
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .card-body h5,
    .card-body h3 {
        transition: color 0.2s ease-in-out;
    }

    .card:hover h5,
    .card:hover h3 {
        color: #d70f64;
    }
</style>