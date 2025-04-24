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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Rider</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container my-4">
    <div class="row text-center">
        <div class="col-md-4 mb-2">
            <div class="border p-2 rounded">
                <i class='bx bx-store-alt'></i>
                <a href="#" class="text-decoration-none text-dark fw-bold ms-1">1. Choose Stall</a>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="border p-2 rounded">
                <i class='bx bx-bowl-hot'></i>
                <a href="#" class="text-decoration-none text-dark fw-bold ms-1">2. Order Food</a>
            </div>
        </div>
        <div class="col-md-4 mb-2">
    <div class="bg-primary text-white p-2 rounded fw-bold">
        <i class='bx bxs-star'></i>
        <span class="ms-1">3. Rate Rider</span>
    </div>
</div>

    </div>
</div>


<div class="container my-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="text-center mb-4">
                <i class='bx bx-star text-warning'></i> Rate Your Rider
            </h3>

            <?php include 'layouts/alert.php'; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="rider" class="form-label">
                        <i class='bx bx-user'></i> Rider:
                    </label>
                    <input type="text" class="form-control" id="rider" name="rider_name"
                           value="<?= htmlspecialchars($rider_name); ?>" readonly>
                    <input type="hidden" name="rider_id" value="<?= $rider_id; ?>">
                </div>

                <!-- Rating Stars -->
                <div class="mb-3">
                    <label class="form-label d-block">
                        <i class='bx bx-like'></i> Rate Rider:
                    </label>
                    <div class="btn-group w-100 justify-content-center" role="group" aria-label="Star Rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" class="btn-check" name="rating" id="star<?= $i ?>" value="<?= $i ?>" required>
                            <label class="btn btn-outline-warning" for="star<?= $i ?>"><i class='bx bxs-star'></i></label>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Feedback -->
                <div class="mb-3">
                    <label for="complaint" class="form-label">
                        <i class='bx bx-message-rounded-dots'></i> Feedback (Optional):
                    </label>
                    <textarea name="complaint" class="form-control" id="complaint" rows="4" placeholder="Write your feedback..."></textarea>
                </div>

                <!-- Submit -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-send'></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
