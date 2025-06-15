<?php
session_start();
include 'connection/connect.php';

$cat_id = $_POST['cat_id'] ?? null;
$res_id = $_POST['res_id'] ?? null;

if (!$res_id) {
    echo '<div class="col-12 text-danger text-center">Restaurant ID is missing.</div>';
    exit;
}

$query = "SELECT * FROM dishes WHERE status = 0 AND rs_id = ?";
$params = [$res_id];
$types = "i";

if (!empty($cat_id)) {
    $query .= " AND dish_category_id = ?";
    $types .= "i";
    $params[] = $cat_id;
}

$stmt = $db->prepare($query);
if (!$stmt) {
    echo '<div class="col-12 text-danger text-center">Query Error: ' . htmlspecialchars($db->error) . '</div>';
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($product = $result->fetch_assoc()):
        $dishId = htmlspecialchars($product['d_id']);
        $dishTitle = htmlspecialchars($product['title']);
        $dishSlogan = htmlspecialchars($product['slogan']);
        $dishPrice = htmlspecialchars($product['price']);
        $dishImage = htmlspecialchars($product['img']);
        $restaurantId = htmlspecialchars($product['rs_id']);
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm h-100 border-0 dish-card position-relative rounded-4">

                <!-- Badge -->
                <span class="badge bg-success position-absolute top-0 start-0 m-2">Popular</span>

                <!-- Image -->
                <div class="card-img-top" style="
                    background-image: url('admin/Res_img/<?= $dishImage ?>');
                    background-size: cover;
                    background-position: center;
                    height: 130px;
                    border-top-left-radius: .75rem;
                    border-top-right-radius: .75rem;">
                </div>

                <!-- Card Body -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1">
                        <i class='bx bxs-bowl-rice text-warning'></i> <?= $dishTitle ?>
                    </h5>
                    <p class="card-text text-muted small mb-2">
                        <i class='bx bx-info-circle'></i> <?= $dishSlogan ?>
                    </p>
                    <p class="card-text text-success fw-bold fs-5 mb-3">
                        <i class='bx bx-money'></i> ₱<?= $dishPrice ?>
                    </p>

                    <!-- Checkbox Selection -->
                    <div class="form-check mb-3 mt-auto">
                        <input class="form-check-input" type="checkbox" name="selected_dishes[]"
                            value="<?= $dishId ?>" id="dish<?= $dishId ?>">
                        <label class="form-check-label small" for="dish<?= $dishId ?>">
                            Add this dish
                        </label>
                    </div>

                    <!-- View Button -->
                    <a href="viewDishes.php?dish_id=<?= $dishId ?>&restaurant_id=<?= $restaurantId ?>"
                        class="btn btn-sm btn-outline-secondary">
                        View
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile;
else: ?>
    <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 300px;">
        <div class="text-center">
            <img src="assets/images/icons/emptycart.svg" alt="No Dishes" class="mb-4" style="width: 200px;">
            <h5 class="text-muted">No available dishes at the moment 🛒</h5>
        </div>
    </div>
<?php endif; ?>
