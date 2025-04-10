<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>  
  <meta charset="UTF-8">
  <title>Login</title>
  
  <!-- Reset CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet prefetch" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900">
  <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/login.css">

  <style type="text/css">
    #buttn {
      color: #fff;
      background-color: #5c4ac7;
    }
  </style>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animsition.min.css" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <header id="header" class="header-scroll top-header headrom">
    <nav class="navbar navbar-dark">
      <div class="container">
        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
        <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/icn.png" alt=""> </a>
        <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
          <ul class="nav navbar-nav">
            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
            <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Stalls <span class="sr-only"></span></a> </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <div style="background-image: url('images/img/pimg.jpg');">

  <?php
include("connection/connect.php"); 
error_reporting(0); 

if (isset($_POST['submit'])) {  
    $username = trim($_POST['username']);  
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Prepared statement to prevent SQL Injection
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Check if password matches either MD5 hash or bcrypt hash
        if ($row) {
            // Check if the password is bcrypt hashed
            if (substr($row['password'], 0, 4) === '$2y$' || substr($row['password'], 0, 4) === '$2a$') {
                // Password is bcrypt hashed
                if (password_verify($password, $row['password'])) {
                    $_SESSION["user_id"] = $row['u_id']; 
                    $_SESSION["role"] = $row['role'];

                    // Redirect based on role
                    if ($row['role'] == 1) {
                        header("Location: admin/dashboard.php");
                    } elseif ($row['role'] == 2) {
                        header("Location: rider/index.php");
                        
                    }elseif ($row['role'] == 3) {
                          header("Location: stall/index.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    $message = "Invalid Username or Password!";
                }
            } else {
                // Password is MD5 hashed
                if (md5($password) === $row['password']) {
                    $_SESSION["user_id"] = $row['u_id']; 
                    $_SESSION["role"] = $row['role'];

                    // Redirect based on role
                    if ($row['role'] == 1) {
                        header("Location: admin/dashboard.php");
                    } elseif ($row['role'] == 2) {
                        header("Location: rider/index.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    $message = "Invalid Username or Password!";
                }
            }
        }
    }
}
?>


    <div class="pen-title">
      <!-- Pen Title (Leave empty or modify) -->
    </div>

    <div class="module form-module">
      <div class="toggle"></div>
      <div class="form">
        <h2>Login to your account</h2>
        <span style="color:red;"><?php echo isset($message) ? $message : ''; ?></span> 
        <form action="" method="post">
          <input type="text" placeholder="Username" name="username" required/>
          <input type="password" placeholder="Password" name="password" required/>
          
          <div class="forgot-pass-remember-me mt=10">
            <div class="forgot-pass">
              <a href="verify.php"> Forgot Password</a>
            </div>
          </div>
          <input type="submit" id="buttn" name="submit" value="Login" />
        </form>
      </div>
      <div class="cta">Register as a Rider?<a href="rider_registration.php" style="color:#5c4ac7;"> Click Here</a></div>

      <div class="cta">Register as a Customer?<a href="registration.php" style="color:#5c4ac7;"> Create an account</a></div>
    </div>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <div class="container-fluid pt-3">
      <p></p>
    </div>

    <footer class="footer">
      <div class="container">
        <div class="bottom-footer">
          <div class="row">
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
      </div>
    </footer>
  </div>

</body>

</html>
