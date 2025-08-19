<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['submit'])) {
    // Check if the category name is empty
    if (empty($_POST['c_name'])) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Field is required!'
        ];
    } else {
        // Sanitize the category name input
        $c_name = mysqli_real_escape_string($db, $_POST['c_name']);
        
        // Check if category already exists
        $check_cat = mysqli_query($db, "SELECT c_name FROM res_category WHERE c_name = '$c_name'");

        if (mysqli_num_rows($check_cat) > 0) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Category already exists!'
            ];
        } else {
            // Insert new category into the database
            $mql = "INSERT INTO res_category(c_name) VALUES('$c_name')";
            if (mysqli_query($db, $mql)) {
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'New category added successfully.'
                ];
            } else {
                $_SESSION['message'] = [
                    'type' => 'danger',
                    'text' => 'Failed to add new category. Please try again.'
                ];
            }
        }
    }

    // Redirect to refresh the page and prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>
<?php include 'layouts/sweetalert.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                       
                        <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                    </ol>
                </nav>
            </div>
        </div>


        <?php include 'layouts/alerts.php' ?>

        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Categories</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $sql = "SELECT * FROM res_category ORDER BY c_id DESC";
                                        $query = mysqli_query($db, $sql);

                                        if (mysqli_num_rows($query) <= 0) {
                                            echo '<tr><td colspan="7"><center>No Categories-Data!</center></td></tr>';
                                        } else {
                                            while ($rows = mysqli_fetch_array($query)) {

                                                switch ($rows['status']) {
                                                    case 0:
                                                        $statusText = 'Active';
                                                        $badgeClass = 'bg-success';
                                                        break;
                                                    case 1:
                                                        $statusText = 'Inactive';
                                                        $badgeClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $statusText = 'Unknown';
                                                        $badgeClass = 'bg-secondary';
                                                        break;
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($rows['c_id']) ?></td>
                                                    <td><?= htmlspecialchars($rows['c_name']) ?></td>
                                                    <td>
                                                        <span class="badge <?= $badgeClass ?>">
                                                            <?= $statusText ?>
                                                        </span>
                                                    </td>
                                                    <td><?= date('F j, Y', strtotime($rows['date'])) ?></td>
                                                    <td>
                                                        <a href="delete_category.php?cat_del=<?= urlencode($rows['c_id']) ?>"
                                                            class="btn btn-danger btn-flat btn-addon btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this category?');">
                                                            <i class="bx bx-trash"></i>
                                                        </a>

                                                        <a href="update_category.php?c_id=<?= urlencode($rows['c_id']) ?>"
                                                            class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Add Stall Category</h5>
                        </div>

                        <div class="widget card-body shadow-sm">

                            <div class="widget-body">
                                <form action='' method='POST'>
                                    <div class="form-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Category</label>
                                                    <input type="text" name="c_name" class="form-control" required>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="form-actions my-2">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Save">
                                            <a href="add_category.php" class="btn btn-danger">Cancel</a>
                                        </div>
                                </form>
                            </div>
                        </div>




                    </div>

                    </>

                </div>

            </div>

            <?php include 'layouts/footer.php' ?>