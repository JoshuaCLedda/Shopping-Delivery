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
            <a href="add_user.php" class="btn btn-primary">Add User</a>
        </div>




        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">User Management</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover"
                                    id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Name</th>

                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Phone</th>
                                            <th>Reg-Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM users ORDER BY u_id DESC";
                                        $query = mysqli_query($db, $sql);

                                        if (!mysqli_num_rows($query) > 0) {
                                            echo '<tr><td colspan="9" class="text-center">No Users Found</td></tr>';
                                        } else {
                                            while ($rows = mysqli_fetch_array($query)) {

                                                // Role badge logic
                                                switch ($rows['role']) {
                                                    case 1:
                                                        $roleText = 'Admin';
                                                        $badgeClass = 'badge bg-danger rounded-pill';
                                                        break;
                                                    case 2:
                                                        $roleText = 'Rider';
                                                        $badgeClass = 'badge bg-warning text-dark rounded-pill';
                                                        break;
                                                    case 0:
                                                        $roleText = 'User';
                                                        $badgeClass = 'badge bg-info text-dark rounded-pill';
                                                        break;
                                                    default:
                                                        $roleText = 'Unknown';
                                                        $badgeClass = 'badge bg-secondary rounded-pill';
                                                        break;
                                                }
                                                
                                                echo '<tr>
                                                <td>' . $rows['username'] . '</td>
                                                <td>' . $rows['f_name'] . ' ' . $rows['l_name'] . '</td>
                                                <td>' . $rows['email'] . '</td>
                                                <td><span class="badge ' . $badgeClass . '">' . $roleText . '</span></td>
                                                <td>' . $rows['phone'] . '</td>
                                                <td>' . date("F j, Y", strtotime($rows['date'])) . '</td>
                                                <td>
                                                    <a href="delete_users.php?user_del=' . $rows['u_id'] . '" class="btn btn-sm btn-danger">
                                                        <i class="bx bx-trash"></i>

                                                    </a>
                                                    <a href="update_users.php?user_upd=' . $rows['u_id'] . '" class="btn btn-sm btn-info ms-2">
                                                    <i class="bx bx-edit"></i>
                                                    </a>
                    </td>
                </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>



                                </table>
                            </div> <!-- table-responsive -->
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div> <!-- col -->
            </div> <!-- row -->
        </div> <!-- container -->

    </div>
</div>

</div>


</div>

</div>

<?php include 'layouts/footer.php' ?>