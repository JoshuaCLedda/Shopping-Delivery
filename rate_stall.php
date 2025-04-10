<?php
session_start();
error_reporting(E_ALL);
include "admin/Index.php";
$index = new Index;


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Default value
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

    if ($user_id && $restaurant_id && $rating && $stall_name) {
        // Call the method to insert rating
        $result = $index->addRestaurantRating($stall_name, $restaurant_id, $rating, $complaint, $user_id);

        if ($result) {
            $_SESSION['message'] = ['type' => 'success', 'message' => 'Restaurant rating submitted successfully!'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Restaurant rating submission failed. Please try again.'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid input. Please check all required fields.'];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Rider</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .rating-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        select,
        textarea,
        button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .rating {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        .star {
            font-size: 30px;
            cursor: pointer;
            color: gray;
            transition: color 0.3s;
        }

        .star:hover,
        .star.selected {
            color: gold;
        }

        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
    <!-- for the meantime -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>



    <div class="rating-container">
        <h2>Rate a Stall</h2>

        <?php include 'layouts/alert.php' ?>
        <form action="" method="POST">

            <div class="mb-3">
                <label for="rider" class="form-label">Select Restaurant:</label>
                <input type="text" class="form-control" id="rider" name="stall_name"
                    value="<?php echo htmlspecialchars($stall_name); ?>" readonly>
                <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">

            </div>

            <div class="mb-3">
                    <label class="form-label d-block">Rate Stall (1 to 5):</label>
                    <div class="d-flex justify-content-between">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" id="rating<?= $i ?>" value="<?= $i ?>" required>
                                <label class="form-check-label" for="rating<?= $i ?>"><?= $i ?></label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>


            <label for="complaint">Feedback:</label>
            <textarea name="complaint" class="form-control" placeholder="Describe your complaint or feedback (if any)..."></textarea>

            <button type="submit" class="my-2">Submit Rating</button>
        </form>
    </div>



</body>

</html>