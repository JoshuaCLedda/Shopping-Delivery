<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourfontawesomekit.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .cart-card {
            background: #f8f9fa;
            /* light gray background */
            border-radius: 1rem;
            /* rounded corners */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            /* softer shadow */
            transition: all 0.3s ease;
            /* smooth animation */
        }

        .cart-card:hover {
            transform: translateY(-5px);
            /* lift up */
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            /* deeper shadow on hover */
        }

        .cart-badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 50rem;
            /* pill shape */
        }
    </style>

</head>

<?php include 'layouts/navbar.php'; ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <?php echo $_SESSION['message']['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <h3 class="mb-4 fw-bold"><i class="fa-solid fa-cart-shopping me-2"></i>My Cart</h3>
            <a href="#" id="deleteSelectedBtn" class="btn btn-outline-danger btn-sm">
    <i class='bx bx-trash me-1'></i> Delete Selected
</a>


            <form action="checkout.php" method="POST">
                <?php
                $result = $index->getUserCart($user_id);
                if (mysqli_num_rows($result) > 0):

                    $cartByRestaurant = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $cartByRestaurant[$row['restaurantName']][] = $row;
                    }

                    foreach ($cartByRestaurant as $restaurantName => $items): ?>
                        <div class="mb-5">
                            <div class="bg-light p-3 rounded d-flex align-items-center mb-3 shadow-sm">
                                <i class="fa-solid fa-store text-warning me-2 fs-4"></i>
                                <h5 class="mb-0 text-dark fw-bold"><?= htmlspecialchars($restaurantName) ?></h5>
                            </div>

                            <?php foreach ($items as $item): ?>
                                <div class="card mb-3 shadow-sm border-0">
                                    <div class="card-body d-flex align-items-center gap-3">

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="selected_items[]" value="<?= $item['cartId']; ?>">
                                        </div>

                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($item['dishName']) ?></h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge bg-success">₱<?= number_format($item['totalPrice'], 2) ?></span>
                                                <span class="badge bg-secondary">Qty: <?= (int)$item['quantity'] ?></span>
                                            </div>
                                        </div>

                                        <div>
                                            <a href="delete_cart_item.php?cartId=<?= $item['cartId']; ?>" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Remove this item from your cart?');">
                                                <i class='bx bx-trash'></i><?= $item['cartId']; ?>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="text-center my-4">
                        <button type="submit" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow">
                            <i class="fa-solid fa-cart-arrow-down me-2"></i> Checkout Selected
                        </button>
                    </div>

                <?php else: ?>
                    <div class="text-center py-5">
                        <h6 class="text-muted">Your cart is empty 🛒</h6>
                    </div>
                <?php endif; ?>
            </form>

        </div>
    </div>
</div>

<script>
    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
        e.preventDefault();

        const form = document.querySelector('form');
        const checkboxes = form.querySelectorAll('input[name="selected_items[]"]:checked');

        if (checkboxes.length === 0) {
            alert('Please select at least one item to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete the selected items?')) {
            form.action = 'delete_selected_cart_items.php';
            form.submit();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>