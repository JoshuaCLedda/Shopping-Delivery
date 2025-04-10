<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
?>
<?php include 'layouts/header.php' ?>

<body class="fix-header fix-sidebar">


    <div id="main-wrapper">

        <?php include 'layouts/navbar.php' ?>


        <?php include 'layouts/sidebar.php' ?>


        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <h4>All Menu</h4>
                                <div class="table-responsive">

                                    <table id="myTable" class="table table-bordered table-hover align-middle">


                                        <thead>
                                            <tr>
                                                <th>Stall</th>
                                                <th>Dish</th>
                                                <th>Available Quantity</th>
                                                <th>Price</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM dishes ORDER BY d_id DESC";
                                            $query = mysqli_query($db, $sql);

                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="7"><center>No Menu</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $mql = "SELECT * FROM restaurant WHERE rs_id='" . $rows['rs_id'] . "'";
                                                    $newquery = mysqli_query($db, $mql);
                                                    $fetch = mysqli_fetch_array($newquery);

                                                    // Determine the status text
                                                    $statusText = ($rows['status'] == 'available') ? 'Available' : 'Not Available';

                                                    echo '<tr>
                    <td>' . htmlspecialchars($fetch['stall']) . '</td>
                    <td>' . htmlspecialchars($rows['title']) . '</td>
                    <td>' . htmlspecialchars($rows['available_quantity']) . '</td>
                   
                    <td>₱' . htmlspecialchars($rows['price']) . '</td>
                    <td>
                        <div class="col-md-3 col-lg-8 m-b-10">
                          <img src="Res_img/dishes/' . htmlspecialchars($rows['img']) . '" class="img-fluid" style="max-height: 50px; max-width: 100px;" />
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-' . ($rows['status'] == 'available' ? 'success' : 'danger') . '">' . $statusText . '</span>
                    </td>
                      
                    <td>
                      
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Status
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="update_status.php?menu_id=' . $rows['d_id'] . '&status=available">Mark as Available</a>
                                <a class="dropdown-item" href="update_status.php?menu_id=' . $rows['d_id'] . '&status=not_available">Mark as Not Available</a>
                            </div>
                        </div>
               
                        <a href="delete_menu.php?menu_del=' . $rows['d_id'] . '" class="btn btn-danger btn-flat btn-xs">
                            <i class="fa fa-trash-o" style="font-size:16px"></i>
                        </a> 
                        <a href="update_menu.php?menu_upd=' . $rows['d_id'] . '" class="btn btn-info btn-flat btn-xs">
                            <i class="fa fa-edit"></i>
                        </a>
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


    <?php include 'layouts/footer.php' ?>