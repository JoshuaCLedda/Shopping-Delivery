<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "Main.php";

$index = new Index;

if (isset($_GET['menu_img'])) {
    $dishes_Id = intval($_GET['menu_img']);

    // Get image based on dish ID
    $query = mysqli_query($index->con, "SELECT img FROM dishes WHERE d_id = $dishes_Id");
    $data = mysqli_fetch_assoc($query);

    if ($data && !empty($data['img'])) {
        $imagePath = 'Res_img/' . $data['img'];

        if (file_exists($imagePath)) {
            unlink($imagePath); // delete the image file
        }

        // Clear image field from database
        $update = mysqli_query($index->con, "UPDATE dishes SET img = NULL WHERE d_id = $dishes_Id");

        if ($update) {
            $_SESSION['message'] = ['type' => 'success', 'message' => 'Image deleted successfully.'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to update database.'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'warning', 'message' => 'Image not found.'];
    }
} else {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'Invalid request.'];
}

// Redirect back (adjust path as needed)
header("Location: update_menu.php?menu_upd=$dishes_Id");

exit;
?>
