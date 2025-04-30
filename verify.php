<?php
// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  unset($_SESSION['allow_security_question']);
  unset($_SESSION['allow_password_reset']);
  unset($_SESSION['user_id']);
  unset($_SESSION['security_question']);
  unset($_SESSION['security_answer']);
}

include_once __DIR__ . '/connection/connect.php'; 
// Check if connection was established
if (!$db) {
    die("Error: Database connection is not established.");
}
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_username'])) {
        // Step 1: Handle username/email submission
        $username = mysqli_real_escape_string($db, trim($_POST['username']));
        
        // Check if username or email exists
        $stmt = mysqli_prepare($db, "SELECT * FROM users WHERE username=? OR email=?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Fetch user data
            $user = mysqli_fetch_assoc($result);
            $user_id = $user['u_id'];
            $security_question = $user['security_questions']; // Store security question
            $security_answer = $user['answer']; // Store plain text answer

            // Store user details in session for the next steps
            $_SESSION['user_id'] = $user_id;
            $_SESSION['security_question'] = $security_question;
            $_SESSION['security_answer'] = $security_answer;
            $_SESSION['allow_security_question'] = true; // Allow the next step
        } else {
            $error = "User not found!";
        }
    } elseif (isset($_POST['verify_answer'])) {
        // Step 2: Handle security question answer verification
        if ($_SESSION['allow_security_question']) {
            $user_answer = trim($_POST['security_answer']);
            
            // Compare the provided answer with the plain text answer stored in the database
            if ($user_answer === $_SESSION['security_answer']) {
                $_SESSION['allow_password_reset'] = true;
            } else {
                $error = "Incorrect answer to the security question!";
            }
        } else {
            $error = "You are not authorized to reset the password.";
        }
    } elseif (isset($_POST['reset_password'])) {
        // Step 3: Handle password reset
        if ($_SESSION['allow_password_reset']) {
            $user_id = $_SESSION['user_id'];
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);

            // Password requirements check (minimum 8 characters, 1 uppercase, 1 number)
            if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
                $error = "Password must be at least 8 characters long and include at least one uppercase letter and one number.";
            } elseif ($new_password !== $confirm_password) {
                $error = "Passwords do not match!";
            } else {
                // Hash the new password securely using password_hash()
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Securely hashed password

                // Update password in the database
                $stmt = mysqli_prepare($db, "UPDATE users SET password=? WHERE u_id=?");
                mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>
                        alert('The password reset was successful, you can now log in to your account!');
                        window.location.href = 'login.php';
                    </script>";
                    exit();
                } else {
                    $error = "Failed to reset password!";
                }
            }
        } else {
            $error = "You are not authorized to reset the password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .forgot-container {
      max-width: 500px;
      margin: auto;
      margin-top: 80px;
    }
    .form-icon {
      font-size: 1.3rem;
      vertical-align: middle;
      margin-right: 8px;
      color: #0d6efd;
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


<!-- ... same <head> as before ... -->
<div class="container forgot-container">
  <div class="text-center mb-4">
    <h4 class="fw-bold"><i class='bx bx-key form-icon'></i>Forgot Password</h4>
    <p class="text-muted">Follow the steps to reset your password.</p>
  </div>

  <!-- Error Message -->
  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- Success Message -->
  <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <!-- Step 1: Enter Username/Email -->
  <?php if (!isset($_SESSION['allow_security_question'])): ?>
    <div class="mb-3">
      <h6><i class='bx bx-user-circle'></i> Step 1: Enter Username or Email</h6>
      <p class="text-muted mb-2">We’ll use this to verify your identity.</p>
    </div>
    <div class="card shadow">
      <div class="card-body">
        <form method="post" action="">
          <div class="mb-3">
            <label for="username" class="form-label">Username or Email</label>
            <input type="text" class="form-control" name="username" id="username" required>
          </div>
          <button type="submit" name="submit_username" class="btn btn-primary w-100">Submit</button>
        </form>
      </div>
    </div>

  <!-- Step 2: Answer Security Question -->
  <?php elseif (isset($_SESSION['allow_security_question']) && !isset($_SESSION['allow_password_reset'])): ?>
    <div class="mb-3">
      <h6><i class='bx bx-help-circle'></i> Step 2: Answer Your Security Question</h6>
      <p class="text-muted mb-2">Please answer the question you selected during registration.</p>
    </div>
    <div class="card shadow">
      <div class="card-body">
        <form method="post" action="">
          <div class="mb-3">
            <label for="security_question" class="form-label">Security Question</label>
            <select class="form-select" name="security_question" id="security_question" required>
              <option value="mother_maiden_name">What is your mother's maiden name?</option>
              <option value="pet_name">What is the name of your first pet?</option>
              <option value="birth_city">In what city were you born?</option>
              <option value="favorite_food">What is your favorite food?</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="security_answer" class="form-label">Your Answer</label>
            <input type="text" class="form-control" name="security_answer" required>
          </div>
          <button type="submit" name="verify_answer" class="btn btn-primary w-100">Verify Answer</button>
        </form>
      </div>
    </div>

  <!-- Step 3: Reset Password -->
  <?php elseif (isset($_SESSION['allow_password_reset'])): ?>
    <div class="mb-3">
      <h6><i class='bx bx-lock-open'></i> Step 3: Reset Your Password</h6>
      <p class="text-muted mb-2">Choose a strong new password.</p>
    </div>
    <div class="card shadow">
      <div class="card-body">
        <form method="post" action="">
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" required>
          </div>
          <button type="submit" name="reset_password" class="btn btn-success w-100">Reset Password</button>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>
