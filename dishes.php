<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['res_id'])) {
    $_SESSION['dishedId'] = $_GET['res_id'];
}

$dishesId = $_SESSION['dishedId'] ?? "null";

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

<div class="top-links 
bg-light border-bottom">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                <div class="link-item active">
                    <i class='bx bx-store-alt fs-3 mb-1'></i>
                    <a href="#" class="d-block text-decoration-none text-dark">Choose Stall</a>
                </div>
            </div>
            <div class="col-12 col-sm-4 mb-2 mb-sm-0 ">
                <div class="link-item">
                    <i class='bx bx-food-menu fs-3 mb-1 text-primary'></i>
                    <a href="#" class="d-block text-decoration-none text-dark">Pick Your Favorite Food</a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="link-item">
                    <i class='bx bx-cart fs-3 mb-1'></i>
                    <a href="#" class="d-block text-decoration-none text-dark">Order and Pay


                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="inner-page-hero position-relative" style="background-image: url('images/img/rest.png'); background-size: cover; background-position: center; min-height: 340px;">

    <!-- Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.55); z-index: 1;"></div>

    <?php
    $ress = mysqli_query($index->con, "SELECT * FROM restaurant WHERE rs_id='{$_GET['res_id']}'");
    $rows = mysqli_fetch_array($ress);
    ?>

    <div class="profile position-relative text-white" style="z-index: 2;">
        <div class="container py-5">
            <div class="row align-items-center g-4">

                <!-- Restaurant Image -->
                <div class="col-sm-4 text-center">
                    <div style="
                        background-image: url('admin/<?= htmlspecialchars($rows['image']) ?>');
                        background-size: cover;
                        background-position: center;
                        width: 220px;
                        height: 220px;
                        margin: 0 auto;
                        border-radius: 12px;
                        border: 3px solid #fff;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
                    </div>
                </div>

                <?php
                $restaurant_id = htmlspecialchars($rows['rs_id']);

                // Fetch average rating and review count
                $rating_query = mysqli_query($index->con, "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM restaurant_ratings WHERE restaurant_id = '$restaurant_id'");
                $rating_data = mysqli_fetch_assoc($rating_query);

                $avg_rating = number_format($rating_data['avg_rating'], 1); // Format to 1 decimal place
                $total_reviews = $rating_data['total_reviews'];
                ?>
                <?php
                function expandDayRange($range)
                {
                    $daysMap = [
                        'mon' => 'Monday',
                        'tue' => 'Tuesday',
                        'wed' => 'Wednesday',
                        'thu' => 'Thursday',
                        'thur' => 'Thursday',
                        'fri' => 'Friday',
                        'sat' => 'Saturday',
                        'sun' => 'Sunday'
                    ];

                    $parts = explode('-', strtolower($range));
                    $start = $daysMap[trim($parts[0])] ?? ucfirst($parts[0]);
                    $end = isset($parts[1]) ? ($daysMap[trim($parts[1])] ?? ucfirst($parts[1])) : '';

                    return $end ? "$start – $end" : $start;
                }

                $openDaysFormatted = expandDayRange($rows['o_days']);
                ?>

                <div class="col-sm-8">
                    <h2 class="fw-bold mb-2 text-white"><?= htmlspecialchars($rows['title']) ?></h2>

                    <p class="mb-2"><i class='bx bx-map me-2'></i><?= htmlspecialchars($rows['address'] ?? 'No address available') ?></p>

                    <p class="mb-2"><i class='bx bx-time-five me-2'></i><strong>Open Hours:</strong> <?= htmlspecialchars($rows['o_hr']) ?> - <?= htmlspecialchars($rows['c_hr']) ?></p>
                    <p class="mb-2">
                        <i class='bx bx-calendar me-2'></i>
                        <strong>Open Days:</strong>
                        <?= htmlspecialchars($openDaysFormatted) ?>
                    </p>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <i class='bx bxs-star text-warning me-2'></i>
                                <strong>Rating:</strong> <?= $avg_rating ?> (<?= $total_reviews ?> reviews)
                            </p>
                            <!-- <p class="mb-1">
                                <i class='bx bx-food-menu me-2'></i>
                                <strong>Cuisine:</strong> Filipino, Street Food
                            </p> -->
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1"><i class='bx bx-timer me-2'></i><strong>Delivery Time:</strong> 25-35 mins</p>
                            <p class="mb-1"><i class='bx bx-cart me-2'></i><strong>Min Order:</strong> ₱50.00</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="#menu" class="btn btn-primary px-4 rounded-pill">
                            <i class='bx bx-restaurant me-2'></i>Browse Dishes
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<section class="featured-dishes py-5 bg-light" id="menu">
    <div class="container">


        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">
                <i class='bx bxs-bowl-hot text-danger align-middle'></i> Available Dishes
            </h2>
            <p class="text-muted fs-5">Browse through our freshly prepared meals and delights</p>
        </div>

        <!-- Filter Card -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                    <div class="d-flex align-items-center mb-2">
                        <i class='bx bx-filter-alt fs-4 text-primary me-2'></i>
                        <h5 class="mb-0 fw-semibold">Filter by Category</h5>
                    </div>
                    <select name="dish_category_id" id="dishCategorySelect" class="form-select mt-2 shadow-sm">
                        <option value="">-- Select a Category --</option>
                        <?php
                        $query = "SELECT id, category FROM dish_category ORDER BY category ASC";
                        $result = mysqli_query($db, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>" . htmlspecialchars($row['category']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Dish Cards -->
        <div class="row g-4 justify-content-center" id="dishContainer">
            <?php
            $cat_id = $_POST['cat_id'] ?? null;
            $res_id = $dishesId;

            if (!$res_id) {
                echo "<div class='text-center text-danger'>Restaurant ID missing.</div>";
                exit;
            }

            $query = "SELECT * FROM dishes WHERE status = 0 AND rs_id = ?";
            $params = [$res_id];
            $types = "i";

            if ($cat_id) {
                $query .= " AND dish_category_id = ?";
                $types .= "i";
                $params[] = $cat_id;
            }

            $stmt = $db->prepare($query);
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
                        <div class="card shadow-sm h-100 border-0 dish-card position-relative">
                            <!-- Badge (optional enhancement) -->
                            <span class="badge bg-success position-absolute top-0 start-0 m-2">Popular</span>

                            <!-- Image -->
                            <div class="card-img-top" style="
                                background-image: url('admin/Res_img/<?= $dishImage ?>');
                                background-size: cover;
                                background-position: center;
                                height: 160px;
                                border-top-left-radius: .5rem;
                                border-top-right-radius: .5rem;">
                            </div>

                            <!-- Body -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1"><i class='bx bxs-bowl-rice text-warning'></i> <?= $dishTitle ?></h5>
                                <p class="card-text text-muted small mb-2"><i class='bx bx-info-circle'></i> <?= $dishSlogan ?></p>
                                <p class="card-text text-success fw-bold fs-5 mb-3"><i class='bx bx-money'></i> ₱<?= $dishPrice ?></p>

                                <!-- Add to Cart -->
                                <form method="POST" action="" class="mt-auto">
                                    <div class="d-flex align-items-center">
                                        <input class="form-control form-control-sm me-2" type="number" name="quantity" value="1" min="1" style="width: 80px;">
                                        <input type="hidden" name="dishes_id" value="<?= $dishId ?>">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id'] ?? 0) ?>">
                                        <button type="submit" name="submit" class="btn btn-sm btn-outline-primary">
                                            <i class='bx bx-cart-alt'></i> Add
                                        </button>
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
        </div>
    </div>
</section>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#dishCategorySelect').on('change', function() {
        var catId = $(this).val();
        var resId = <?= json_encode($_GET['res_id']) ?>;

        $.ajax({
            url: 'fetch_dish.php',
            type: 'POST',
            data: {
                cat_id: catId,
                res_id: resId
            },
            beforeSend: function() {
                $('#dishContainer').html('<div class="text-center">Loading dishes...</div>');
            },
            success: function(response) {
                $('#dishContainer').html(response);
            },
            error: function() {
                $('#dishContainer').html('<div class="text-danger">Failed to load dishes.</div>');
            }
        });
    });
</script>

<?php include 'layouts/sweetalert.php'; ?>



<?php include 'layouts/footer.php'; ?>

<style>
    .card {
        max-width: 90%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dish-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        transition: transform 0.3s ease;
    }

    .dish-card:hover .card-body {
        transform: scale(1.05);
    }
</style>