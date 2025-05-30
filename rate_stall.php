<?php
session_start();
error_reporting(E_ALL);
include "admin/Main.php";
$index = new Index;


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (isset($_GET['transaction_id'])) {
    $transaction_id = intval($_GET['transaction_id']);
}

$stall_name = '';

// Get the restaurant (stall) details if ID is passed
if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];
    $stall = $index->getStallById($restaurant_id);
    if ($stall) {
        $stall_name = $stall['title'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $stall_name     = trim($_POST['stall_name']);
    $restaurant_id  = trim($_POST['restaurant_id']);
    $rating         = trim($_POST['rating']);
    $complaint      = trim($_POST['complaint']);
    $transaction_id  = $_POST['transaction_id'];


    if ($user_id && $restaurant_id && $rating && $stall_name) {
        // Call the method to insert rating
        $result = $index->addRestaurantRating($stall_name, $restaurant_id, $rating, $complaint, $user_id, $transaction_id);

        if ($result) {
            $_SESSION['message'] = ['type' => 'success', 'message' => 'Stall rating submitted successfully!'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Stall rating submission failed. Please try again.'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid input. Please check all required fields.'];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}


?>

<?php include 'layouts/header.php'; ?>


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
                <span class="ms-1">3. Rate Stall</span>
            </div>
        </div>

    </div>
</div>

<div class="container my-5">
    <div class="card shadow-lg rounded-4 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">
            <h3 class="text-center mb-4 text-primary">
                <i class='bx bxs-store-alt'></i> Rate a Stall
            </h3>

            <?php include 'layouts/sweetalert.php'; ?>

            <form action="" method="POST">
                <input type="hidden" name="transaction_id" value="<?= $transaction_id ?>">

                <div class="mb-3">
                    <label for="stall" class="form-label">
                        <i class='bx bx-restaurant'></i> Restaurant:
                    </label>
                    <input type="text" class="form-control" id="stall" name="stall_name"
                        value="<?= htmlspecialchars($stall_name); ?>" readonly>
                    <input type="hidden" name="restaurant_id" value="<?= $restaurant_id; ?>">
                </div>

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

                <div class="mb-3">
                    <label for="complaint" class="form-label">
                        <i class='bx bx-message-dots'></i> Feedback (Optional):
                    </label>
                    <textarea name="complaint" class="form-control" rows="4" placeholder="Share your thoughts..."></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class='bx bx-send'></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>