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
    <div class="container my-5">
        <?php include 'layouts/sweetalert.php'; ?>

        <div class="bg-white rounded shadow-sm p-4">

            <div class="collapse show" id="popular2">
                <div class="row g-4">
                    <?php
                    $stmt = $index->con->prepare("SELECT * FROM dishes WHERE status = 1 AND dish_category_id IN (4,5) AND rs_id = ?");
                    $stmt->bind_param("i", $_GET['res_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($product = $result->fetch_assoc()) {
                            $imagePath = "admin/Res_img/dishes/" . htmlspecialchars($product['img']);
                    ?>
                            <div class="col-md-6 col-lg-4">
                                <form method="POST" action="" class="card h-100 border-0 shadow-sm hover-shadow transition">
                                    <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                                        <?php if (!empty($product['img']) && file_exists($imagePath)) { ?>
                                            <img src="<?= $imagePath ?>" class="w-100 h-100 object-fit-cover" alt="Dish Image">
                                        <?php } else { ?>
                                            <div class="d-flex align-items-center justify-content-center bg-light text-muted w-100 h-100">
                                                <i class="fa fa-image me-2"></i> No Image
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-semibold text-dark mb-1"><?= htmlspecialchars($product['title']) ?></h6>
                                            <p class="text-muted small mb-2"><?= htmlspecialchars($product['slogan']) ?></p>
                                            <span class="badge bg-light text-success border border-success px-2 py-1">₱<?= htmlspecialchars($product['price']) ?></span>
                                        </div>
                                        <div class="mt-3">
                                            <div class="d-flex gap-2 mb-2">
                                                <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm quantity-input" />
                                            </div>
                                            <input type="hidden" name="dishes_id" value="<?= htmlspecialchars($product['d_id']) ?>">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
                                            <button type="submit" name="submit" class="btn btn-primary btn-sm w-100 add-cart-btn">
                                                <i class="fa fa-shopping-cart me-1"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center py-5"><p class="text-muted fs-5">No other dishes available at the moment.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .transition {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card-body {
        padding: 1.25rem;
        background-color: #fefefe;
    }

    .card-body h6 {
        font-size: 1rem;
        color: #222;
    }

    .card-body .badge {
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 6px;
    }

    .quantity-input {
        border-radius: 6px;
        border: 1px solid #ccc;
        padding: 0.4rem 0.6rem;
        width: 60%;
        transition: border 0.2s;
    }

    .quantity-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
    }

    .add-cart-btn {
        font-weight: 500;
        background-color: #0d6efd;
        border: none;
        transition: background-color 0.3s, transform 0.2s;
    }

    .add-cart-btn:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
    }

    .add-cart-btn:active {
        transform: translateY(0);
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<?php include 'layouts/footer.php'; ?>