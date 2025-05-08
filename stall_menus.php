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
    <div class="container ">
        <?php include 'layouts/sweetalert.php'; ?>

        <div class="bg-white rounded shadow-sm p-4">

            <div class="collapse show" id="popular2">
            <div class="text-center mb-4">
            <h2 class="fw-bold">Other Dishes</h2>
            <p class="lead text-muted">You can order addional dishes from the same shops</p>
        </div>


        <div class="row g-4">
    <?php
    $stmt = $index->con->prepare("SELECT * FROM dishes WHERE status = 1 AND dish_category_id IN (4,5) AND rs_id = ?");
    $stmt->bind_param("i", $_GET['res_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            $imagePath = "admin/Res_img/" . htmlspecialchars($product['img']);
    ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <form method="POST" action="" class="card h-100 border-0 dish-card">
                    <div class="ratio ratio-4x3 overflow-hidden">
                        <?php if (!empty($product['img']) && file_exists($imagePath)) { ?>
                            <img src="<?= $imagePath ?>" class="w-100 h-100 " alt="Dish Image">
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
/* Card base styles */
.card {
    border-radius: 8px; /* Soft rounded corners */
    overflow: hidden; /* Ensure content fits inside the card */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition for hover effect */
}

/* Dish image */
.card-img-top {
    width: 100%;
    height: 150px; /* Set a smaller height for the image */
    object-fit: cover; /* Make sure the image covers the area without distortion */
}

/* Hover effect */
.dish-card:hover {
    transform: translateY(-5px); /* Slight lift on hover */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Soft shadow on hover */
}

/* Card body */
.card-body {
    padding: 1rem;
    background-color: #fff;
    color: #333;
}

.card-body a {
    text-decoration: none; /* Remove underline from links */
}

.card-body a:hover {
    color: #0d6efd; /* Change link color on hover */
}

/* List group items */
.list-group-item {
    border: 1px solid #ddd; /* Light border around list items */
}

/* Optionally, add a hover effect to list group items */
.list-group-item:hover {
    background-color: #f8f9fa; /* Slight background change on hover */
}

/* Style for the input field */
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

/* Add to cart button */
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