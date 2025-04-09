<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(E_ALL); // Show all errors for debugging
include("connection/connect.php");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Form validation
    if (empty($_POST['username']) || 
        empty($_POST['firstname']) || 
        empty($_POST['lastname']) || 
        empty($_POST['email']) ||  
        empty($_POST['phone']) || 
        empty($_POST['password']) || 
        empty($_POST['cpassword']) ||
        empty($_POST['security_question']) || 
        empty($_POST['security_answer'])) {
        echo "<script>alert('localhost says: All fields must be required!');</script>";
    } else {
        // Escape user inputs for security
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $phone = mysqli_real_escape_string($db, $_POST['phone']);
        $password = md5($_POST['password']); // Consider using a stronger hash method
        $security_question = mysqli_real_escape_string($db, $_POST['security_question']);
        $security_answer = mysqli_real_escape_string($db, $_POST['security_answer']);
        
        // Check if the passwords match
        if ($_POST['password'] != $_POST['cpassword']) {
            echo "<script>alert('localhost says: Password does not match');</script>";
        } elseif (strlen($_POST['password']) < 6) {
            echo "<script>alert('localhost says: Password must be >= 6 characters');</script>";
        } elseif (strlen($_POST['phone']) < 10) {
            echo "<script>alert('localhost says: Invalid phone number!');</script>";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('localhost says: Invalid email address!');</script>";
        } else {
            // Check if the username or email already exists
            $check_username = mysqli_query($db, "SELECT username FROM riders WHERE username = '$username'");
            $check_email = mysqli_query($db, "SELECT email FROM riders WHERE email = '$email'");

            if (mysqli_num_rows($check_username) > 0) {
                echo "<script>alert('localhost says: Username already exists!');</script>";
            } elseif (mysqli_num_rows($check_email) > 0) {
                echo "<script>alert('localhost says: Email already exists!');</script>";
            } else {
                // Insert user data into the database
                $mql = "INSERT INTO riders (username, f_name, l_name, email, phone, password, security_question, answer)
                        VALUES ('$username', '$firstname', '$lastname', '$email', '$phone', '$password', '$security_question', '$security_answer')";

if (!mysqli_query($db, $mql)) {
    echo "<script>alert('Error inserting user: " . mysqli_error($db) . "');</script>";
} else {
    // Alert before redirect
    echo "<script>
            alert('Registration successful! ');
            window.location.href = 'login.php';
          </script>";
    exit(); // Prevent further code execution after the redirect
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
</head>
<body>
<div style="background-image: url('images/img/pimg.jpg');">
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"><img class="img-rounded" src="images/icn.png" alt=""></a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="restaurants.php">Stall <span class="sr-only"></span></a></li>
                        <?php
                        if (empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>
                                  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a></li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a></li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="page-wrapper">
        <section class="contact-page inner-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="widget-body">
                                <center><h2>Rider Registration</h2></center>
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
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
                                            <label for="exampleInputEmail1">Email Address</label>
                                            <input type="text" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputEmail1">Phone number</label>
                                            <input class="form-control" type="text" name="phone" id="example-tel-input-3" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputPassword1">Confirm password</label>
                                            <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2" required>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="security_question">Security Question</label>
                                            <select name="security_question" class="form-control" required>
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

        <footer class="footer">
            <div class="container">
                <div class="row bottom-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 payment-options color-gray">
                                <h5>Payment Options</h5>
                                <ul>
                                    <li><a href="#"><img src="images/paypal.png" alt="Paypal"></a></li>
                                    <li><a href="#"><img src="images/mastercard.png" alt="Mastercard"></a></li>
                                    <li><a href="#"><img src="images/maestro.png" alt="Maestro"></a></li>
                                    <li><a href="#"><img src="images/stripe.png" alt="Stripe"></a></li>
                                    <li><a href="#"><img src="images/bitcoin.png" alt="Bitcoin"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>
</html>
