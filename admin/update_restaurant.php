<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;
if (isset($_GET['res_upd'])) {
    $_SESSION['rs_id'] = $_GET['res_upd'];
}

$rs_id = $_SESSION['rs_id'] ?? null;
// backend
if (isset($_POST['submit'])) {
    $rs_id = $_SESSION['rs_id'];
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
    $result = $index->updateRestaurant(
        $rs_id,
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
            <a href="all_restaurant.php" class="btn btn-primary">Back
            </a>
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
                                    $res = mysqli_query($index->con, $ssql);
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
                                                <label class="control-label">Contact Number </label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="<?php echo $row['phone']; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group has-danger">
                                            <label class="control-label">Website <span class="text-success" style="font-style: italic">(if there are)</span></label>

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
                                                <label class="control-label">Select Category </label>
                                                <select name="c_name" class="form-control custom-select"
                                                    data-placeholder="Choose a Category" tabindex="1" required>
                                                    <option value="">--Select Category--</option>
                                                    <?php
                                                    $ssql = "SELECT * FROM res_category";
                                                    $res = mysqli_query($index->con, $ssql);


                                                    while ($cat = mysqli_fetch_array($res)) {
                                                        $selected = (isset($row['c_id']) && $row['c_id'] == $cat['c_id']) ? 'selected' : '';
                                                        echo '<option value="' . $cat['c_id'] . '" ' . $selected . '>' . $cat['c_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                        <?php
                                        $imagePath = $row['image'];
                                        ?>

<?php if (!empty($imagePath) && file_exists($imagePath)): ?>
    
    <div>
        <label class="control-label">Restaurant Profile</label>
        <input type="file" name="image" class="form-control">    
    </div>
    <div class="mt-2" id="image-preview">
    <img src="<?= $imagePath ?>" alt="Restaurant Image" class="img-thumbnail" style="max-height: 120px;">
    <button type="button" class="btn btn-sm btn-danger"
            id="deleteImageBtn" data-rs-id="<?= $rs_id ?>">
            <i class="bx bx-trash"></i>
    </button>
</div>

<?php else: ?>
    <div class="mt-2 text-muted">
    <label class="control">Resturant Profile</label>
    <input type="file" name="image" class="form-control">    
        <p class="text-danger my-3">No Image Data</p>
    </div>
<?php endif; ?>





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


<script>
$(document).on('click', '#deleteImageBtn', function () {
    const rs_id = $(this).data('rs-id');

    if (confirm('Are you sure you want to delete this image?')) {
        $.ajax({
            url: 'delete_image.php',
            type: 'POST',
            data: { rs_id: rs_id },
            success: function (response) {
                if (response === 'success') {
                    $('#image-preview').fadeOut();
                } else {
                    alert('Failed to delete image.');
                }
            }
        });
    }
});
</script>

<?php include 'layouts/footer.php' ?>