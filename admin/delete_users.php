<?php
include("../connection/connect.php");
session_start();
error_reporting(0);

if (isset($_GET['user_del'])) {
    $user_id = $_GET['user_del'];

    $result = mysqli_query($db, "DELETE FROM users WHERE u_id = '$user_id'");

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'User Deleted Successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to Delete user. Please try again.'];
    }

    // Redirect back to users list
    header("Location: all_users.php");
    exit();
} else {
    // If no user ID was provided
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid request.'];
    header("Location: all_users.php");
    exit();
}
?>
