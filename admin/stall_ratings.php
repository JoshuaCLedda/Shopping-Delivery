<?php
session_start();
error_reporting(E_ALL);
include "Main.php";
$index = new Index;
?>

<?php include 'layouts/header.php'; ?>
<?php include 'layouts/sidebar.php'; ?>
<?php include 'layouts/navbar.php'; ?>

<div id="main">
    <div class="main-container">
        
        <!-- Breadcrumb -->
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

        <!-- Ratings Table -->
        <div class="row">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Stall Ratings</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable table-striped table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Stall Name</th>
                                            <th>Rater's Name</th>
                                            <th>Rating</th>
                                            <th>Date</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $index->getStallRatings();
                                        while ($row = mysqli_fetch_array($result)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['restaurant']) ?></td>
                                                <td><?= htmlspecialchars($row['f_name'] . ' ' . $row['l_name']) ?></td>
                                                <td><i class="bx bxs-star text-warning"></i> <?= htmlspecialchars($row['rating']) ?></td>
                                                <td><?= date('F j, Y', strtotime($row['created_at'])) ?></td>
                                                <td>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary viewRatingBtn"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#exampleModal"
                                                            data-restaurant="<?= htmlspecialchars($row['restaurant']) ?>"
                                                            data-name="<?= htmlspecialchars($row['f_name'] . ' ' . $row['l_name']) ?>"
                                                            data-rating="<?= htmlspecialchars($row['rating']) ?>"
                                                            data-comment="<?= htmlspecialchars($row['complaint']) ?>">
                                                        View
                                                    </button>
                                                </td>
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ratings Information</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="widget-body">
                            <form>
                                <div class="form-body">
                                    <div class="row p-t-20">

                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label>Restaurant Name</label>
                                                <input type="text" id="modalRestaurant" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label>Rater's Name</label>
                                                <input type="text" id="modalName" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label>Total Rating (Total of 5)</label>
                                                <input type="text" id="modalRating" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label>Comments</label>
                                            <textarea class="form-control" id="modalComment" rows="4" readonly></textarea>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- jQuery Script for Modal -->
<script>
$(document).ready(function() {
    $('.viewRatingBtn').on('click', function() {
        var restaurant = $(this).data('restaurant');
        var name = $(this).data('name');
        var rating = $(this).data('rating');
        var comment = $(this).data('comment');

        $('#modalRestaurant').val(restaurant);
        $('#modalName').val(name);
        $('#modalRating').val(rating);
        $('#modalComment').val(comment);
    });
});
</script>

<?php include 'layouts/footer.php'; ?>
