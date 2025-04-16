<?php
include("../connection/connect.php");
error_reporting(0);
session_start();




if (isset($_POST['submit'])) {







    if (empty($_POST['c_name']) || empty($_POST['res_name']) || $_POST['email'] == '' || $_POST['phone'] == '' || $_POST['url'] == '' || $_POST['o_hr'] == '' || $_POST['c_hr'] == '' || $_POST['o_days'] == '' || $_POST['address'] == '') {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
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

        $store = "Res_img/" . basename($fnew);

        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
            if ($fsize >= 5000000) {


                $error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Max Image Size is 1024kb!</strong> Try different Image.
															</div>';
            } else {


                $res_name = $_POST['res_name'];

                $sql = "update restaurant set c_id='$_POST[c_name]', title='$res_name',email='$_POST[email]',phone='$_POST[phone]',url='$_POST[url]',o_hr='$_POST[o_hr]',c_hr='$_POST[c_hr]',o_days='$_POST[o_days]',address='$_POST[address]',image='$fnew' where rs_id='$_GET[res_upd]' ";  // store the submited data ino the database :images												mysqli_query($db, $sql); 
                mysqli_query($db, $sql);
                move_uploaded_file($temp, $store);

                $success = '<div class="alert alert-success alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Record Updated!</strong>.
															</div>';
            }
        } elseif ($extension == '') {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>select image</strong>
															</div>';
        } else {

            $error = '<div class="alert alert-danger alert-dismissible fade show">
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
                        <li class="breadcrumb-item active" aria-current="page">Register User</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="all_restaurant.php" class="btn btn-primary">Back</a>
        </div>

        <?php include 'layouts/alert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Update Stall</h5>
                    </div>

                    <div class="widget card-body shadow-sm">

                        <div class="widget-body">

                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php $ssql = "select * from restaurant where rs_id='$_GET[res_upd]'";
                                    $res = mysqli_query($db, $ssql);
                                    $row = mysqli_fetch_array($res); ?>
                                    <div class="row p-t-20">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Stall Name</label>
                                                <input type="text" name="res_name" value="<?php echo $row['title']; ?>"
                                                    class="form-control" placeholder="John doe">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Bussiness E-mail</label>
                                                <input type="text" name="email" value="<?php echo $row['email']; ?>"
                                                    class="form-control form-control-danger"
                                                    placeholder="example@gmail.com">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Phone </label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="<?php echo $row['phone']; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                                <label class="control-label">website URL</label>
                                                <input type="text" name="url" class="form-control form-control-danger"
                                                    value="<?php echo $row['url']; ?>" placeholder="http://example.com">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Open Hours</label>
                                                <select name="o_hr" class="form-control custom-select"
                                                    data-placeholder="Choose a time">
                                                    <option value="7am" <?= ($row['o_hr'] == '7am') ? 'selected' : '' ?>>
                                                        7:00 am</option>
                                                    <option value="8am" <?= ($row['o_hr'] == '8am') ? 'selected' : '' ?>>
                                                        8:00 am</option>
                                                    <option value="9am" <?= ($row['o_hr'] == '9am') ? 'selected' : '' ?>>
                                                        9:00 am</option>
                                                    <option value="10am" <?= ($row['o_hr'] == '10am') ? 'selected' : '' ?>>
                                                        10:00 am</option>
                                                    <!-- Add more options if needed -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Close Hours</label>
                                                <select name="c_hr" class="form-control custom-select"
                                                    data-placeholder="Choose a time">
                                                    <option value="6pm" <?= ($row['c_hr'] == '6pm') ? 'selected' : '' ?>>
                                                        6:00 pm</option>
                                                    <option value="7pm" <?= ($row['c_hr'] == '7pm') ? 'selected' : '' ?>>
                                                        7:00 pm</option>
                                                    <option value="8pm" <?= ($row['c_hr'] == '8pm') ? 'selected' : '' ?>>
                                                        8:00 pm</option>
                                                    <option value="9pm" <?= ($row['c_hr'] == '9pm') ? 'selected' : '' ?>>
                                                        9:00 pm</option>
                                                    <!-- Add more if needed -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Open Days</label>
                                                <select name="o_days" class="form-control custom-select"
                                                    data-placeholder="Choose a Category" tabindex="1" required>
                                                    <option value="">--Select your Days--</option>
                                                    <option value="Mon-Tue" <?= ($row['o_days'] == 'Mon-Tue') ? 'selected' : '' ?>>Mon-Tue</option>
                                                    <option value="Mon-Wed" <?= ($row['o_days'] == 'Mon-Wed') ? 'selected' : '' ?>>Mon-Wed</option>
                                                    <option value="Mon-Thu" <?= ($row['o_days'] == 'Mon-Thu') ? 'selected' : '' ?>>Mon-Thu</option>
                                                    <option value="Mon-Fri" <?= ($row['o_days'] == 'Mon-Fri') ? 'selected' : '' ?>>Mon-Fri</option>
                                                    <option value="Mon-Sat" <?= ($row['o_days'] == 'Mon-Sat') ? 'selected' : '' ?>>Mon-Sat</option>
                                                    <option value="24hr-x7" <?= ($row['o_days'] == '24hr-x7') ? 'selected' : '' ?>>24hr-x7</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Select Category  </label>
                                                <select name="c_name" class="form-control custom-select"
                                                    data-placeholder="Choose a Category" tabindex="1" required>
                                                    <option value="">--Select Category--</option>
                                                    <?php
                                                    $ssql = "SELECT * FROM res_category";
                                                    $res = mysqli_query($db, $ssql);
                                                    while ($cat = mysqli_fetch_array($res)) {
                                                        $selected = (isset($row['c_id']) && $row['c_id'] == $cat['c_id']) ? 'selected' : '';
                                                        echo '<option value="' . $cat['c_id'] . '" ' . $selected . '>' . $cat['c_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image</label>
                                                <input type="file" name="file" id="lastName"
                                                    class="form-control form-control-danger">

                                                <?php
                                                $imagePath = '' . $row['image'];
                                                if (!empty($row['image']) && file_exists($imagePath)): ?>
                                                    <div class="mt-2">
                                                        <img src="<?= $imagePath ?>" alt="Restaurant Image"
                                                            class="img-thumbnail" style="max-height: 150px;">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="mt-2 text-muted">
                                                        <p class="text-danger">No Image Data</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>


                                   




                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">

                                                <label class="control-label">Stall Address</label>
                                                <textarea name="address" type="text" style="height:100px;"
                                                    class="form-control"> <?php echo $row['address']; ?> </textarea>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" name="submit" class="btn btn-primary" value="Save">
                            <a href="all_restaurant.php" class="btn btn-danger">Cancel</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</div>

<script src="js/lib/jquery/jquery.min.js"></script>
<script src="js/lib/bootstrap/js/popper.min.js"></script>
<script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/sidebarmenu.js"></script>
<script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="js/custom.min.js"></script>

</body>

</html>