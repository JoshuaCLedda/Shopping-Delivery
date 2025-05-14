<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if a category ID is provided
if (isset($_GET['cat_del'])) {
    $categoryId = $_GET['cat_del'];

    // Delete category from the database
    $query = "DELETE FROM res_category WHERE c_id = '$categoryId'";
    
    if (mysqli_query($db, $query)) {
        // Set success message if deletion is successful
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Category deleted successfully.'
        ];
    } else {
        // Set error message if deletion fails
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Failed to delete category. Please try again.'
        ];
    }
} else {
    // Set error message if no category ID is provided
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Invalid category ID.'
    ];
}

// Redirect back to the category page
header("Location: add_category.php");
exit();
?>
