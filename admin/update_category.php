<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;

if (isset($_GET['c_id'])) {
    $c_id = $_GET['c_id'];

    // Ensure c_id is numeric
    if (is_numeric($c_id)) {
        $result = $index->viewCategoryDetails($c_id);

        if ($row = mysqli_fetch_object($result)) {
            $c_id = $row->c_id;
            $c_name = $row->c_name;
            $status = $row->status;

        } else {
            die("Category not found.");
        }
    } else {
        die("Invalid category ID.");
    }
} else {
    die("No category ID provided.");
}

if (isset($_POST['submit'])) {
    $c_name = $_POST['c_name'];
    $status = $_POST['status'];

    $result = $index->updateCategory($c_id, $c_name, $status);

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Category Updated Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Update failed! Please try again.'];
    }

    // Redirect back to the previous page (if referrer is available)
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header('Location: add_category.php');
        exit();
    }
}
?>

<?php include 'layouts/header.php'; ?>
<?php include 'layouts/sidebar.php'; ?>
<?php include 'layouts/navbar.php'; ?>

<div id="main">
    <div class="main-container">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Category</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="d-flex justify-content-end my-2">
            <a href="add_category.php" class="btn btn-primary">Back</a>
        </div>

        <?php include 'layouts/alert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Category Details</h5>
                    </div>



                    <div class="widget card-body shadow-sm">
                        <div class="widget-body">
                            <form action='' method='post'>
                                <div class="form-body">
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Category</label>
                                                <input type="text" name="c_name"
                                                    value="<?= htmlspecialchars($c_name); ?>" class="form-control"
                                                    placeholder="Category Name">
                                            </div>
                                        </div>

                                  

                                        <div class="form-group col-sm-6 mb-3">
                                        <label class="control-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="0" <?= ($status == '0') ? 'selected' : ''; ?>>Active</option>
                                            <option value="1" <?= ($status == '1') ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>


                                    </div>
                                    <div class="form-actions mt-4">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save">
                                        <a href="add_category.php" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'layouts/footer.php'; ?>