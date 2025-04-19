<?php
include("../connection/connect.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_POST['submit'])) {
    // Form validation
    if (empty($_POST['d_name']) || empty($_POST['about']) || empty($_POST['price']) || empty($_POST['res_name'])) {
        $error = showAlert('danger', 'All fields must be filled!');
    } else {
        // File upload handling
        $file = $_FILES['file'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = uniqid() . '.' . $fileExt;
        $filePath = "../admin/Res_img/dishes/" . basename($fileName);

        // Validate file extension and size
        if (!in_array($fileExt, ['jpg', 'png', 'gif'])) {
            $error = showAlert('danger', 'Invalid file extension! Only jpg, png, and gif are accepted.');
        } elseif ($file['size'] >= 1000000) {
            $error = showAlert('danger', 'Max image size is 1024kb! Try a different image.');
        } else {
            // Sanitize input
            $d_name = mysqli_real_escape_string($db, $_POST['d_name']);
            $about = mysqli_real_escape_string($db, $_POST['about']);
            $price = mysqli_real_escape_string($db, $_POST['price']);
            $res_name = mysqli_real_escape_string($db, $_POST['res_name']);

            // Insert dish data into the database
            $sql = "INSERT INTO dishes (rs_id, title, slogan, price, img) VALUES ('$res_name', '$d_name', '$about', '$price', '$fileName')";
            if (mysqli_query($db, $sql)) {
                move_uploaded_file($file['tmp_name'], $filePath);
                $success = showAlert('success', 'New dish added successfully.');
            } else {
                $error = showAlert('danger', 'Failed to add dish.');
            }
        }
    }
}

// Function to generate alert messages
function showAlert($type, $message) {
    return "<div class='alert alert-$type alert-dismissible fade show'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>$message</strong>
            </div>";
}
?>

<?php include '../admin/layouts/header.php'; ?>
<?php include '../layouts/stall/sidebar.php'; ?>
<?php include '../layouts/stall/navbar.php'; ?>

<div id="main">
    <div class="main-container">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register Menu</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="d-flex justify-content-end my-2">
            <a href="menus.php" class="btn btn-primary">Back</a>
        </div>

        <?php include '../layouts/alert.php'; ?>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Register Menu</h5>
                    </div>

                    <div class="widget card-body shadow-sm">
                        <form action='' method='post' enctype="multipart/form-data">
                            <div class="row p-t-20">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Dish Name</label>
                                        <input type="text" name="d_name" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Description</label>
                                        <input type="text" name="about" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row p-t-20">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Price</label>
                                        <input type="text" name="price" class="form-control" placeholder="₱" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Image</label>
                                        <input type="file" name="file" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Select Stall</label>
                                        <select name="res_name" class="form-control custom-select" required>
                                            <option>--Select Stall--</option>
                                            <?php
                                            $ssql = "SELECT * FROM restaurant";
                                            $res = mysqli_query($db, $ssql);
                                            while ($row = mysqli_fetch_array($res)) {
                                                echo '<option value="' . $row['rs_id'] . '">' . $row['title'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions my-2">
                                <input type="submit" name="submit" class="mr-2 btn btn-primary" value="Save">
                                <a href="all_menu.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../admin/layouts/footer.php'; ?>
