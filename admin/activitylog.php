<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}
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
                       
                        <li class="breadcrumb-item"><a href="#">Activity Log</a></li>

                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Activity Log</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity </th>
                                            <th>Details</th>
                                            <th>Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $index->getActivityLog();
                                        while ($row = mysqli_fetch_array($result)):
                                        ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                                <td><?= htmlspecialchars($row['activity']) ?></td>
                                                <td><?= htmlspecialchars($row['details']) ?></td>
                                                <td><?= date('F j, Y h:i A', strtotime($row['created_at'])) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
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