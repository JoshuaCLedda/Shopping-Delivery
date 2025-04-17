<?php
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;

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
                                        $result = $index->getAllMenu();
                                        while ($row = mysqli_fetch_array($result)) {

                                            $status = $row['status']; // corrected $rows to $row
                                        
                                            switch ($status) {
                                                case 0:
                                                    $statusText = 'Active';
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 1:
                                                    $statusText = 'Inactive';
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                default:
                                                    $statusText = 'Unknown';
                                                    $badgeClass = 'bg-secondary';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['stall_name'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['dish_name'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['available_quantity'] ?? '') ?> Pieces</td>
                                                <td>₱ <?= htmlspecialchars($row['price'] ?? '') ?></td>
                                                <td>
                                                    <?php if (!empty($row['image'])): ?>
                                                        <img src="Res_img/<?= htmlspecialchars($row['image']) ?>"
                                                            alt="Dish Image" class="img-thumbnail" style="max-height: 60px;">
                                                    <?php else: ?>
                                                        <span class="text-muted">No Image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                                </td>
                                                <td>
                                                    <a href="update_menu.php?menu_upd=<?= $row['dishedId'] ?>"
                                                        class="btn btn-sm btn-info ms-2">
                                                        <i class="bx bx-edit"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                            <?php
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