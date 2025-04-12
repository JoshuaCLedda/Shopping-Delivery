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
                        <li class="breadcrumb-item active" aria-current="page">Menu</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="d-flex justify-content-end my-2">
            <a href="add_menu.php" class="btn btn-primary">Add Menu</a>
        </div>

    <div class="row">
      <div class="col-12">
        <div class="col-lg-12">
          <div class="card card-outline-primary">
   
            <div class="card-header bg-primary">
        <h5 class="mb-0 text-white">Menu Details</h5>
    </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table datatable table-striped table-hover" 
                id="datatable">
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
                        <span class="text-black badge badge-' . ($rows['status'] == 'available' ? 'success' : 'danger') . '">' . $statusText . '</span>
                    </td>
                      
                    <td>
                      
                        <div class="btn-group">
                           <button type="button" class="btn btn-sm btn-warning dropdown-toggle" 
        data-bs-toggle="dropdown" aria-expanded="false">
        Status
    </button>
                              <ul class="dropdown-menu">
                                <a class="dropdown-item" href="update_status.php?menu_id=' . $rows['d_id'] . '&status=available">Mark as Available</a>
                                <a class="dropdown-item" href="update_status.php?menu_id=' . $rows['d_id'] . '&status=not_available">Mark as Not Available</a>
                            </ul>
                        </div>
               
                        <a href="delete_menu.php?menu_del=' . $rows['d_id'] . '" class="btn btn-sm btn-danger">
                   <i class="bx bx-trash"></i>
                        </a> 
                        <a href="update_menu.php?menu_upd=' . $rows['d_id'] . '" class="btn btn-sm btn-info">
                             <i class="bx bx-edit"></i
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