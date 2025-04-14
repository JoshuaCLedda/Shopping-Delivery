<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if 'res_del' is set in the URL
if (isset($_GET['res_del'])) {
    // Escape the input to prevent SQL injection
    $res_id = mysqli_real_escape_string($db, $_GET['res_del']);

    // Perform the delete query
    $result = mysqli_query($db, "DELETE FROM restaurant WHERE rs_id = '$res_id'");

    // Set session message based on result
    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Restaurant deleted successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Deletion failed! Please try again.'];
    }
} else {
    $_SESSION['message'] = ['type' => 'warning', 'message' => 'Invalid request.'];
}

// Redirect to all_restaurant.php
header("Location: all_restaurant.php");
exit();
?>
