<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$res_id = $_GET['res_id'];

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

<div class="page-wrapper">
    <div class="container">
        <div class="bg-light py-3 border-bottom">
            <?php include 'layouts/alert.php' ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="menu-widget" id="2">
                        <div class="widget-heading d-flex justify-content-between align-items-center">
                            <h6 class="widget text-dark mb-0">You might also like other beverages and desert</h6>
                            <a class="btn btn-link" data-bs-toggle="collapse" href="#popular2" role="button" aria-expanded="true" aria-controls="popular2">
                                <i class="fa fa-angle-down"></i>
                            </a>
                        </div>

                        <div class="collapse show" id="popular2">
                            <?php
                            $stmt = $index->con->prepare("SELECT * FROM dishes WHERE status = 1
                            AND dish_category_id = 4 AND 5
                            AND rs_id = ?");
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












        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <?php include 'layouts/footer.php'; ?>