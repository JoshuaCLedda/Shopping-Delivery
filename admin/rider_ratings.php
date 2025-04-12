<?php
session_start();
error_reporting(E_ALL);
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
                        <li class="breadcrumb-item active" aria-current="page">Riders Rating</li>
                    </ol>
                </nav>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Rider Ratings</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover"
                                    id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Rider's Name</th>
                                            <th>Rater's Name</th>
                                            <th>Rating</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $index->getRidersRatings();
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row['rider_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['f_name'] . ' ' . $row['l_name']) . '</td>';
                                            echo '<td><i class="bx bxs-star text-warning"></i> ' . htmlspecialchars($row['rating']) . '</td>';
                                            echo '<td>
            <a href="#" class="btn btn-sm btn-success">View</a>
          </td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                </section>


            </div>
        </div>
    </div>

    <?php include 'layouts/footer.php' ?>