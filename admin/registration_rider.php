<?php
session_start();
error_reporting(E_ALL);
include "Index.php";
$index = new Index;
// backend
if (isset($_POST['submit'])) {
    $username          = $_POST['username'];
    $f_name            = $_POST['f_name'];
    $l_name            = $_POST['l_name'];
    $address           = $_POST['address'];
    $email             = $_POST['email'];
    $phone             = $_POST['phone'];
    $password_plain    = $_POST['password'];
    $security_question = $_POST['security_question'];
    $security_answer   = $_POST['security_answer'];

    $result = $index->addRider(
        $username,
        $f_name,
        $l_name,
        $address,
        $email,
        $phone,
        $password_plain,
        $security_question,
        $security_answer
    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Registration successful!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Registration failed! Please try again.'];
    }
    header("Location: registration_rider.php");
    exit();
}
?>
<?php include 'layouts/header.php' ?>

<body class="fix-header fix-sidebar">

<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
</div>
<div id="main-wrapper">
    <?php include 'layouts/navbar.php' ?>


    <?php include 'layouts/sidebar.php' ?>

    <div class="page-wrapper">


    <div class="container-fluid my-1">

            <?php include 'layouts/alert.php'; ?>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <!-- Card starts here -->
                    <div class="widget card shadow-sm">
                        <div class="widget-body">
                            <div class="text-left">
                                <h4>Rider Registration</h4>
                            </div>
                            <form action="" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">User-Name</label>
                                        <input class="form-control" type="text" name="username" id="example-text-input" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">First Name</label>
                                        <input class="form-control" type="text" name="f_name" id="example-text-input" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Last Name</label>
                                        <input class="form-control" type="text" name="l_name" id="example-text-input-2" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Address</label>
                                        <input class="form-control" type="text" name="address" id="example-text-input-2" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Email Address</label>
                                        <input type="text" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputEmail1">Phone</label>
                                        <input class="form-control" type="number" name="phone" id="example-tel-input-3" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="exampleInputPassword1">Confirm Password</label>
                                        <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2" required>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label for="security_question">Security Question</label>
                                        <select name="security_question" class="form-control" required>
                                            <option value="Your first pet's name?">Your first pet's name?</option>
                                            <option value="Your mother's maiden name?">Your mother's maiden name?</option>
                                            <option value="City you were born in?">City you were born in?</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label for="security_answer">Answer</label>
                                        <input type="text" class="form-control" name="security_answer" placeholder="Answer" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" value="Register" name="submit" class="btn btn-success">Register Rider</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <?php include 'layouts/footer.php' ?>