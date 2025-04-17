<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include "Main.php";
$index = new Index;

$u_id = $_GET['user_upd'];

if (isset($_POST['submit']) && $u_id) {
    $u_id = trim($_POST['u_id']);
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']); // may be empty

    // If password is entered, hash it
    $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    // Now call your model function to update profile
    $result = $index->updateUser(
        $u_id,
        $f_name,
        $l_name,
        $username,
        $phone,
        $email,
        $address,
        $role,
        $hashed_password
    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Profile Updated Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Update failed! Please try again.'];
    }

    // Use the HTTP referer to redirect the user back to the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
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
            <a href="rider_details.php" class="btn btn-primary">Back</a>
        </div>

        <?php include 'layouts/alert.php'; ?>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Register Rider</h5>
                    </div>

                    <div class="widget card-body shadow-sm">
                        <div class="widget-body">
                            <?php
                            $ssql = "SELECT * FROM users WHERE u_id='" . $_GET['user_upd'] . "'";
                            $res = mysqli_query($index->con, $ssql);
                            $newrow = mysqli_fetch_array($res);
                            ?>

                            <form action='' method='post'>
                                <div class="form-body">
                                    <input type="hidden" name="u_id" value="<?php echo $newrow['u_id']; ?>">

                                    <div class="row p-t-20">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Username</label>
                                                <input type="text" name="username" class="form-control"
                                                    value="<?php echo $newrow['username']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">First Name</label>
                                                <input type="text" name="f_name" class="form-control"
                                                    value="<?php echo $newrow['f_name']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Last Name</label>
                                                <input type="text" name="l_name" class="form-control"
                                                    value="<?php echo $newrow['l_name']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="<?php echo $newrow['email']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Password</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Phone</label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="<?php echo $newrow['phone']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Role</label>
                                                <select name="role" class="form-control">
                                                    <option value="0" <?php echo ($newrow['role'] == 0) ? 'selected' : ''; ?>>User</option>
                                                    <option value="1" <?php echo ($newrow['role'] == 1) ? 'selected' : ''; ?>>Admin</option>
                                                    <option value="2" <?php echo ($newrow['role'] == 2) ? 'selected' : ''; ?>>Rider</option>
                                                    <option value="3" <?php echo ($newrow['role'] == 3) ? 'selected' : ''; ?>>Stall Owner</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Save">
                                    <a href="all_users.php" class="btn btn-danger">Cancel</a>
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
