<?php
include("connection/connect.php");
session_start();

// DEBUG: Check if POST data is received
if (empty($_POST)) {
    die("Error: No data received.");
}

// Ensure rider_id and rider_rating are set
if (!isset($_POST["rider_id"]) || !isset($_POST["rider_rating"]) || empty($_POST["rider_id"]) || empty($_POST["rider_rating"])) {
    die("Error: Missing rider or rating.");
}

$rider_id = intval($_POST["rider_id"]);
$rider_rating = intval($_POST["rider_rating"]);
$complaint = isset($_POST["complaint"]) ? trim($_POST["complaint"]) : null;

if ($rider_rating < 1 || $rider_rating > 5) {
    die("Error: Invalid rating value. Select a rating between 1 and 5.");
}

// ✅ Fetch rider's name from the users table
$query = "SELECT f_name, l_name FROM users WHERE u_id = ?";
$stmt = mysqli_prepare($db, $query);
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($db));
}
mysqli_stmt_bind_param($stmt, "i", $rider_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    die("Error: Rider not found.");
}

$rider_name = $row['f_name'] . ' ' . $row['l_name']; // Combine first and last name

// ✅ Insert rating into database with rider name
$sql = "INSERT INTO rating_rider (rider_id, rider_name, rating, complaint, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($db));
}
mysqli_stmt_bind_param($stmt, "isis", $rider_id, $rider_name, $rider_rating, $complaint);
$execute = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($execute) {
    echo "<script>alert('✅ Rider rating submitted successfully!'); window.location.href='your_orders.php';</script>";
} else {
    echo "<script>alert('❌ Error submitting rating. Please try again.'); window.location.href='your_orders.php';</script>";
}
