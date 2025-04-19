<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../connection/connect.php");

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
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Passwords do not match!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        }
        elseif(strlen($password) < 6) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Password must be at least 6 characters!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        }
        elseif(strlen($phone) < 10) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid phone number!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid email address!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        }
        elseif(mysqli_num_rows($check_username) > 0) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Username already exists!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        }
        elseif(mysqli_num_rows($check_email) > 0) {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Email already exists!'];
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the same page
            exit();
        }
        else
        {
            // Secure password hashing using bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user details into the database
            $mql = "INSERT INTO users(username, f_name, l_name, email, phone, password, address, security_questions, answer) 
                    VALUES('$username', '$firstname', '$lastname', '$email', '$phone', '$hashed_password', '$address', '$security_questions', '$answer')";

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
        
        <?php include 'layouts/alert.php'; ?>


        <div class="row justify-content-center">
                <div class="col-md-12">
          <div class="card card-outline-primary">
                    
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Register New User</h5>
                    </div>

                    <div class="widget card-body shadow-sm">

                        <div class="widget-body">
                         
                                    <form action="add_user" method="POST">
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
            



