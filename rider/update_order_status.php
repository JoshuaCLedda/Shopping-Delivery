<?php
include("../connection/connect.php");

if (isset($_POST['trans_id']) && isset($_POST['status'])) {
    $trans_id = intval($_POST['trans_id']);  // Ensure it's an integer
    $status = mysqli_real_escape_string($db, $_POST['status']);  // Escape special characters in the status

    // Check if the values are valid
    if (empty($trans_id) || empty($status)) {
        echo "Transaction ID or Status cannot be empty!";
        exit();
    }

    // Update the status of the order
    $sql = "UPDATE transaction SET status = '$status' WHERE id = $trans_id";
    
    if (mysqli_query($db, $sql)) {
        // Redirect back to the orders page after successful update
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating status: " . mysqli_error($db);
    }
} else {
    echo "Transaction ID or Status not provided!";
}
?>
