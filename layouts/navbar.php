<?php
include("connection/connect.php");
error_reporting(E_ALL);
$cartCount = 0;
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cartQuery = mysqli_query($db, "SELECT COUNT(*) as total FROM carts WHERE user_id = '$user_id'");
    if ($cartQuery) {
        $cartData = mysqli_fetch_assoc($cartQuery);
        $cartCount = $cartData['total'];
    }
}
?>

<header id="header" class="shadow-sm sticky-top bg-white">
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container">
            
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <i class="bx bx-user fs-3 me-2"></i> Shopping
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'restaurants.php' ? 'active' : ''; ?>" href="restaurants.php">Stalls</a>
                    </li>

                    <?php if (empty($_SESSION["user_id"])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registration.php">Register</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="your_orders.php">My Orders</a>
                        </li>
                        <li class="nav-item">
    <a class="nav-link position-relative" href="carts.php">
        My Cart
        <?php if ($cartCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $cartCount ?>
                <span class="visually-hidden">cart items</span>
            </span>
        <?php endif; ?>
    </a>
</li>

                        <li class="nav-item">
                            <a class="nav-link text-danger" href="logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>
</header>
