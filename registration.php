<?php
session_start();
error_reporting(0);
include("connection/connect.php");

if(isset($_POST['submit'])) 
{
    if(empty($_POST['username']) || 
       empty($_POST['firstname']) || 
       empty($_POST['lastname']) || 
       empty($_POST['email']) ||  
       empty($_POST['phone']) ||
       empty($_POST['password']) ||
       empty($_POST['cpassword']) ||
       empty($_POST['address']) ||
       empty($_POST['security_questions']) ||
       empty($_POST['answer']))
    {
        echo "<script>alert('All fields must be required!');</script>";
    }
    else
    {
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

        $check_username = mysqli_query($db, "SELECT username FROM users WHERE username = '$username'");
        $check_email = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");

        if($password !== $cpassword) {  
            echo "<script>alert('Passwords do not match');</script>"; 
        }
        elseif(strlen($password) < 6) {
            echo "<script>alert('Password must be at least 6 characters');</script>"; 
        }
        elseif(strlen($phone) < 10) {
            echo "<script>alert('Invalid phone number!');</script>"; 
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email address!');</script>"; 
        }
        elseif(mysqli_num_rows($check_username) > 0) {
            echo "<script>alert('Username already exists!');</script>"; 
        }
        elseif(mysqli_num_rows($check_email) > 0) {
            echo "<script>alert('Email already exists!');</script>"; 
        }
        else
        {
            // Secure password hashing using bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user details into the database
            $mql = "INSERT INTO users(username, f_name, l_name, email, phone, password, address, security_questions, answer) 
                    VALUES('$username', '$firstname', '$lastname', '$email', '$phone', '$hashed_password', '$address', '$security_questions', '$answer')";

            mysqli_query($db, $mql);

            echo "<script>alert('Registration Successful!');</script>";
            header("refresh:0.1;url=login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/icn.png" alt=""> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home</a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Stall</a> </li>
                            <?php
                                if(empty($_SESSION["user_id"])) {
                                    echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>';
                                    echo '<li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
                                } else {
                                    echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a> </li>';
                                    echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
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
                                    <form action="" method="post">
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
                                                <input type="text" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputEmail1">Phone number</label>
                                                <input class="form-control" type="text" name="phone" id="example-tel-input-3">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputPassword1">Password</label>
                                                <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="exampleInputPassword1">Confirm password</label>
                                                <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2">
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

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p> <input type="submit" value="Register" name="submit" class="btn theme-btn"> </p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="row bottom-footer">
                    <div class="col-xs-12 col-sm-3 payment-options color-gray">
                        <h5>Payment Options</h5>
                        <ul>
                            <li><a href="#"> <img src="images/paypal.png" alt="Paypal"> </a></li>
                            <li><a href="#"> <img src="images/mastercard.png" alt="Mastercard"> </a></li>
                            <li><a href="#"> <img src="images/maestro.png" alt="Maestro"> </a></li>
                            <li><a href="#"> <img src="images/stripe.png" alt="Stripe"> </a></li>
                            <li><a href="#"> <img src="images/bitcoin.png" alt="Bitcoin"> </a></li>
                        </ul>
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
