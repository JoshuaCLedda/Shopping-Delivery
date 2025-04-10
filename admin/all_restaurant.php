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
                                <h4>Registered Stalls</h4>


                                <div class="table-responsive">
                                    <table id="myTable" class="table table-bordered table-hover align-middle">
                                        <thead>
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
    $sql = "SELECT * FROM restaurant ORDER BY rs_id DESC";
    $query = mysqli_query($db, $sql);

    if (!mysqli_num_rows($query) > 0) {
        echo '<tr><td colspan="6"><center>No Restaurants</center></td></tr>';
    } else {
        while ($rows = mysqli_fetch_array($query)) {
            // Get category name
            $mql = "SELECT * FROM res_category WHERE c_id='" . $rows['c_id'] . "'";
            $res = mysqli_query($db, $mql);
            $row = mysqli_fetch_array($res);

            // Display the important details
            echo '<tr>
                    <td>' . $row['c_name'] . '</td>
                    <td>' . $rows['title'] . '</td>
                    <td>' . $rows['email'] . '</td>
                    <td>' . $rows['phone'] . '</td>
                    <td>' . date("F j, Y", strtotime($rows['date'])) . '</td>
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