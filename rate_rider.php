<?php
session_start();
error_reporting(E_ALL);
include "admin/Main.php";
$index = new Index;


$rider_name = ''; 

if (isset($_GET['rider_id'])) {
    $rider_id = $_GET['rider_id'];

    // Get rider data (assuming this function exists and returns an array or object)
    $rider = $index->getRiderById($rider_id);

    if ($rider) {
        $rider_name = $rider['f_name'] . ' ' . $rider['l_name'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rider_id  = $_POST['rider_id'];
    $rider_name = $_POST['rider_name'];
    $rating     = $_POST['rating'];
    $complaint  = $_POST['complaint'];

    // Call the method to insert rating
    $result = $index->addRiderRating($rider_id, $rider_name,  $rating, $complaint);

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Rating submitted successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Rating submission failed! Please try again.'];
    }

    header("Location: rate_rider.php?rider_id=" . urlencode($rider_id));
    exit();
}

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<div class="container my-5">
    <div class="card shadow rounded-4">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4">Rate a Rider</h3>
        <?php include 'layouts/alert.php' ?>

            <form action="" method="POST">

                <div class="mb-3">
                    <label for="rider" class="form-label">Rider:</label>
                    <input type="text" class="form-control" id="rider" name="rider_name"
                        value="<?php echo htmlspecialchars($rider_name); ?>" readonly>
                    <input type="hidden" name="rider_id" value="<?php echo $rider_id; ?>">

                </div>


                <div class="mb-3">
                    <label class="form-label d-block">Rate Rider (1 to 5):</label>
                    <div class="d-flex justify-content-between">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" id="rating<?= $i ?>" value="<?= $i ?>" required>
                                <label class="form-check-label" for="rating<?= $i ?>"><?= $i ?></label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="my-3">
                    <label for="complaint" class="form-label">Feedback:</label>
                    <textarea name="complaint" class="form-control" rows="4" placeholder="Describe your complaint or feedback (if any)..."></textarea>
                </div>

                <div class="text-end my-2">
                    <button type="submit" class="btn btn-primary px-4 mt-2">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>



</body>

</html>