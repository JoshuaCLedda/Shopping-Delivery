<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
?>

<?php include 'layouts/header.php' ?>
<?php include 'layouts/sidebar.php' ?>
<?php include 'layouts/navbar.php' ?>
<div id="main">
    <div class="main-container">


        <div class="row">

            <div class="col-lg-12">



                <div class="col-lg-12">
                    <div class="card card-outline-primary" id="hidden">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Generate Report</h5>
                        </div>



                        <div class="card-body">
                            <div id="danger-alert" class="alert alert-danger" role="alert" style="display: none;"></div>


                            <form id="profileForm" action="../report/generate_report.php" method="POST" target="_blank">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Types of Report</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="report_type" class="form-control" required>
                                            <option value="">-- Select Report Type --</option>
                                            <option value="sales">Sales Report</option>
                                            <option value="orders">Orders Report</option>
                                            <option value="users">User Report</option>
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


<?php include 'layouts/footer.php' ?>