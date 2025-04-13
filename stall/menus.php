<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
?>
<?php include '../admin/layouts/header.php' ?>
<?php include '../layouts/stall/sidebar.php' ?>
<?php include '../layouts/stall/navbar.php' ?>

<div id="main">
    <div class="main-container">

        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Stall</a></li>
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
                                <table class="table datatable table-striped table-hover" id="datatable">
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
                                        ?>

                                        <?php if (!mysqli_num_rows($query) > 0): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No Menu</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php while ($rows = mysqli_fetch_array($query)): ?>
                                                <?php
                                                $mql = "SELECT * FROM restaurant WHERE rs_id='" . $rows['rs_id'] . "'";
                                                $newquery = mysqli_query($db, $mql);
                                                $fetch = mysqli_fetch_array($newquery);

                                                $status = $rows['status'];
                                                switch ($status) {
                                                    case 'available':
                                                        $statusText = 'Available';
                                                        $badgeClass = 'bg-success';
                                                        break;
                                                    case 'not_available':
                                                    default:
                                                        $statusText = 'Not Available';
                                                        $badgeClass = 'bg-danger';
                                                        break;
                                                }
                                                ?>

                                                <tr>
                                                    <td><?= htmlspecialchars($fetch['stall']) ?></td>
                                                    <td><?= htmlspecialchars($rows['title']) ?></td>
                                                    <td><?= htmlspecialchars($rows['available_quantity']) ?></td>
                                                    <td>₱<?= htmlspecialchars($rows['price']) ?></td>
                                                    <td>
    <div class="ratio ratio-4x3" style="max-width: 100px;">
        <img src="../admin/Res_img/dishes/<?= htmlspecialchars($rows['img']) ?>"
             class="rounded shadow-sm object-fit-cover w-100 h-100"
             alt="Dish Image">
    </div>
</td>

                                                    <td>
                                                        <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="delete_menu.php?menu_del=<?= $rows['d_id'] ?>"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                        <a href="update_menu.php?menu_upd=<?= $rows['d_id'] ?>"
                                                            class="btn btn-sm btn-info">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php endif; ?>


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


<?php include '../admin/layouts/footer.php' ?>