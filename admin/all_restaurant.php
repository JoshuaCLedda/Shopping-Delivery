<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

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



            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">



                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Registered Stalls</h4>
                                </div>

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered table">
                                        <thead class="">
                                            <tr>
                                                <th>Category</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM restaurant order by rs_id desc";
                                            $query = mysqli_query($db, $sql);

                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<td colspan="6"><center>No Restaurants</center></td>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    // Get category name
                                                    $mql = "SELECT * FROM res_category where c_id='" . $rows['c_id'] . "'";
                                                    $res = mysqli_query($db, $mql);
                                                    $row = mysqli_fetch_array($res);

                                                    // Display the important details
                                                    echo ' <tr>
                                <td>' . $row['c_name'] . '</td>
                                <td>' . $rows['title'] . '</td>
                                <td>' . $rows['email'] . '</td>
                                <td>' . $rows['phone'] . '</td>
                                <td>' . $rows['date'] . '</td>
                                <td>
                                    <a href="delete_restaurant.php?res_del=' . $rows['rs_id'] . '" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i class="fa fa-trash-o" style="font-size:16px"></i></a> 
                                    <a href="update_restaurant.php?res_upd=' . $rows['rs_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i class="fa fa-edit"></i></a>
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

    </div>

    </div>

    </div>
    <?php include 'layouts/footer.php' ?>


</body>

</html>