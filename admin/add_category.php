<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();


if (isset($_POST['submit'])) {
    if (empty($_POST['c_name'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>field Required!</strong>
															</div>';
    } else {

        $check_cat = mysqli_query($db, "SELECT c_name FROM res_category where c_name = '" . $_POST['c_name'] . "' ");



        if (mysqli_num_rows($check_cat) > 0) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Category already exist!</strong>
															</div>';
        } else {


            $mql = "INSERT INTO res_category(c_name) VALUES('" . $_POST['c_name'] . "')";
            mysqli_query($db, $mql);
            $success = '<div class="alert alert-success alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																New Category Added Successfully.</br></div>';
        }
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
                        <li class="breadcrumb-item active" aria-current="page">Rider Details</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="add_user.php" class="btn btn-primary">Add User</a>
        </div>




        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Categories</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover"
                                    id="datatable">
                                    <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Date</th>

                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $sql = "SELECT * FROM res_category ORDER BY c_id DESC";
                                $query = mysqli_query($db, $sql);

                                if (!mysqli_num_rows($query) > 0) {
                                    echo '<td colspan="7"><center>No Categories-Data!</center></td>';
                                } else {
                                    while ($rows = mysqli_fetch_array($query)) {
                                        echo ' 
        <tr>
            <td>' . $rows['c_id'] . '</td>
            <td>' . $rows['c_name'] . '</td>
            <td>' . date('F j, Y', strtotime($rows['date'])) . '</td> <!-- Displaying full date in format: Month Day, Year -->
            <td>
                <a href="delete_category.php?cat_del=' . $rows['c_id'] . '" class="btn btn-danger btn-flat btn-addon btn-sm">
                    <i class="bx bx-trash"></i>
                </a>
                <a href="update_category.php?cat_upd=' . $rows['c_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
  <i class="bx bx-edit"></i>
                </a>
            </td>
        </tr>';
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
                <form action='' method='post'>
                    <div class="form-body">

                        <div class="row p-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Category</label>
                                    <input type="text" name="c_name" class="form-control">
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