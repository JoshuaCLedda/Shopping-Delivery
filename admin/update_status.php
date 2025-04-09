<?php
include("../connection/connect.php");
session_start();

if (isset($_GET['menu_id']) && isset($_GET['status'])) {
    $menu_id = mysqli_real_escape_string($db, $_GET['menu_id']);
    $new_status = ($_GET['status'] == 'available') ? 'available' : 'not_available';

    // Update the status directly
    $sql = "UPDATE dishes SET status = '$new_status' WHERE d_id = '$menu_id'";
    
    if (mysqli_query($db, $sql)) {
        header("Location: all_menu.php?status_update=success");
        exit();
    } else {
        error_log("SQL Error: " . mysqli_error($db));
        header("Location: all_menu.php?status_update=error");
        exit();
    }
} else {
    header("Location: all_menu.php?status_update=error");
    exit();
}
