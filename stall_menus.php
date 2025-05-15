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

<div class="page-wrapper py-5 bg-light-subtle min-vh-100">
    <div class="container">
        <?php include 'layouts/sweetalert.php'; ?>

        <!-- Page Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6 text-dark">Other Dishes</h2>
            <p class="lead text-muted">You can order additional dishes from the same shop</p>
        </div>

        <!-- Category Tabs -->
        <ul class="nav nav-pills justify-content-center mb-4" id="dishTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#all">All</a>
            </li>
         
        </ul>

        <div class="tab-content" id="dishContent">
            <div class="tab-pane fade show active" id="all">
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
                                <form method="POST" action="" class="card h-100 border-0 shadow-sm dish-card rounded-4 overflow-hidden">
                                    <!-- Image -->
                                    <div class="ratio ratio-4x3 bg-light overflow-hidden">
                                        <?php if (!empty($product['img']) && file_exists($imagePath)) { ?>
                                            <img src="<?= $imagePath ?>" class="w-100 h-100 object-fit-cover" alt="Dish Image">
                                        <?php } else { ?>
                                            <div class="d-flex align-items-center justify-content-center text-muted w-100 h-100">
                                                <i class="bx bx-image-alt fs-1 me-2"></i> No Image
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="card-body d-flex flex-column justify-content-between p-3">
                                        <div>
                                            <h6 class="fw-semibold text-dark mb-1">
                                                <?= htmlspecialchars($product['title']) ?>
                                                <span class="badge bg-warning text-dark ms-1">Popular</span>
                                            </h6>
                                            <p class="text-muted small mb-2"><?= htmlspecialchars($product['slogan']) ?></p>
                                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2 py-1">
                                                ₱<?= htmlspecialchars($product['price']) ?>
                                            </span>
                                        </div>

                                        <!-- Quantity and Add -->
                                        <div class="mt-3 d-flex gap-2 align-items-center">
                                            <div class="input-group input-group-sm" style="max-width: 90px;">
                                                <input type="number" name="quantity" value="1" min="1" class="form-control quantity-input">
                                            </div>
                                            <input type="hidden" name="dishes_id" value="<?= htmlspecialchars($product['d_id']) ?>">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
                                            <button type="submit" name="submit" class="btn btn-primary btn-sm flex-fill add-cart-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart">
                                                <i class="bx bx-cart me-1"></i> Add
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

            <!-- You can add more category panes like #rice or #drinks here -->

        </div>

      <div class="accordion mt-5" id="faqAccordion">
    <!-- Q1 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="faq1">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                How do I add multiple dishes?
            </button>
        </h2>
        <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Simply adjust the quantity for each dish and click "Add". You can add multiple different dishes one by one.
            </div>
        </div>
    </div>

    <!-- Q2 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="faq2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                Can I edit my cart later?
            </button>
        </h2>
        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Yes, go to your cart page to change quantities or remove items before checkout.
            </div>
        </div>
    </div>

    <!-- Q3 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="faq3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                What payment methods do you accept?
            </button>
        </h2>
        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                               We currently accept cash on delivery, GCash, and Cash on Delivery. More options will be added soon!

            </div>
        </div>
    </div>

    <!-- Q4 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="faq4">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                Can I schedule my order?
            </button>
        </h2>
        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Yes, you can specify a preferred delivery time during checkout. Make sure the restaurant supports scheduling.
            </div>
        </div>
    </div>

    <!-- Q5 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="faq5">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                What if a dish is out of stock?
            </button>
        </h2>
        <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                If a dish becomes unavailable after placing an order, our team will contact you to suggest an alternative or refund.
            </div>
        </div>
    </div>
</div>



    </div>
</div>
<style>
.page-wrapper {
    background: #f9f9f9;
}

.dish-card {
    border-radius: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #fff;
}

.dish-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.quantity-input {
    border-radius: 6px;
    font-size: 0.875rem;
}

.add-cart-btn {
    font-weight: 500;
    background-color: #0d6efd;
    color: #fff;
    transition: all 0.2s ease-in-out;
    border-radius: 6px;
}

.add-cart-btn:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
}

.accordion-button {
    font-weight: 500;
}
</style>
<script>
    // Bootstrap tooltips
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include 'layouts/footer.php'; ?>