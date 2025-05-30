<?php
error_reporting(0);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header("Location: ../login.php");
    exit();
}


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    include "../admin/Main.php";
    $index = new Index;

    $query = "SELECT u_id, restaurant_id FROM users WHERE u_id = '$user_id'";
    $result = mysqli_query($index->con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
        header("Location: login.php"); // Redirect if no user data
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}

?>


<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>
<?php include '../layouts/sweetalert.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Stall</a></li>
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary" id="hidden">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Generate Report</h5>
                        </div>

                        <div class="card-body">
                            <div id="danger-alert" class="alert alert-danger" role="alert" style="display: none;"></div>
                            <form id="profileForm" action="../report/generateStallReport.php" method="POST" target="_blank">

                            <input type="hidden" name="restaurant_id" value="<?=$user['restaurant_id'];?>">


                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Types of Report</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="report_type" class="form-control" required>
                                            <option value="">-- Select Report Type --</option>
                                            <option value="sales">Sales Report</option>
                                            <option value="orders">Orders Report</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Start Date</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0">End Date</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Download Format</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="format" class="form-control">
                                            <option value="">-- Select Report Format --</option>
                                            <option value="pdf">PDF</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary mx-2" type="button" id="backButton">Back</button>

                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </div>
                        </div>

                        </form>


                    </div>
                </div>




            </div>
        </div>

    </div>

</div>
</div>


<?php include '../layouts/footer.php' ?>