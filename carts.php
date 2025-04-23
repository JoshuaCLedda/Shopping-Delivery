<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Ensure errors are displayed
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
    <script src="https://kit.fontawesome.com/yourfontawesomekit.js" crossorigin="anonymous"></script> <!-- Add your FA Kit -->
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

<body>

    <?php include 'layouts/navbar.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <?php echo $_SESSION['message']['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <h3 class="mb-4 fw-bold">🛒 My Cart</h3>

                <form action="checkout.php" method="POST">
                    <?php
                    $result = $index->getUserCart();
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                            <div class="card cart-card mb-3 border-0">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_items[]" value="<?php echo $row['cartId']; ?>">
                                    </div>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($row['dishName']); ?></h6>
                                        <span class="badge bg-primary cart-badge me-2">₱<?php echo number_format($row['totalPrice'], 2); ?></span>
                                        <span class="badge bg-secondary cart-badge">Qty: <?php echo (int)$row["quantity"]; ?></span>
                                    </div>

                                    <div>
                                        <a href="delete-cart-item.php?id=<?php echo $row['cartId']; ?>"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to remove this item from your cart?');">
                                            <i class='bx bx-trash'></i>
                                        </a>
                                    </div>


                                </div>
                            </div>

                        <?php endwhile; ?>

                        <div class="text-center my-4">
                            <button type="submit" class="btn btn-success px-5 py-2 rounded-pill fw-bold">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>