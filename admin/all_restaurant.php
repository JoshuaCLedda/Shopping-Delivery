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
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rider Details</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="add_restaurant.php" class="btn btn-primary">Add Stall</a>
        </div>




                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                        <div class="card card-outline-primary">
   
   <div class="card-header bg-primary">
<h5 class="mb-0 text-white">Stall Details</h5>
</div>

   <div class="card-body">
   <a href="delete_users.php?user_del=' . $rows['u_id'] . '" c

                                <div class="table-responsive">
  <table class="table datatable table-striped table-hover" id="datatable">
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
                        <a href="delete_restaurant.php?res_del=' . $rows['rs_id'] . '" class="btn btn-sm btn-danger">
                          <i class="bx bx-trash"></i>
                        <a href="update_restaurant.php?res_upd=' . $rows['rs_id'] . '" class="btn btn-sm btn-info ms-2">
                        <i class="bx bx-edit"></i>
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