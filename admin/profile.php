<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Assuming user_id is stored in session after login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Assuming your database connection is established in 'Main.php'
    include "Main.php";
    $index = new Index;

    // Query to fetch user data from database
    $query = "SELECT f_name, l_name, email, phone, address, role, status FROM users WHERE u_id = '$user_id'";
    $result = mysqli_query($index->con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        // Handle case when user data is not found
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
        header("Location: login.php"); // Redirect if no user data
        exit();
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
?>

<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>
<div id="main">
    <div class="main-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline-primary" id="first">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">User Management</h5>
                    </div>
                    <div class="card-body">
                        <!-- Success Alert -->
                        <div id="success-alert" class="alert alert-success" role="alert" style="display: none;">
                            Successfully Updated
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><p class="mb-0">Name</p></div>
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
                        <div class="row">
                            <div class="col-sm-3"><p class="mb-0">Email</p></div>
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
                        <div class="row">
                            <div class="col-sm-3"><p class="mb-0">Phone</p></div>
                            <div class="col-sm-9"><p class="text-muted mb-0"><?php echo isset($user) ? $user['phone'] : "Not available"; ?></p></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3"><p class="mb-0">Address</p></div>
                            <div class="col-sm-9"><p class="text-muted mb-0"><?php echo isset($user) ? $user['address'] : "Not available"; ?></p></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3"><p class="mb-0">Role</p></div>
                            <div class="col-sm-9"><p class="text-muted mb-0"><?php echo isset($user) ? $user['role'] : "Not available"; ?></p></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3"><p class="mb-0">Status</p></div>
                            <div class="col-sm-9"><p class="text-muted mb-0"><?php echo isset($user) ? $user['status'] : "Not available"; ?></p></div>
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

                        <form id="profileForm" action="#" method="POST">
                            <div class="row">
                                <div class="col-sm-3"><p class="mb-0">First Name</p></div>
                                <div class="col-sm-9"><input type="text" name="first_name" class="form-control" value="<?php echo isset($user) ? $user['f_name'] : ''; ?>"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3"><p class="mb-0">Last Name</p></div>
                                <div class="col-sm-9"><input type="text" name="last_name" class="form-control" value="<?php echo isset($user) ? $user['l_name'] : ''; ?>"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3"><p class="mb-0">Email</p></div>
                                <div class="col-sm-9"><input type="email" name="email" class="form-control" value="<?php echo isset($user) ? $user['email'] : ''; ?>"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3"><p class="mb-0">Stall</p></div>
                                <div class="col-sm-9"><input type="text" name="employee_no" class="form-control" value="<?php echo isset($user) ? $user['role'] : ''; ?>"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3"><p class="mb-0">Password</p></div>
                                <div class="col-sm-9"><input type="password" name="password" class="form-control"></div>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary mx-2" id="backButton" type="button">Back</button>
                                <button class="btn btn-success" id="saveButton" type="submit">Save</button>
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
<?php include 'layouts/footer.php' ?>
