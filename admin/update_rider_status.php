<?php
include("../connection/connect.php");
session_start();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $new_status = ($_GET['status'] == 'active') ? 'active' : 'inactive';

    // Update the status directly
    $sql = "UPDATE riders SET status = '$new_status' WHERE id  = '$id'";
    
    if (mysqli_query($db, $sql)) {
        header("Location: rider_details.php?status_update=success");
        exit();
    } else {
        error_log("SQL Error: " . mysqli_error($db));
        header("Location: rider_details.php?status_update=error");
        exit();
    }
} else {
    header("Location: rider_details.php?status_update=error");
    exit();
}
