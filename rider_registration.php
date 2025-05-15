<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection/connect.php");

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 1:
            header("Location: admin/dashboard.php");
            break;
        case 2:
            header("Location: rider/index.php");
            break;
        case 3:
            header("Location: stall/index.php");
            break;
        default:
            header("Location: index.php");
            break;
    }
    exit();
}


if (isset($_POST['submit'])) {
    $fields = ['username', 'firstname', 'lastname', 'email', 'phone', 'password', 'cpassword', 'security_questions', 'security_answer'];

    // Extract & sanitize
    $username  = mysqli_real_escape_string($db, $_POST['username']);
    $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($db, $_POST['lastname']);
    $email     = mysqli_real_escape_string($db, $_POST['email']);
    $phone     = mysqli_real_escape_string($db, $_POST['phone']);
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $question  = mysqli_real_escape_string($db, $_POST['security_questions']);
    $answer    = mysqli_real_escape_string($db, $_POST['security_answer']);
    $role      = 2;

    // Check for duplicates
    $exists = mysqli_query($db, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($exists)) {
        echo "<script>alert('Username or email already exists!');</script>"; exit;
    }

    // Save user
    $hashedPassword = md5($password); // Consider using password_hash() instead
    $sql = "INSERT INTO users (username, f_name, l_name, email, phone, password, security_questions, answer, role) 
            VALUES ('$username', '$firstname', '$lastname', '$email', '$phone', '$hashedPassword', '$question', '$answer', '$role')";

    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($db) . "');</script>";
    }
}
?>


<?php include 'layouts/header.php'  ?>

<body>
    <div style="background-image: url('images/img/pimg.jpg');">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class='bx bx-home me-2'></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="restaurants.php">
                    <i class='bx bx-store me-2'></i> Stalls
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="login.php">
                    <i class='bx bx-log-in me-2'></i> Login
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="registration.php">
                    <i class='bx bx-user-plus me-2'></i> Register
                </a>
            </li>
        </ul>
    </div>
</nav>


        <div class="page-wrapper">
            <section class="contact-page inner-page">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="widget">
                                <div class="widget-body">
                                    <center>
                                        <h2>Rider Registration</h2>
                                    </center>
                                    <form action="" method="post">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputEmail1">User-Name</label>
                                                <input class="form-control" type="text" name="username" id="example-text-input" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputEmail1">First Name</label>
                                                <input class="form-control" type="text" name="firstname" id="example-text-input" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputEmail1">Last Name</label>
                                                <input class="form-control" type="text" name="lastname" id="example-text-input-2" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputEmail1">Address</label>
                                                <input class="form-control" type="text" name="address" id="example-text-input-2" required>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputEmail1">Phone number</label>
                                                <input class="form-control" type="text" name="phone" id="example-tel-input-3" required>
                                            </div>
                                            <div class="form-group col-sm-6 position-relative">
                                                <label for="exampleInputPassword1">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
                                                    <span class="input-group-text" onclick="togglePassword('exampleInputPassword1', this)">
                                                        <i class='bx bx-hide'></i>
                                                    </span>
                                                </div>
                                            </div>


                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputPassword1">Confirm password</label>
                                                <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2" required>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label for="security_question">Security Question</label>
                                                <select name="security_questions" class="form-control" required>
                                                    <option value="Your first pet's name?">Your first pet's name?</option>
                                                    <option value="Your mother's maiden name?">Your mother's maiden name?</option>
                                                    <option value="City you were born in?">City you were born in?</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label for="security_answer">Answer</label>
                                                <input type="text" class="form-control" name="security_answer" placeholder="Answer" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 my-3">
                                                <p><input type="submit" value="Register" name="submit" class="btn btn-primary"></p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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
</script>
