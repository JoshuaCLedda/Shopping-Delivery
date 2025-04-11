<?php
session_start();
error_reporting(E_ALL);
include "../admin/Main.php";
$index = new Index;

// get the id from the rider
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['submit'])) {
    // Collect form data
    $rider_id = $_POST['rider_id'];
    $transaction_id    = $_POST['transaction_id'];


    // Call the model function with image path
    $result = $index->acceptOrderRider(
        $rider_id,
        $transaction_id

    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Order Accepted Succesfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Please Try Again.'];
    }

    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Panel</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<?php include '../layouts/header.php' ?>

<body class="fix-header fix-sidebar">


    <div id="main-wrapper">
        <?php include '../layouts/navbar.php' ?>
        <?php include '../layouts/rider/sidebar.php' ?>
        <div class="page-wrapper">


            <div class="container-fluid py-3">



                <div class="row">


                    <div class="col-lg-12">
                        <!-- Profile View -->
                        <div class="card mb-4" id="first">
                            <div class="card-body">
                                <div id="success-alert" class="alert alert-success" role="alert" style="display: none;">
                                    Successfully Updated
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0" id="userName">John Doe</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Stall</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0" id="userEmail">john.doe@dmmmsu.edu.ph</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Role.</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">EMP12345</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Status</p>
                                    </div>

                                </div>
                                <hr>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-success" id="editButton" type="button">Edit</button>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Edit -->
                        <div class="card mb-4" id="hidden" style="display:none;">
                            <div class="card-body">
                                <div id="danger-alert" class="alert alert-danger" role="alert" style="display: none;"></div>

                                <form id="profileForm" action="#" method="POST">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">First Name</p>
                                        </div>
                                        <div class="col-sm-9"><input type="text" name="first_name" class="form-control" value="John"></div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Last Name</p>
                                        </div>
                                        <div class="col-sm-9"><input type="text" name="last_name" class="form-control" value="Doe"></div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0"> Email</p>
                                        </div>
                                        <div class="col-sm-9"><input type="email" name="email" class="form-control" value="john.doe@dmmmsu.edu.ph"></div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Stall.</p>
                                        </div>
                                        <div class="col-sm-9"><input type="number" name="employee_no" class="form-control" value="EMP12345"></div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Password</p>
                                        </div>
                                        <div class="col-sm-9"><input type="password" name="password" class="form-control"></div>
                                    </div>
                                    <hr>

                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary mx-2" id="backButton" type="button">Back</button>
                                        <button class="btn btn-success" id="saveButton" type="submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>




                    </div>
                </div>

            </div>







        </div>
        <footer class="footer">© 2024-DMMMSU-NLUC-BSIS-STUDENT</footer>
    </div>


    <?php include '../layouts/footer.php' ?>