<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    include "../admin/Main.php";
    $index = new Index;

    $query = "SELECT f_name, l_name, username, email, phone, address, role, status FROM users WHERE u_id = '$user_id'";
    $result = mysqli_query($index->con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
        header("Location: login.php"); // Redirect if no user data
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}


// update profile


if (isset($_POST['submit']) && $user_id) {
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']); // may be empty

    // If password is entered, hash it
    $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    // Now call your model function to update profile
    $result = $index->updateProfile(
        $user_id, 
        $f_name, 
        $l_name, 
        $username, 
        $email, 
        $address, 
        $hashed_password
    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Profile Updated Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Update failed! Please try again.'];
    }

    header("Location: profile.php"); // Redirect to the same page to see the changes
    exit();
}


?>

<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>
<div id="main">
    <div class="main-container">
        <div class="row">
            <div class="col-lg-12">
                <?php include '../layouts/alert.php'; ?>
                <div class="card card-outline-primary" id="first">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">User Management</h5>
                    </div>
                    <div class="card-body">
                       

                    <div id="success-alert" class="alert alert-success" role="alert" style="display: none;">
                            Successfully Updated
                        </div>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <p class="mb-0">Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0" id="userName">
                                    <?php
                                    if (isset($user)) {
                                        echo $user['f_name'] . ' ' . $user['l_name'];
                                    } else {
                                        echo "User not found";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0" id="userEmail">
                                    <?php
                                    if (isset($user)) {
                                        echo $user['email'];
                                    } else {
                                        echo "Not available";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($user) ? $user['phone'] : "Not available"; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($user) ? $user['address'] : "Not available"; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <p class="mb-0">Role</p>
                            </div>
                            <div class="col-sm-9">
                                <?php
                                if (isset($user)) {
                                    switch ($user['role']) {
                                        case 0:
                                            $statusText = 'User';
                                            $badgeClass = 'badge bg-primary';
                                            break;
                                        case 1:
                                            $statusText = 'Admin';
                                            $badgeClass = 'badge bg-success';
                                            break;
                                        case 2:
                                            $statusText = 'Rider';
                                            $badgeClass = 'badge bg-warning text-dark';
                                            break;
                                        case 3:
                                            $statusText = 'Stall';
                                            $badgeClass = 'badge bg-info text-dark';
                                            break;
                                        default:
                                            $statusText = 'Unknown';
                                            $badgeClass = 'badge bg-secondary';
                                    }
                                    echo '<span class="' . $badgeClass . '">' . $statusText . '</span>';
                                } else {
                                    echo '<span class="badge bg-secondary">Not available</span>';
                                }
                                ?>
                            </div>
                        </div>

                        <hr>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <p class="mb-0">Status</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="mb-0">
                                    <?php
                                    if (isset($user)) {
                                        if ($user['status'] == 'active') {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } elseif ($user['status'] == 'inactive') {
                                            echo '<span class="badge bg-danger">Inactive</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Unknown</span>';
                                        }
                                    } else {
                                        echo '<span class="badge bg-secondary">Not available</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success" id="editButton" type="button">Edit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Edit -->
            <div class="col-lg-12">
                <div class="card card-outline-primary" id="hidden" style="display:none;">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        <div id="danger-alert" class="alert alert-danger" role="alert" style="display: none;"></div>

                        <form action="" method="POST">
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0">First Name</p>
                                </div>
                                <div class="col-sm-9"><input type="text" name="f_name" class="form-control"
                                        value="<?= isset($user) ? htmlspecialchars($user['f_name']) : ''; ?>"></div>
                            </div>
                            <hr>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0">Last Name</p>
                                </div>
                                <div class="col-sm-9"><input type="text" name="l_name" class="form-control"
                                        value="<?= isset($user) ? htmlspecialchars($user['l_name']) : ''; ?>"></div>
                            </div>
                            <hr>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0">Username</p>
                                </div>
                                <div class="col-sm-9"><input type="text" name="username" class="form-control"
                                        value="<?= isset($user) ? htmlspecialchars($user['username']) : ''; ?>"></div>
                            </div>
                            <hr>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9"><input type="email" name="email" class="form-control"
                                        value="<?= isset($user) ? htmlspecialchars($user['email']) : ''; ?>"></div>
                            </div>
                            <hr>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0">Address</p>
                                </div>
                                <div class="col-sm-9"><input type="text" name="address" class="form-control"
                                        value="<?= isset($user) ? htmlspecialchars($user['address']) : ''; ?>"></div>
                            </div>
                            <hr>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <p class="mb-0">Password</p>
                                </div>
                                <div class="col-sm-9"><input type="password" name="password" class="form-control"
                                        placeholder="Leave blank to keep current password"></div>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary mx-2" id="backButton" type="button">Back</button>
                                <button class="btn btn-success" name="submit" type="submit">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editButton = document.getElementById("editButton");
        const backButton = document.getElementById("backButton");
        const firstSection = document.getElementById("first");
        const hiddenSection = document.getElementById("hidden");
        const profileForm = document.getElementById("profileForm");
        const successAlert = document.getElementById("success-alert");
        const dangerAlert = document.getElementById("danger-alert");

        editButton.addEventListener("click", function () {
            firstSection.style.display = "none";
            hiddenSection.style.display = "block";
        });

        backButton.addEventListener("click", function () {
            hiddenSection.style.display = "none";
            firstSection.style.display = "block";
        });

        profileForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // Simulate form submission and show success alert
            successAlert.style.display = "block";
            setTimeout(() => {
                successAlert.style.display = "none";
                hiddenSection.style.display = "none";
                firstSection.style.display = "block";
                // You could also update the text content of the view with new input values here
            }, 1500);
        });
    });
</script>
<?php include '../admin/layouts/footer.php' ?>