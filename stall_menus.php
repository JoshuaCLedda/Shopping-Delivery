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
            <h2 class="fw-bold display-5 text-pink">
                <i class='bx bxs-bowl-hot align-middle me-1'></i> Explore Dishes
            </h2>
            <p class="text-muted fs-5">
                Hungry? Start with your selected item and customize your meal with more tasty dishes!
            </p>
        </div>




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
                                <form method="POST" action=""
                                    class="card h-100 border-0 shadow-sm dish-card rounded-4 overflow-hidden">
                                    <!-- Dish Image -->
                                    <div class="ratio ratio-4x3 bg-light overflow-hidden rounded-top">
                                        <?php if (!empty($product['img']) && file_exists($imagePath)) { ?>
                                            <img src="<?= $imagePath ?>" alt="Dish Image" class="w-100 h-100 object-fit-cover" />
                                        <?php } else { ?>
                                            <div class="d-flex align-items-center justify-content-center text-muted w-100 h-100">
                                                <i class="bx bx-image-alt fs-2"></i> No Image
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Dish Info -->
                                    <div class="card-body d-flex flex-column justify-content-between p-3">
                                        <div>
                                            <h6 class="fw-semibold text-dark mb-2 d-flex align-items-center">
                                                <?= htmlspecialchars($product['title']) ?>
                                                <span class="badge bg-warning text-dark ms-2 small"
                                                    style="font-weight: 600; padding: 0.3em 0.6em; border-radius: 0.4rem;">
                                                    Popular
                                                </span>
                                            </h6>
                                            <p class="text-muted small mb-3"><?= htmlspecialchars($product['slogan']) ?></p>
                                            <span
                                                class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-1 fs-6 fw-semibold">
                                                ₱<?= htmlspecialchars($product['price']) ?>
                                            </span>
                                        </div>

                                        <!-- Quantity + Add Button -->
                                        <div class="mt-4 d-flex gap-3 align-items-center">
                                            <input type="number" name="quantity" min="1" value="1"
                                                class="form-control form-control-sm quantity-input shadow-sm"
                                                style="max-width: 70px; border-radius: 0.5rem;" />
                                            <input type="hidden" name="dishes_id"
                                                value="<?= htmlspecialchars($product['d_id']) ?>" />
                                            <input type="hidden" name="user_id"
                                                value="<?= htmlspecialchars($_SESSION['user_id']) ?>" />
                                            <button type="submit" name="submit"
                                                class="btn btn-primary btn-sm d-flex align-items-center gap-2 flex-fill add-cart-btn shadow-sm"
                                                data-bs-toggle="tooltip" title="Add to Cart"
                                                style="border-radius: 0.5rem; font-weight: 600;">
                                                <i class="bx bx-cart fs-5"></i> Add
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

        <!-- FAQ Accordion -->
        <div class="accordion mt-5" id="faqAccordion">
            <?php
            $faqs = [
                ["How do I add multiple dishes?", "Simply adjust the quantity for each dish and click \"Add\". You can add multiple different dishes one by one."],
                ["Can I edit my cart later?", "Yes, go to your cart page to change quantities or remove items before checkout."],
                ["What payment methods do you accept?", "We currently accept cash on delivery, GCash, and Cash on Delivery. More options will be added soon!"],
                ["Can I schedule my order?", "Yes, you can specify a preferred delivery time during checkout. Make sure the restaurant supports scheduling."],
                ["What if a dish is out of stock?", "If a dish becomes unavailable after placing an order, our team will contact you to suggest an alternative or refund."]
            ];
            foreach ($faqs as $i => $faq) {
                $faqId = "collapse" . ($i + 1);
                $headerId = "faq" . ($i + 1);
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="<?= $headerId ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#<?= $faqId ?>" aria-expanded="false" aria-controls="<?= $faqId ?>">
                            <?= htmlspecialchars($faq[0]) ?>
                        </button>
                    </h2>
                    <div id="<?= $faqId ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <?= htmlspecialchars($faq[1]) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
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
        border-radius: 0.5rem;
        font-size: 0.9rem;
    }

    .add-cart-btn {
        font-weight: 600;
        background-color: #0d6efd;
        color: #fff;
        transition: all 0.2s ease-in-out;
        border-radius: 0.5rem;
        justify-content: center;
    }

    .add-cart-btn:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }

    .accordion-button {
        font-weight: 600;
    }

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

<script>
    // Bootstrap tooltips
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include 'layouts/footer.php'; ?>