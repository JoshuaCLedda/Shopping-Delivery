<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>All Menu</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">All Riders</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Surname</th>
                                                <th>Given Name</th>
                                                <th>Email</th>
                                                <th>Contact Number</th>
                                                <th>Address</th>
                                                <th>status</th>
                                                <th>Action</th>
                                                  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM riders ORDER BY id DESC";
                                            $query = mysqli_query($db, $sql);

                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="7"><center>No Menu</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $mql = "SELECT * FROM riders WHERE id='" . $rows['id'] . "'";
                                                    $newquery = mysqli_query($db, $mql);
                                                    $fetch = mysqli_fetch_array($newquery);

                                                    // Determine the status text
                                                    $statusText = ($rows['status'] == 'active') ? 'active' : 'Inactive';

                                                    echo '<tr>
                                                            <td>' . htmlspecialchars($fetch['l_name']) . '</td>
                                                            <td>' . htmlspecialchars($rows['f_name']) . '</td>
                                                            <td>' . htmlspecialchars($rows['email']) . '</td>
                                                            <td>' . htmlspecialchars($rows['phone']) . '</td>
                                                            <td>' . htmlspecialchars($rows['address']) . '</td>
                                                            <td>' . htmlspecialchars($rows['status']) . '</td>
                                                            
                                                            <td>
                                                                <span class="badge badge-' . ($rows['status'] == 'active' ? 'success' : 'danger') . '">' . $statusText . '</span>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Change Status
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="update_rider_status.php?id=' . $rows['id'] . '&status=active">Mark as Active</a>
                                                                        <a class="dropdown-item" href="update_rider_status.php?id=' . $rows['id'] . '&status=inactive">Mark as Inactive</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                         
                                                        </tr>';
                                                }
                                            }
                                            ?>
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
    
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>