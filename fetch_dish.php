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
                <div class="card-img-top" style="background-image: url('admin/Res_img/<?= $dishImage ?>');
                        background-size: cover;
                        background-position: center;
                        height: 150px;
                        border-top-left-radius: .5rem;
                        border-top-right-radius: .5rem;">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1"><?= $dishTitle ?></h5>
                    <p class="card-text text-muted small mb-1"><?= $dishSlogan ?></p>
                    <p class="card-text text-success fw-bold fs-5 mb-3">₱<?= $dishPrice ?></p>

                    <form method="POST" action="" class="mt-auto">
                        <div class="d-flex align-items-center">
                            <input class="form-control form-control-sm me-2" type="number" name="quantity" value="1" min="1" style="width: 80px;">
                            <input type="hidden" name="dishes_id" value="<?= $dishId ?>">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id'] ?? 0) ?>">
                            <button type="submit" name="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                        </div>
                    </form>
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
