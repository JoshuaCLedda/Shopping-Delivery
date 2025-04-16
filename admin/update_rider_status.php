<?php
include("../connection/connect.php");
session_start();

if (isset($_GET['u_id']) && isset($_GET['status'])) {
    $id = mysqli_real_escape_string($db, $_GET['u_id']);
    $new_status = ($_GET['status'] == 'active') ? 'active' : 'inactive';

    $sql = "UPDATE users SET status = '$new_status' WHERE u_id  = '$id'";
    
    if (mysqli_query($db, $sql)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'message' => 'Rider status updated successfully.'
        ];
    } else {
        error_log("SQL Error: " . mysqli_error($db));
        $_SESSION['message'] = [
            'type' => 'danger',
            'message' => 'Failed to update rider status.'
        ];
    }
} else {
    $_SESSION['message'] = [
        'type' => 'danger',
        'message' => 'Invalid request parameters.'
    ];
}

header("Location: rider_details.php");
exit();
