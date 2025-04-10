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


            <div class="container-fluid my-2">


                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow-lg border-0 rounded-2xl">
                            <h4 class="mb-0">User Management</h4>

                            <div class="card-body">

                                <div class="table-responsive">

                                    <table id="myTable" class="table table-bordered table-hover align-middle">
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
                    $badgeClass = 'badge-danger';
                    break;
                case 2:
                    $roleText = 'Rider';
                    $badgeClass = 'badge-warning';
                    break;
                case 0:
                    $roleText = 'User';
                    $badgeClass = 'badge-info';
                    break;
                default:
                    $roleText = 'Unknown';
                    $badgeClass = 'badge-secondary';
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
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <a href="update_users.php?user_upd=' . $rows['u_id'] . '" class="btn btn-sm btn-info ms-2">
                            <i class="fa fa-edit"></i>
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

