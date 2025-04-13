<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
include("sidebar.php");
?>


<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/rider/sidebar.php' ?>
<?php include '../layouts/rider/navbar.php' ?>


<div id="main">
    <div class="main-container">

         
            <div class="row">
   
        <div class="col-lg-12">
                    <div class="card card-outline-primary" id="first">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Rider's Profile</h5>
                        </div>


            <div class="card-body">
                <div id="success-alert" class="alert alert-success" role="alert" style="display: none;">
                    Successfully Updated
                </div>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Name</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0" id="userName">John Doe</p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Stall</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0" id="userEmail">john.doe@dmmmsu.edu.ph</p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Role.</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0">EMP12345</p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Status</p></div>
                 
                </div>
                <hr>
             
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success" id="editButton" type="button">Edit</button>
                </div>
            </div>
        </div>

        <!-- Profile Edit -->
   
        <div class="col-lg-12">
                    <div class="card card-outline-primary" id="hidden" style="display:none;"> 

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Edit Profile</h5>
                        </div>



            <div class="card-body">
                <div id="danger-alert" class="alert alert-danger" role="alert" style="display: none;"></div>

                <form id="profileForm" action="#" method="POST">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">First Name</p></div>
                        <div class="col-sm-9"><input type="text" name="first_name" class="form-control" value="John"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">Last Name</p></div>
                        <div class="col-sm-9"><input type="text" name="last_name" class="form-control" value="Doe"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0"> Email</p></div>
                        <div class="col-sm-9"><input type="email" name="email" class="form-control" value="john.doe@dmmmsu.edu.ph"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">Stall.</p></div>
                        <div class="col-sm-9"><input type="number" name="employee_no" class="form-control" value="EMP12345"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">Password</p></div>
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
    </div>

    </div>


    </div>

    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const editButton = document.getElementById("editButton");
        const backButton = document.getElementById("backButton");
        const firstSection = document.getElementById("first");
        const hiddenSection = document.getElementById("hidden");
        const profileForm = document.getElementById("profileForm");
        const successAlert = document.getElementById("success-alert");
        const dangerAlert = document.getElementById("danger-alert");

        editButton.addEventListener("click", function () {
            firstSection.style.display = "none";
            hiddenSection.style.display = "block";
        });

        backButton.addEventListener("click", function () {
            hiddenSection.style.display = "none";
            firstSection.style.display = "block";
        });

        profileForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // Simulate form submission and show success alert
            successAlert.style.display = "block";
            setTimeout(() => {
                successAlert.style.display = "none";
                hiddenSection.style.display = "none";
                firstSection.style.display = "block";
                // You could also update the text content of the view with new input values here
            }, 1500);
        });
    });
</script>

    <?php include 'layouts/footer.php' ?>

