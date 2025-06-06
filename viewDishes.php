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
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">
                <i class='bx bxs-bowl-hot text-danger align-middle'></i> Dishes Information
                <?=$restaurantId ?>
            </h2>
            <p class="text-muted fs-5">There are also other available dishes you can add</p>
        </div>



<div class="py-5 bg-light border rounded shadow-sm">
    <div class="container">
        <div class="row justify-content-center" id="dishContainer">
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
                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 border-0 shadow-sm rounded overflow-hidden">
                        <div style="
                            background-image: url('admin/Res_img/<?= $dishImage ?>');
                            background-size: cover;
                            background-position: center;
                            height: 250px;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title mb-2">
                                <i class='bx bxs-bowl-rice text-warning'></i> <?= $dishTitle ?>
                            </h4>
                            <p class="card-text text-muted small mb-2">
                                <i class='bx bx-info-circle'></i> <?= $dishSlogan ?>
                            </p>
                            <p class="card-text text-success fw-bold fs-4 mb-4">
                                <i class='bx bx-money'></i> ₱<?= $dishPrice ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo '
                <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 300px;">
                    <div class="text-center">
                        <img src="assets/images/icons/emptycart.svg" alt="No Dish" class="mb-4" style="width: 200px;">
                        <h5 class="text-muted">Dish not found or unavailable 🛒</h5>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</div>

<hr>

<!-- Other Dishes with Checkboxes -->
<div class="py-5 bg-light border rounded shadow-sm">
    <div class="container">
        <form method="POST" action="addToCart.php">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id'] ?? 0) ?>">
            <div class="row g-4 justify-content-center" id="dishContainer">
                <?php
                if (!isset($restaurantId) || !is_numeric($restaurantId)) {
                    echo "<div class='text-center text-danger'>Invalid or missing restaurant ID.</div>";
                    exit;
                }

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
                            <div class="card shadow-sm h-100 border-0 dish-card position-relative">
                                <span class="badge bg-success position-absolute top-0 start-0 m-2">Popular</span>

                                <div class="card-img-top" style="
                                    background-image: url('admin/Res_img/<?= $image ?>');
                                    background-size: cover;
                                    background-position: center;
                                    height: 160px;
                                    border-top-left-radius: .5rem;
                                    border-top-right-radius: .5rem;">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-1">
                                        <i class='bx bxs-bowl-rice text-warning'></i> <?= $title ?>
                                    </h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class='bx bx-info-circle'></i> <?= $slogan ?>
                                    </p>
                                    <p class="card-text text-success fw-bold fs-5 mb-2">
                                        <i class='bx bx-money'></i> ₱<?= $price ?>
                                    </p>

                                    <div class="form-check mt-auto">
                                        <input class="form-check-input" type="checkbox" name="selected_dishes[]" value="<?= $id ?>" id="dish<?= $id ?>">
                                        <label class="form-check-label small" for="dish<?= $id ?>">
                                            Select to add
                                        </label>
                                    </div>

                                    <a href="viewDishes.php?dish_id=<?= $id ?>&restaurant_id=<?= $restaurantId ?>"
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '
                    <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 300px;">
                        <div class="text-center">
                            <img src="assets/images/icons/emptycart.svg" alt="Empty Cart" class="mb-4" style="width: 200px;">
                            <h5 class="text-muted">No available dishes at the moment 🛒</h5>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <!-- Submit Selected Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bx bx-cart"></i> Add Selected to Cart
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