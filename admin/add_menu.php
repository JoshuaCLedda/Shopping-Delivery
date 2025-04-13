<?php
include("../connection/connect.php");
error_reporting(0);
session_start();


if (isset($_POST['submit'])) {
    if (empty($_POST['d_name']) || empty($_POST['about']) || $_POST['price'] == '' || $_POST['res_name'] == '') {
        $error =     '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>All fields Must be Fillup!</strong>
															</div>';
    } else {

        $fname = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
        $fsize = $_FILES['file']['size'];
        $extension = explode('.', $fname);
        $extension = strtolower(end($extension));
        $fnew = uniqid() . '.' . $extension;

        $store = "Res_img/dishes/" . basename($fnew);

        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
            if ($fsize >= 1000000) {


                $error =     '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Max Image Size is 1024kb!</strong> Try different Image.
															</div>';
            } else {




                $sql = "INSERT INTO dishes(rs_id,title,slogan,price,img) VALUE('" . $_POST['res_name'] . "','" . $_POST['d_name'] . "','" . $_POST['about'] . "','" . $_POST['price'] . "','" . $fnew . "')";  // store the submited data ino the database :images
                mysqli_query($db, $sql);
                move_uploaded_file($temp, $store);

                $success =     '<div class="alert alert-success alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																 New Dish Added Successfully.
															</div>';
            }
        } elseif ($extension == '') {
            $error =     '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>select image</strong>
															</div>';
        } else {

            $error =     '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>invalid extension!</strong>png, jpg, Gif are accepted.
															</div>';
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
                        <li class="breadcrumb-item active" aria-current="page">Register Menu</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="all_menu.php" class="btn btn-primary">Back</a>
        </div>



            <?php include 'layouts/alert.php'; ?>


            <div class="row justify-content-center">
                <div class="col-md-12">
          <div class="card card-outline-primary">
                    
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Register Menu</h5>
                    </div>

                    <div class="widget card-body shadow-sm">

                        <div class="widget-body">
                        
                        <form action='' method='post' enctype="multipart/form-data">
                     

                                    <div class="row p-t-20">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Dish Name</label>
                                                <input type="text" name="d_name" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Description</label>
                                                <input type="text" name="about" class="form-control form-control-danger">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Price </label>
                                                <input type="text" name="price" class="form-control" placeholder="₱">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image</label>
                                                <input type="file" name="file" id="lastName" class="form-control form-control-danger" placeholder="12n">
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Select Stall</label>
                                                <select name="res_name" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                    <option>--Select Stall--</option>
                                                    <?php $ssql = "select * from restaurant";
                                                    $res = mysqli_query($db, $ssql);
                                                    while ($row = mysqli_fetch_array($res)) {
                                                        echo ' <option value="' . $row['rs_id'] . '">' . $row['title'] . '</option>';;
                                                    }

                                                    ?>
                                                </select>
                                            </div>
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
    </div>

    <?php include 'layouts/footer.php' ?>