<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;

if (isset($_GET['menu_upd'])) {
    $_SESSION['dishedId'] = $_GET['menu_upd'];
}

$dishesiD = $_SESSION['dishedId'] ?? null;

// backend
if (isset($_POST['dishedId'])) {
    $dishes_Id= $_FILES['dishesId'];
    $title = $_POST['title'];
    $slogan = $_POST['slogan'];
    $price = $_POST['price'];
    $available_quantity = $_POST['available_quantity'];
    $dish_category_id = $_POST['dish_category_id'];
    $rs_id = $_POST['rs_id'];
    $image = $_FILES['image'] ?? '';

    // Call the model function with image path
    $result = $index->updateMenu(
        $dishes_Id,
        $title,
        $slogan,
        $price,
        $available_quantity,
        $dish_category_id,
        $rs_id,
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
        header("Location: add_restaurant.php"); // fallback if no referrer
        exit();
    }
}

?>

<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register User</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="all_menu.php" class="btn btn-primary">Back

            </a>
        </div>

        <?php include 'layouts/alert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Update Menu</h5>
                    </div>


                    <div class="widget card-body shadow-sm">
                        <div class="widget-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                           

                           <?php
                                    $qml = "SELECT * FROM dishes WHERE d_id='$_GET[menu_upd]'";
                                    $rest = mysqli_query($index->con, $qml);
                                    $row = mysqli_fetch_array($rest);
                                    ?>

                                <input type="hidden" name="dishes_id" value="<?= htmlspecialchars($row['d_id']) ?>">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Dish Name</label>
                                                <input type="text" name="title"
                                                    value="<?php echo htmlspecialchars($row['title']); ?>"
                                                    class="form-control" placeholder="Morzirella">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Slogan</label>
                                                <input type="text" name="slogan"
                                                    value="<?php echo htmlspecialchars($row['slogan']); ?>"
                                                    class="form-control form-control-danger" placeholder="Slogan">
                                            </div>
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input type="text" name="price"
                                                    value="<?php echo htmlspecialchars($row['price']); ?>"
                                                    class="form-control" placeholder="₱">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Availble Quantity</label>
                                                <input type="text" name="available_quantity"
                                                    value="<?php echo htmlspecialchars($row['available_quantity']); ?>"
                                                    class="form-control" >
                                            </div>
                                        </div>






                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Menu Category</label>
                                                <select name="dish_category_id" class="form-control custom-select"
                                                    data-placeholder="Choose a Category" tabindex="1">
                                                    <option value="">--Select Category--</option>
                                                    <?php
                                                    $currentDishCategory = $row['dish_category_id']; // This is from your current dish
                                                    $ssql = "SELECT * FROM dish_category";
                                                    $res = mysqli_query($index->con, $ssql);

                                                    // Loop through the categories and set the selected attribute
                                                    while ($catRow = mysqli_fetch_array($res)) {
                                                        // Check if the current category is the one for this dish
                                                        $selected = ($catRow['id'] == $currentDishCategory) ? 'selected' : '';
                                                        echo '<option value="' . htmlspecialchars($catRow['id']) . '" ' . $selected . '>' . htmlspecialchars($catRow['category']) . '</option>';
                                                    }
                                                    ?>
                                                </select>

                                            </div>

                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Select Stall</label>
                                                <select name="rs_id" class="form-control custom-select"
                                                    data-placeholder="Choose a Category" tabindex="1">
                                                    <option value="">--Select Category--</option>
                                                    <?php
                                                    $currentRestau = $row['rs_id']; // This is from your current dish
                                                    $ssql = "SELECT * FROM restaurant";
                                                    $res = mysqli_query($index->con, $ssql);

                                                    // Loop through the categories and set the selected attribute
                                                    while ($catRow = mysqli_fetch_array($res)) {
                                                        // Check if the current category is the one for this dish
                                                        $selected = ($catRow['rs_id'] == $currentRestau) ? 'selected' : '';
                                                        echo '<option value="' . htmlspecialchars($catRow['rs_id']) . '" ' . $selected . '>' . htmlspecialchars($catRow['title']) . '</option>';
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>



                                        <div class="col-md-6">

                                            <?php if (!empty($row['img']) && file_exists('Res_img/' . $row['img'])): ?>
                                                <div>
                                                    <label class="control-label">Menu Image</label>
                                                    <input type="file" name="image" class="form-control" disabled>
                                                </div>
                                                <div class="mt-2" id="image-preview">
                                                    <img src="Res_img/<?= htmlspecialchars($row['img']) ?>"
                                                        alt="Restaurant Image" class="img-fluid"
                                                        style="max-width: 100px; max-height: 100px;">
                                                    <button type="button" class="btn btn-sm btn-danger" id="deleteImageBtn"
                                                        data-rs-id="<?= $rs_id ?>">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <div class="mt-2 text-muted">
                                                    <label class="control-label">Menu Image</label>
                                                    <input type="file" name="image" class="form-control">
                                                    <p class="text-danger my-3">No Image Data</p>
                                                </div>
                                            <?php endif; ?>


                                        </div>
                                    </div>
                                    <div class="form-actions my-3">
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


<?php include 'layouts/footer.php' ?>