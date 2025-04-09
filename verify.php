<?php
// Start session
session_start();

// Include connection file
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Forgot Password</h2>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Step 1: Enter Username/Email -->
        <?php if (!isset($_SESSION['allow_security_question'])): ?>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="username">Use Email or Username in Reset Password</label>
                            <input type="text" class="for   m-control" name="username" required>
                        </div>
                        <button type="submit" name="submit_username" class="btn btn-primary btn-block">Submit</button>
                    </form>
                </div>
            </div>
        <?php elseif (isset($_SESSION['allow_security_question']) && !isset($_SESSION['allow_password_reset'])): ?>
            <!-- Step 2: Answer the Security Question -->
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="security_question">Choose a Security Question</label>
                            <select class="form-control" name="security_question" id="security_question" required>
                                <option value="mother_maiden_name">What is your mother's maiden name?</option>
                                <option value="pet_name">What is the name of your first pet?</option>
                                <option value="birth_city">In what city were you born?</option>
                                <option value="favorite_food">What is your favorite food?</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="security_answer">Enter Your Answer:</label>
                            <input type="text" class="form-control" name="security_answer" required>
                        </div>
                        <button type="submit" name="verify_answer" class="btn btn-primary btn-block">Verify Answer</button>
                    </form>
                </div>
            </div>
        <?php elseif (isset($_SESSION['allow_password_reset'])): ?>
            <!-- Step 3: Reset Password -->
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="new_password">Enter New Password:</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <button type="submit" name="reset_password" class="btn btn-success btn-block">Reset Password</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
