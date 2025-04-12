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
                <table class="table datatable table-striped table-hover" 
                id="datatable">
                  <thead>
                                                <tr>
                                                    <th>Last Name</th>
                                                    <th>First Name</th>
                                                    <th>Email</th>
                                                    <th>Contact Number</th>
                                                    <th>Status</th>
                                                    <th>Manage</th>

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
                                                            
                                                            <td>
                                                                <span class="badge badge-' . ($rows['status'] == 'active' ? 'success' : 'danger') . '">' . $statusText . '</span>
                                                             </td>
                                                            <td>
                                                             
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Manage
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="update_rider_status.php?id=' . $rows['id'] . '&status=active">Approve</a>
                                                                        <a class="dropdown-item" href="update_rider_status.php?id=' . $rows['id'] . '&status=inactive">Dissaprove</a>
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
    </div>
    <?php include 'layouts/footer.php' ?>

</body>

</html>