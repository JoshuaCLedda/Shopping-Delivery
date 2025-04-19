<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

?>
<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>

<div id="main">
    <div class="main-container">


        <div class="col-lg-12">


            <div class="row g-4">

                <!-- Stalls -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-home text-primary display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Stalls</h6>
                                        <small class="text-muted">Total Restaurants</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-primary mb-0">
                                    <?php
                                    $sql = "SELECT * FROM restaurant";
                                    $result = mysqli_query($db, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dishes -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-restaurant text-success display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Dishes</h6>
                                        <small class="text-muted">Total Menu Items</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-success mb-0">
                                    <?php
                                    $sql = "SELECT * FROM dishes";
                                    $result = mysqli_query($db, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-user text-info display-5 me-3'></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Users</h6>
                                        <small class="text-muted">Registered Users</small>
                                    </div>
                                </div>
                                <h1 class="fw-bold text-info mb-0">
                                    <?php
                                    $sql = "SELECT * FROM users";
                                    $result = mysqli_query($db, $sql);
                                    echo mysqli_num_rows($result);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

        
            



                <div class="col-lg-6 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold mb-4">Recent Orders</h5>
                            <div class="table">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">ID</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Customer</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Food Item</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Status</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">1</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">Ana Reyes</h6>
                                                <span class="fw-normal">#ORD-1001</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Cheeseburger Combo</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-primary rounded-3 fw-semibold">Pending</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">2</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">Mark Villanueva</h6>
                                                <span class="fw-normal">#ORD-1002</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Spaghetti & Garlic Bread</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-secondary rounded-3 fw-semibold">On Hold</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">3</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">Maria Lopez</h6>
                                                <span class="fw-normal">#ORD-1003</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Beef Tapa with Rice</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-danger rounded-3 fw-semibold">Cancelled</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">4</h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-1">John Santos</h6>
                                                <span class="fw-normal">#ORD-1004</span>
                                            </td>
                                            <td class="border-bottom-0">
                                                <p class="mb-0 fw-normal">Chicken Wings & Fries</p>
                                            </td>
                                            <td class="border-bottom-0">
                                                <span class="badge bg-success rounded-3 fw-semibold">Completed</span>
                                            </td>
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
    <?php include '../admin/layouts/footer.php' ?>