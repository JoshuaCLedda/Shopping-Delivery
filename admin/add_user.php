<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}
include("../connection/connect.php");

if (isset($_POST['submit'])) {
    if (
        empty($_POST['username']) ||
        empty($_POST['firstname']) ||
        empty($_POST['lastname']) ||
        empty($_POST['email']) ||
        empty($_POST['phone']) ||
        empty($_POST['password']) ||
        empty($_POST['cpassword']) ||
        empty($_POST['address']) ||
        empty($_POST['security_questions']) ||
        empty($_POST['answer'])
    ) {
        echo "<script>alert('All fields must be required!');</script>";
    } else {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $phone = mysqli_real_escape_string($db, $_POST['phone']);
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $address = mysqli_real_escape_string($db, $_POST['address']);
        $security_questions = mysqli_real_escape_string($db, $_POST['security_questions']);
        $answer = mysqli_real_escape_string($db, $_POST['answer']);

        $role = mysqli_real_escape_string($db, $_POST['role']);
        //Additional hidden 2 
        $restaurant_id = isset($_POST['restaurant_id']) && $_POST['restaurant_id'] !== '' ? intval($_POST['restaurant_id']) : 0;
        $vehicle_type = isset($_POST['vehicle_type']) && $_POST['vehicle_type'] !== '' ? intval($_POST['vehicle_type']) : 0;



        $check_username = mysqli_query($db, "SELECT username FROM users WHERE username = '$username'");
        $check_email = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");

        if ($password !== $cpassword) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Passwords do not match!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        } elseif (strlen($password) < 6) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Password must be at least 6 characters!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        } elseif (strlen($phone) < 10) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid phone number!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid email address!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        } elseif (mysqli_num_rows($check_username) > 0) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Username already exists!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        } elseif (mysqli_num_rows($check_email) > 0) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Email already exists!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        } else {
            // Secure password hashing using bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user details into the database
            $mql = "INSERT INTO users(restaurant_id, vehicle_type, username, f_name, l_name, email, phone, password, address, role, security_questions, answer) 
                    VALUES('$restaurant_id', '$vehicle_type', '$username', '$firstname', '$lastname', '$email', '$phone', '$hashed_password', '$address', '$role', '$security_questions', '$answer')";

            if (mysqli_query($db, $mql)) {
                $_SESSION['message'] = ['type' => 'success', 'message' => 'Registration successful!'];
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'message' => 'Registration failed! Please try again.'];
            }

            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
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
            <a href="all_users.php" class="btn btn-primary">Back</a>
        </div>

        <?php include 'layouts/sweetalert.php'; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-outline-primary">

                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Register New User</h5>
                    </div>

                    <div class="widget card-body shadow-sm">

                        <div class="widget-body">

                            <form action="" method="POST">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="exampleInputEmail1">User-Name</label>
                                        <input class="form-control" type="text" name="username" id="example-text-input">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">First Name</label>
                                        <input class="form-control" type="text" name="firstname" id="example-text-input">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Last Name</label>
                                        <input class="form-control" type="text" name="lastname" id="example-text-input-2">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Phone number</label>
                                        <input class="form-control" type="text" name="phone" id="example-tel-input-3">
                                    </div>
                                    <div class="form-group col-sm-6 position-relative">
                                        <label for="exampleInputPassword1">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                                            <span class="input-group-text" onclick="togglePassword('exampleInputPassword1', this)">
                                                <i class='bx bx-hide'></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 position-relative">
                                        <label for="exampleInputPassword2">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2" onkeyup="validatePassword()">
                                            <span class="input-group-text" onclick="togglePassword('exampleInputPassword2', this)">
                                                <i class='bx bx-hide'></i>
                                            </span>
                                        </div>
                                        <span id="password-error" class="text-danger small"></span>
                                    </div>



                                    <div class="form-group col-md-6">
                                        <label for="role">Role</label>
                                        <select class="form-control" name="role" id="role">
                                            <option value="0">User</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Rider</option>
                                            <option value="3">Stall</option>
                                        </select>
                                    </div>

                                    <!-- Stall Selection (Shown only when role = Stall) -->
                                    <div class="col-md-6" id="stallSection" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label">Select Stall</label>
                                            <select name="restaurant_id" id="stallSelect" class="form-control custom-select" disabled>
                                                <option value="">--Select Stall--</option>
                                                <?php
                                                $ssql = "SELECT * FROM restaurant";
                                                $res = mysqli_query($db, $ssql);
                                                while ($row = mysqli_fetch_array($res)) {
                                                    echo '<option value="' . $row['rs_id'] . '">' . $row['title'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="vehicleSection" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label">Vehicle Type</label>
                                            <select name="vehicle_type" id="vehicleSelect" class="form-control custom-select" disabled>
                                                <option value="">--Select Vehicle Type--</option>
                                                <?php
                                                $ssql = "SELECT * FROM vehicles";
                                                $res = mysqli_query($db, $ssql);
                                                while ($row = mysqli_fetch_array($res)) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label for="exampleTextarea">Delivery Address</label>
                                        <textarea class="form-control" id="exampleTextarea" name="address" rows="3"></textarea>
                                    </div>

                                    <!-- New Fields for Security Question and Answer -->
                                    <div class="form-group col-sm-12">
                                        <label for="securityQuestion">Security Question</label>
                                        <select class="form-control" name="security_questions" id="securityQuestion">
                                            <option value="mother_maiden_name">What is your mother's maiden name?</option>
                                            <option value="pet_name">What is the name of your first pet?</option>
                                            <option value="birth_city">In what city were you born?</option>
                                            <option value="favorite_food">What is your favorite food?</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label for="securityAnswer">Answer</label>
                                        <input class="form-control" type="text" name="answer" id="securityAnswer" required>
                                    </div>


                                </div>

                                <div class="col-sm-12 my-2">
                                    <button type="submit" name="submit" class="btn btn-primary">Register User</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const stallSection = document.getElementById('stallSection');
        const vehicleSection = document.getElementById('vehicleSection');
        const stallSelect = document.getElementById('stallSelect');
        const vehicleSelect = document.getElementById('vehicleSelect');

        roleSelect.addEventListener('change', function() {
            const role = this.value;

            // Reset all
            stallSection.style.display = 'none';
            stallSelect.disabled = true;
            vehicleSection.style.display = 'none';
            vehicleSelect.disabled = true;

            if (role === '3') { // Stall
                stallSection.style.display = 'block';
                stallSelect.disabled = false;
            } else if (role === '2') { // Rider
                vehicleSection.style.display = 'block';
                vehicleSelect.disabled = false;
            }
        });
    });
</script>
<script>
    function togglePassword(inputId, iconSpan) {
        const input = document.getElementById(inputId);
        const icon = iconSpan.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = "password";
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    }

    function validatePassword() {
        const password = document.getElementById('exampleInputPassword1').value;
        const confirmPassword = document.getElementById('exampleInputPassword2').value;
        const errorText = document.getElementById('password-error');

        if (confirmPassword === "") {
            errorText.textContent = "";
        } else if (password === confirmPassword) {
            errorText.textContent = "Passwords match.";
            errorText.classList.remove('text-danger');
            errorText.classList.add('text-success');
        } else {
            errorText.textContent = "Passwords do not match.";
            errorText.classList.remove('text-success');
            errorText.classList.add('text-danger');
        }
    }
</script>