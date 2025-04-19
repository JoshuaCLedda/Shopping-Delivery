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
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Activity Log</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity </th>
                                            <th>Details</th>
                                            <th>Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>John Doe</td>
                                            <td>Login</td>
                                            <td>User logged into the system</td>
                                            <td>April 17, 2025, 9:00 AM</td>

                                        </tr>
                                        <tr>
                                            <td>Jane Smith</td>
                                            <td>Update Profile</td>
                                            <td>Updated profile picture</td>
                                            <td>April 17, 2025, 9:15 AM</td>

                                        </tr>
                                        <tr>
                                            <td>Mike Johnson</td>
                                            <td>Logout</td>
                                            <td>User logged out</td>
                                            <td>April 17, 2025, 10:00 AM</td>

                                        </tr>
                                        <tr>
                                            <td>Emily Davis</td>
                                            <td>Order Placed</td>
                                            <td>Placed an order #4321</td>
                                            <td>April 17, 2025, 10:30 AM</td>

                                        </tr>
                                        <tr>
                                            <td>Chris Brown</td>
                                            <td>Password Change</td>
                                            <td>Changed account password</td>
                                            <td>April 17, 2025, 11:00 AM</td>

                                        </tr>
                                        <tr>
                                            <td>Olivia Wilson</td>
                                            <td>Added Stall</td>
                                            <td>Added a new stall "Sweet Treats"</td>
                                            <td>April 17, 2025, 11:30 AM</td>

                                        </tr>
                                        <tr>
                                            <td>Daniel Martinez</td>
                                            <td>Delete Order</td>
                                            <td>Deleted order #4319</td>
                                            <td>April 17, 2025, 12:00 PM</td>

                                        </tr>
                                        <tr>
                                            <td>Sophia Anderson</td>
                                            <td>Rating Submitted</td>
                                            <td>Submitted a 5-star rating for "Burger King"</td>
                                            <td>April 17, 2025, 12:30 PM</td>

                                        </tr>
                                        <tr>
                                            <td>Matthew Thomas</td>
                                            <td>Complaint Filed</td>
                                            <td>Filed a complaint against rider #324</td>
                                            <td>April 17, 2025, 1:00 PM</td>

                                        </tr>
                                        <tr>
                                            <td>Ava Taylor</td>
                                            <td>Login</td>
                                            <td>User logged into the system</td>
                                            <td>April 17, 2025, 1:30 PM</td>

                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'layouts/footer.php' ?>

</body>

</html>