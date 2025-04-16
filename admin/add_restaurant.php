<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;

// backend
if (isset($_POST['submit'])) {
    // Collect form data
    $res_name = $_POST['res_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $url = $_POST['url'];
    $o_hr = $_POST['o_hr'];
    $c_hr = $_POST['c_hr'];
    $o_days = $_POST['o_days'];
    $c_name = $_POST['c_name'];
    $image = $_FILES['image'];
    $address = $_POST['address'];



    // Call the model function with image path
    $result = $index->addRestaurant(
        $res_name,
        $email,
        $phone,
        $url,
        $o_hr,
        $c_hr,
        $o_days,
        $c_name,
        $image,
        $address

    );


    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Restaurant Registered Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to Register. Email Might Already Exist.'];
    }

    header("Location: add_restaurant.php");
    exit();
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
                        <li class="breadcrumb-item active" aria-current="page">Register Rider</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="all_restaurant.php" class="btn btn-primary">Back</a>
        </div>



        <div class="container-fluid">
            <div class="card card-outline-primary">

                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Register Stall</h5>
                </div>

                <div class="widget card-body shadow-sm">



                    <?php include 'layouts/alert.php' ?>

                    <form action="add_restaurant.php" method="post" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Stall Name</label>
                                        <input type="text" name="res_name" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group has-danger">
                                        <label class="control-label">Business E-mail</label>
                                        <input type="email" name="email" class="form-control form-control-danger"
                                            required>
                                    </div>
                                </div>



                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>
                                </div>

                            


                                <div class="col-md-6 mb-3">
                                    <div class="form-group has-danger">
                                        <label class="control-label">Website URL</label>
                                        <input type="text" name="url" class="form-control form-control-danger" required>
                                    </div>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Open Hours</label>
                                        <select name="o_hr" class="form-control custom-select" required>
                                            <option>--Select your Hours--</option>
                                            <option value="6am">6am</option>
                                            <option value="7am">7am</option>
                                            <option value="8am">8am</option>
                                            <option value="9am">9am</option>
                                            <option value="10am">10am</option>
                                            <option value="11am">11am</option>
                                            <option value="12pm">12pm</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Close Hours</label>
                                        <select name="c_hr" class="form-control custom-select" required>
                                            <option>--Select your Hours--</option>
                                            <option value="3pm">3pm</option>
                                            <option value="4pm">4pm</option>
                                            <option value="5pm">5pm</option>
                                            <option value="6pm">6pm</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Open Days</label>
                                        <select name="o_days" class="form-control custom-select" required>
                                            <option>--Select your Days--</option>
                                            <option value="Mon-Tue">Mon-Tue</option>
                                            <option value="Mon-Wed">Mon-Wed</option>
                                            <option value="Mon-Thu">Mon-Thu</option>
                                            <option value="Mon-Fri">Mon-Fri</option>
                                            <option value="Mon-Sat">Mon-Sat</option>
                                            <option value="24hr-x7">24hr-x7</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>



                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="control-label">Select Category</label>
                                        <select name="c_name" class="form-control custom-select" required>
                                            <option>Select Category</option>
                                            <?php
                                            $resultType = $index->getRestCategory();
                                            while ($row = mysqli_fetch_array($resultType)) {
                                                $c_id = $row['c_id'];
                                                $c_name = $row['c_name'];
                                                echo '<option value="' . $c_id . '">' . $c_name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="address" class="control-label">Stall Address</label>
                                        <textarea name="address" id="address" class="form-control" rows="3"
                                            required></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions my-2">
                                <button type="submit" name="submit" class="btn btn-success">Add Restaurant</button>
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