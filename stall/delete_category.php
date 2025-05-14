<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

$delete = mysqli_query($db, "DELETE FROM res_category WHERE c_id = '" . $_GET['cat_del'] . "'");

if ($delete) {
    $_SESSION['message'] = ['type' => 'success', 'message' => 'Category deleted successfully.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to delete category.'];
}

header("location:addCategory.php");
exit;
?>
