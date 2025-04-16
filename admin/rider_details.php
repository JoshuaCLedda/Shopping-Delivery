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
      <a href="registration_rider.php" class="btn btn-primary">Add Rider</a>
    </div>

    <?php include 'layouts/alert.php' ?>
    <div class="row">
      <div class="col-12">
        <div class="col-lg-12">
          <div class="card card-outline-primary">

            <div class="card-header bg-primary">
              <h5 class="mb-0 text-white">Rider Details</h5>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table datatable table-striped table-hover" id="datatable">
                  <thead>
                    <tr>
                      <th>Last Name</th>
                      <th>First Name</th>
                      <th>Phone</th>
                      <th>ORCR</th>
                      <th>Status</th>
                      <th>Manage</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT * FROM users
                    WHERE role = 2
                    ORDER BY created_at DESC";
                    $query = mysqli_query($db, $sql);

                    if (!mysqli_num_rows($query) > 0):
                      echo '<tr><td colspan="6" class="text-center">No Rider Found</td></tr>';
                    else:
                      while ($row = mysqli_fetch_array($query)):
                        $statusText = $row['status'] === 'active' ? 'Active' : 'Inactive';
                        $statusClass = $row['status'] === 'active' ? 'success' : 'danger';
                        ?>
                        <tr>
                          <td><?= htmlspecialchars($row['l_name']) ?></td>
                          <td><?= htmlspecialchars($row['f_name']) ?></td>
                          <td><?= htmlspecialchars($row['phone']) ?></td>
                          <td>
                            <?php if (!empty($row['orcr'])): ?>
                              <a href="uploads/<?= htmlspecialchars($row['orcr']) ?>" target="_blank"
                                class="btn btn-sm btn-primary rounded-pill px-3">
                                View ORCR
                              </a>
                            <?php else: ?>
                              <span class="badge bg-secondary rounded-pill px-3">No ORCR</span>
                            <?php endif; ?>
                          </td>



                          <td>
                            <span class="badge bg-<?= $statusClass ?> rounded-pill"><?= $statusText ?></span>
                          </td>

                          <td>
                            <div class="d-flex align-items-center gap-2">

                              <!-- Manage Dropdown -->
                              <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-warning dropdown-toggle"
                                  data-bs-toggle="dropdown" aria-expanded="false">
                                  Manage
                                </button>
                                <ul class="dropdown-menu">
                                  <?php if ($row['status'] !== 'active'): ?>
                                    <li><a class="dropdown-item"
                                        href="update_rider_status.php?u_id=<?= $row['u_id'] ?>&status=active">Approve</a></li>
                                  <?php endif; ?>
                                  <?php if ($row['status'] !== 'inactive'): ?>
                                    <li><a class="dropdown-item"
                                        href="update_rider_status.php?u_id=<?= $row['u_id'] ?>&status=inactive">Disapprove</a>
                                    </li>
                                  <?php endif; ?>
                                </ul>
                              </div>

                              <!-- View Ratings Button -->
                              <a href="riders_rating.php?u_id=<?= $row['u_id'] ?>" class="btn btn-sm btn-secondary">
                                <i class='bx bx-star'></i>
                              </a>

                              <!-- Edit Button -->
                              <a href="update_rider.php?u_id=<?= $row['u_id'] ?>" class="btn btn-sm btn-info">
                                <i class="bx bx-edit"></i>
                              </a>

                            </div>
                          </td>

                        </tr>
                        <?php
                      endwhile;
                    endif;
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