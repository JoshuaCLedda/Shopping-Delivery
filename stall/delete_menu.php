<?php
session_start();
include("../connection/connect.php");
error_reporting(0);

// Check if menu_del is set and is a number
if (isset($_GET['menu_del']) && is_numeric($_GET['menu_del'])) {
    $id = $_GET['menu_del'];

    // Prepare the statement
    $stmt = mysqli_prepare($db, "DELETE FROM dishes WHERE d_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id); // "i" for integer
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
}

if ($result) {
    $_SESSION['message'] = ['type' => 'success', 'message' => 'Rating submitted successfully!'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Rating submission failed! Please try again.'];
}

header("Location: menus.php");
exit();
?>
