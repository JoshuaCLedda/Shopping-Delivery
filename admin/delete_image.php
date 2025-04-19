<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Fixed 'value: 1' to just '1'
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "Main.php";
$index = new Index;
$db = $index->con; // ✅ Use the connection from your class

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rs_id'])) {
    $rs_id = intval($_POST['rs_id']);

    // Fetch the image path
    $query = mysqli_query($db, "SELECT image FROM restaurant WHERE rs_id = $rs_id");
    $row = mysqli_fetch_assoc($query);

    if ($row && !empty($row['image']) && file_exists($row['image'])) {
        unlink($row['image']); // Delete image from filesystem

        // Update DB to remove image path
        mysqli_query($db, "UPDATE restaurant SET image = NULL WHERE rs_id = $rs_id");

        echo 'success';
    } else {
        echo 'no_image';
    }
} else {
    echo 'error';
}
?>
