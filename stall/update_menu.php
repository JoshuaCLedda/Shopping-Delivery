<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../login.php");
    exit();
}

include "../admin/Main.php";
$index = new Index;

if (isset($_GET['menu_upd'])) {
    $_SESSION['dishedId'] = $_GET['menu_upd'];
}

$dishesiD = $_SESSION['dishedId'] ?? null;

// backend
if (isset($_POST['submit'])) {
    $dishes_Id = $_POST['dishes_id'];
    $title = $_POST['title'];
    $status = $_POST['status'];

    $slogan = $_POST['slogan'];
    $price = $_POST['price'];
    $available_quantity = $_POST['available_quantity'];
    $dish_category_id = $_POST['dish_category_id'];
    $image = $_FILES['image'] ?? '';

    // Call the model function with image path
    $result = $index->updateStallMenu(
        $dishes_Id,
        $title,
        $status,
        $slogan,
        $price,
        $available_quantity,
        $dish_category_id,
        $image

    );


    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Restaurant Registered Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to Register. Email Might Already Exist.'];
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header("Location: menus.php");
        exit();
    }
}

?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Stall</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Menu
                          
                        </li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="placed_orders.php" class="btn btn-primary">Back
            </a>
        </div>

        <?php include '../layouts/sweetalert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Update Menu</h5>
                    </div>


                    <div class="widget card-body shadow-sm">
                        <div class="widget-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php
                                $qml = "SELECT * FROM dishes WHERE d_id='{$_GET['menu_upd']}'";
                                $rest = mysqli_query($index->con, $qml);
                                $row = mysqli_fetch_array($rest);
                                $selected_restaurant_id = $_SESSION['restaurant_id'] ?? '';
                                $rs_id = $row['res_id'] ?? ''; // Get the restaurant ID from the dish
                                ?>

                                <input type="hidden" name="dishes_id" value="<?= htmlspecialchars($row['d_id']) ?>">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Dish Name</label>
                                        <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>"
                                            class="form-control" placeholder="Morzirella">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Slogan</label>
                                        <input type="text" name="slogan" value="<?= htmlspecialchars($row['slogan']) ?>"
                                            class="form-control" placeholder="Slogan">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Price</label>
                                        <input type="text" name="price" value="<?= htmlspecialchars($row['price']) ?>"
                                            class="form-control" placeholder="₱">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Available Quantity</label>
                                        <input type="number" name="available_quantity"
                                            value="<?= htmlspecialchars($row['available_quantity']) ?>"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Menu Category</label>
                                        <select name="dish_category_id" class="form-control">
                                            <option value="">--Select Category--</option>
                                            <?php
                                            $currentDishCategory = $row['dish_category_id'];
                                            $ssql = "SELECT * FROM dish_category";
                                            $res = mysqli_query($index->con, $ssql);
                                            while ($catRow = mysqli_fetch_array($res)) {
                                                $selected = ($catRow['id'] == $currentDishCategory) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($catRow['id']) . "' $selected>" . htmlspecialchars($catRow['category']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="col-md-6">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
        <option value="">--Select Status--</option>
        <option value="0" <?= ($row['status'] == '0') ? 'selected' : '' ?>>Active</option>
        <option value="1" <?= ($row['status'] == '1') ? 'selected' : '' ?>>Inactive</option>
    </select>
</div>


                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Menu Image</label>
                                            <?php
                                            $imagePath = '../admin/Res_img/' . $row['img'];
                                            if (!empty($row['img']) && file_exists($imagePath)): ?>
                                                <input type="file" name="image" class="form-control" disabled>
                                                <div class="mt-2 d-flex align-items-center gap-3">
                                                    <img src="<?= $imagePath ?>" alt="Image" class="img-thumbnail"
                                                        style="max-width: 100px;">
                                                    <button type="button" class="btn btn-sm btn-danger" id="deleteImageBtn"
                                                        data-rs-id="<?= htmlspecialchars($rs_id) ?>">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <input type="file" name="image" class="form-control">
                                                <small class="text-danger mt-2">No image uploaded.</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-start gap-2">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Save">
                                    <a href="all_menu.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include '../layouts/footer.php' ?>