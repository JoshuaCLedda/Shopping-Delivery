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
<header class="shadow sticky-top bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold text-white" href="index.php">
                <i class='bx bx-store'></i> FoodStall
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                            <i class='bx bx-home-alt'></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'restaurants.php' ? 'active' : '' ?>" href="restaurants.php">
                            <i class='bx bx-restaurant'></i> Stalls
                        </a>
                    </li>

                    <?php if (empty($_SESSION["user_id"])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="login.php">
                                <i class='bx bx-log-in'></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="registration.php">
                                <i class='bx bx-user-plus'></i> Register
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="your_orders.php">
                                <i class='bx bx-receipt'></i> My Orders
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link text-white" href="carts.php">
                                <i class='bx bx-cart'></i> My Cart
                                <?php if (!empty($cartCount) && $cartCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?= $cartCount ?>
                                        <span class="visually-hidden">items in cart</span>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="logout.php">
                                <i class='bx bx-log-out'></i> Logout
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>
</header>

