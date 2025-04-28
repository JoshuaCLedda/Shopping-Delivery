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


if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Form validation
    if (
        empty($_POST['username']) ||
        empty($_POST['firstname']) ||
        empty($_POST['lastname']) ||
        empty($_POST['email']) ||
        empty($_POST['phone']) ||
        empty($_POST['password']) ||
        empty($_POST['cpassword']) ||
        empty($_POST['security_questions']) ||
        empty($_POST['security_answer'])

    ) {
        echo "<script>alert('localhost says: All fields must be required!');</script>";
    } else {
        // Escape user inputs
        $username           = mysqli_real_escape_string($db, $_POST['username']);
        $firstname          = mysqli_real_escape_string($db, $_POST['firstname']);
        $lastname           = mysqli_real_escape_string($db, $_POST['lastname']);
        $email              = mysqli_real_escape_string($db, $_POST['email']);
        $phone              = mysqli_real_escape_string($db, $_POST['phone']);
        $password           = md5($_POST['password']); // Stronger: password_hash
        $security_question  = mysqli_real_escape_string($db, $_POST['security_questions']);
        $security_answer    = mysqli_real_escape_string($db, $_POST['security_answer']);
        $role               = 2;

        // Validation checks
        if ($_POST['password'] != $_POST['cpassword']) {
            echo "<script>alert('localhost says: Password does not match');</script>";
        } elseif (strlen($_POST['password']) < 6) {
            echo "<script>alert('localhost says: Password must be >= 6 characters');</script>";
        } elseif (strlen($_POST['phone']) < 10) {
            echo "<script>alert('localhost says: Invalid phone number!');</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('localhost says: Invalid email address!');</script>";
        } else {
            // Check for existing username/email in the correct table
            $check_username = mysqli_query($db, "SELECT username FROM users WHERE username = '$username'");
            $check_email = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");

            if (mysqli_num_rows($check_username) > 0) {
                echo "<script>alert('localhost says: Username already exists!');</script>";
            } elseif (mysqli_num_rows($check_email) > 0) {
                echo "<script>alert('localhost says: Email already exists!');</script>";
            } else {
                // Insert user into users table
                $mql = "INSERT INTO users (username, f_name, l_name, email, phone, password, security_questions, answer, role)
                        VALUES ('$username', '$firstname', '$lastname', '$email', '$phone', '$password', '$security_questions', '$security_answer', '$role')";

                if (!mysqli_query($db, $mql)) {
                    echo "<script>alert('Error inserting user: " . mysqli_error($db) . "');</script>";
                } else {
                    echo "<script>
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                          </script>";
                    exit();
                }
            }
        }
    }
}
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div style="background-image: url('images/img/pimg.jpg');">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">

            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="restaurants.php">Stalls</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="registration.php">Register</a>
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
                                            <div class="col-sm-4">
                                                <p><input type="submit" value="Register" name="submit" class="btn theme-btn"></p>
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
