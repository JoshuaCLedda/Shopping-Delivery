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

        select, textarea, button {
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

        .star:hover, .star.selected {
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
</head>
<body>

<?php
include("connection/connect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to rate a rider!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch riders from the database
$query = "SELECT * FROM restaurant";
$result = mysqli_query($db, $query);
?>

<div class="rating-container">
    <h2>Rate a Stall</h2>

    <form action="submit_rider_rating.php" method="POST">
        <label for="rider">Select Stall:</label>
        <select name="rider_id" required>
            <option value="">-- Select Stall --</option>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['c_id']; ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Rate Rider:</label>
        <div class="rating">
            <i class="fa fa-star star" data-value="1"></i>
            <i class="fa fa-star star" data-value="2"></i>
            <i class="fa fa-star star" data-value="3"></i>
            <i class="fa fa-star star" data-value="4"></i>
            <i class="fa fa-star star" data-value="5"></i>
        </div>
        <input type="hidden" id="rider_rating" name="rider_rating" value="0">

        <label for="complaint">Feedback:</label>
        <textarea name="complaint" class="form-control" placeholder="Describe your complaint or feedback (if any)..."></textarea>

        <button type="submit">Submit Rating</button>
    </form>
</div>

<script>
    document.querySelectorAll(".star").forEach(star => {
        star.addEventListener("click", function () {
            let rating = this.getAttribute("data-value");
            document.getElementById("rider_rating").value = rating;

            // Remove "selected" class from all stars
            document.querySelectorAll(".star").forEach(s => {
                s.classList.remove("selected");
            });

            // Highlight selected stars
            for (let i = 1; i <= rating; i++) {
                document.querySelector(`.star[data-value='${i}']`).classList.add("selected");
            }
        });
    });
</script>

</body>
</html>
