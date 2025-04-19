<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;


if (isset($_GET['u_id'])) {
    $u_id = $_GET['u_id'];
    $status = $index->getRiderStatus($u_id); // Call the function to get the rider's status
}


if (isset($_POST['submit'])) {
    $u_id = intval($_POST['u_id']);

    $result = $index->terminateRider($u_id);

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Rider terminated successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Update failed! Please try again.'];
    }

    // Redirect to the appropriate page after termination
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        // If there is no referer, redirect to a default page
        header("Location: rider_details.php");
    }
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
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rider Details</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="d-flex justify-content-end my-2">
            <a href="rider_details.php" class="btn btn-primary">Back
            </a>
        </div>



        <?php
        $overall = $index->getRiderOverallRating($u_id);
        ?>

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4 bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class='bx bx-bar-chart-alt-2'></i> Overall Rating</h5>

                    <div class="mb-2">
                        <?php if (!empty($overall) && isset($overall['avg_rating']) && $overall['total'] > 0): ?>
                            <?php
                            $avg = round($overall['avg_rating'], 1);
                            $whole = floor($avg);
                            $has_half = ($avg - $whole) >= 0.5;

                            // Star color logic
                            $starClass = ($avg <= 1) ? 'text-danger' : 'text-warning';

                            // Stars display
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $whole):
                                    echo "<i class='bx bxs-star $starClass'></i>";
                                elseif ($has_half && $i == $whole + 1):
                                    echo "<i class='bx bxs-star-half $starClass'></i>";
                                else:
                                    echo "<i class='bx bx-star text-muted'></i>";
                                endif;
                            endfor;

                            // Feedback message
                            if ($avg >= 4.5) {
                                $feedback = "Outstanding performance! Keep exceeding expectations.";
                            } elseif ($avg >= 4) {
                                $feedback = "Great job! You're on the right track, keep it up!";
                            } elseif ($avg >= 3) {
                                $feedback = "Satisfactory work, but there's room for improvement.";
                            } elseif ($avg >= 2) {
                                $feedback = "Effort is needed to meet expectations. Focus on improving key areas.";
                            } else {
                                $feedback = "Significant improvement needed. A more focused approach is required.";
                            }
                            ?>

                            <p class="mt-2 text-muted"><?= $feedback ?></p>
                            <p class="mb-1"><strong>Average:</strong> <?= $avg ?>/5</p>
                            <p class="mb-0"><strong>Total Reviews:</strong> <?= $overall['total'] ?></p>
                        <?php else: ?>
                            <div class="card border-0 bg-danger shadow-sm rounded-4 p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class='text-white bx bx-info-circle fs-4 text-primary me-2'></i>
                                    <div>
                                        <p class="mb-0 text-white">This rider hasn't received any ratings yet.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>



                        <div>
                            <hr>

                            <h5 class="card-title text-danger mb-2"><i class='bx bx-user-x'></i> Terminate Rider</h5>
                            <p class="text-muted small">You can terminate this rider if performance is consistently
                                below
                                expectations.</p>
                            <!-- Trigger Button -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#terminateModal" <?php if ($status === 'banned')
                                    echo 'disabled'; ?>>
                                <i class="bx bx-trash"></i>
                                <?php echo $status === 'banned' ? 'Rider already terminated' : 'Terminate Rider'; ?>
                            </button>


                            <div class="modal fade" id="terminateModal" tabindex="-1"
                                aria-labelledby="terminateModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="" method="POST">
                                        <input type="hidden" name="u_id" value="<?= $u_id ?>">
                                        <div class="modal-content rounded-4 shadow-sm">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="terminateModalLabel"><i
                                                        class='bx bx-user-x'></i> Terminate Rider</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to <strong>terminate this rider</strong>? This
                                                    action cannot be undone.</p>
                                                <p class="text-muted small mb-0">Only proceed if the rider’s performance
                                                    has
                                                    consistently failed to meet expectations.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="submit" class="btn btn-danger btn-sm"><i
                                                        class="bx bx-trash"></i> Yes, Terminate</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            </form>

                        </div>


                    </div>
                </div>
            </div>



            <div class="row">
                <!-- starts here -->

                <?php
                $result = $index->getRiderRatings($u_id);

                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm rounded-4">
                                <div class="card-body">
                                    <!-- Star Rating -->
                                    <div class="mb-2">
                                        <?php
                                        $rating = intval($row['rating']);
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($i <= $rating): ?>
                                                <i class='bx bxs-star text-warning'></i>
                                            <?php else: ?>
                                                <i class='bx bx-star text-muted'></i>
                                            <?php endif;
                                        endfor;
                                        ?>
                                    </div>

                                    <!-- Comment -->
                                    <p class="mb-2 text-muted fst-italic">"<?= htmlspecialchars($row['complaint']) ?>"</p>

                                    <!-- Rater and Rider Info -->
                                    <p class="mb-1"><strong>Rider:</strong>
                                        <?= htmlspecialchars($row['f_name'] . ' ' . $row['l_name']) ?></p>

                                    <p class="mb-1"><strong>Customer:</strong> <?= htmlspecialchars($row['rider_name']) ?></p>

                                    <!-- Date -->
                                    <p class="text-end text-muted small mb-0">
                                        <i class='bx bx-calendar'></i>
                                        <?= date('F j, Y', strtotime($row['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                else:
                    ?>

                    <?php
                endif;
                ?>





            </div>




        </div>
    </div>
    <?php include 'layouts/footer.php' ?>