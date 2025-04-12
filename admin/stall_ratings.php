<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;

?>

<?php include 'layouts/header.php' ?>


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
                        <li class="breadcrumb-item active" aria-current="page">Ratings</li>
                    </ol>
                </nav>
            </div>
        </div>



        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                <div class="card card-outline-primary">
   
   <div class="card-header bg-primary">
<h5 class="mb-0 text-white">Stalls Raiting</h5>
</div>

<div class="card-body">
              <div class="table-responsive">
                <table class="table datatable table-striped table-hover" 
                id="datatable">
                  <thead>
                   
                                        <tr>
                                            <th>Stall Name</th>
                                            <th>Rater's Name</th>
                                            <th>Rating</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            $result = $index->getStallRatings();
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo '<tr>';
                                                echo '<td>' . $row['restaurant'] . '</td>';
                                                echo '<td>' . $row['f_name'] . ' ' . $row['l_name'] . '</td>';
                                                echo '<td>' . $row['rating'] . '</td>';
                                                
                                                echo 
                                                '<td>
                                                <a href="" class="btn btn-sm btn-success">View</a>
                                                
                                                </td>';

                                    

                                                echo '</td>';

                                                echo '</tr>';
                                            }
                                            ?>

<td>
                                      </td>

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