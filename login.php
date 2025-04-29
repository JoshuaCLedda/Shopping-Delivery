<?php
session_start();
include("connection/connect.php");
error_reporting(0);

// login security
if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {
  $role = $_SESSION["role"];
  $redirectUrl = ($role == 1) ? "admin/dashboard.php" :
                 (($role == 2) ? "rider/index.php" :
                 (($role == 3) ? "stall/index.php" : "index.php"));
  header("Location: $redirectUrl");
  exit();
}


// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  switch ($_SESSION['role']) {
    case 1:
      header("Location: admin/dashboard.php");
      break;
    case 2:
      header("Location: rider/dashboard.php");
      break;
    case 3:
      header("Location: stall/dashboard.php");
      break;
    default:
      header("Location: index.php");
      break;
  }
  exit();
}

// Login logic
if (isset($_POST['submit'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (!empty($username) && !empty($password)) {
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
      $dbPassword = $row['password'];
      $isValid = false;

      if (substr($dbPassword, 0, 4) === '$2y$' || substr($dbPassword, 0, 4) === '$2a$') {
        $isValid = password_verify($password, $dbPassword);
      } else {
        $isValid = md5($password) === $dbPassword;
      }

      if ($isValid) {
        $_SESSION["user_id"] = $row['u_id'];
        $_SESSION["role"] = $row['role'];
        $_SESSION["restaurant_id"] = $row['restaurant_id'];

        switch ($row['role']) {
          case 1:
            header("Location: admin/dashboard.php");
            break;
          case 2:
            header("Location: rider/index.php");
            break;
          case 3:
            header("Location: stall/dashboard.php");
            break;
          default:
            header("Location: index.php");
            break;
        }
        exit();
      } else {
        $message = "Invalid username or password!";
      }
    } else {
      $message = "Account not found!";
    }
  } else {
    $message = "Please enter both username and password!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login | Food Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <!-- Custom Style -->
  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .bg-cover {
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/img/pimg.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
    }

    .login-card {
      max-width: 400px;
      width: 100%;
    }

    .eye-icon {
      cursor: pointer;
    }

    .navbar-brand img {
      height: 40px;
    }

    .input-group-text {
      border-left: 0;
    }

    .form-control:focus {
      box-shadow: none;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="restaurants.php">Stalls</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="bg-cover d-flex justify-content-center align-items-center">
    <div class="card login-card p-4 shadow-lg">
      <div class="text-center mb-4">
        <h4 class="fw-bold text-dark">
          <i class='bx bx-log-in-circle me-2'></i>Login to your accountds
        </h4>
        <?php if (isset($message)): ?>
          <div class="alert alert-danger py-1 mt-2 mb-0" role="alert" style="font-size: 0.9rem;">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
      </div>

      <form action="" method="post">
        <!-- Username with Icon -->
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text bg-white">
              <i class='bx bx-user'></i>
            </span>
            <input type="text" id="username" name="username" class="form-control" required />
          </div>
        </div>

        <!-- Password with Icon and Toggle -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text bg-white">
              <i class='bx bx-lock'></i>
            </span>
            <input type="password" id="password" name="password" class="form-control" required />
            <span class="input-group-text bg-white" style="cursor: pointer;">
              <i class='bx bx-show' id="togglePassword"></i>
            </span>
          </div>
        </div>

        <!-- Forgot Password -->
        <div class="mb-3 text-end">
          <a href="verify.php" class="text-decoration-none text-primary">Forgot Password?</a>
        </div>

        <!-- Submit -->
        <div class="d-grid">
          <input type="submit" id="buttn" name="submit" value="Login" class="btn btn-primary" />
        </div>
      </form>

      <div class="mt-4 text-center">
        <p>Register as a Rider? <a href="rider_registration.php" class="text-primary text-decoration-none">Click Here</a></p>
        <p>Register as a Customer? <a href="registration.php" class="text-primary text-decoration-none">Create an Account</a></p>
      </div>


    </div>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("password");

    togglePassword.addEventListener("click", function() {
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      this.classList.toggle("bx-show");
      this.classList.toggle("bx-hide");
    });
  </script>

</body>

</html>