<?php
include("../connection/connect.php");
header('Content-Type: application/json');

try {
    if (isset($_POST['trans_id']) && isset($_POST['status'])) {
        $trans_id = intval($_POST['trans_id']);
        $status = mysqli_real_escape_string($db, $_POST['status']);

        // Validate status values
        $valid_statuses = ['place_order', 'order_confirmation', 'in_process', 'cancelled'];
        if (!in_array($status, $valid_statuses)) {
            throw new Exception("Invalid status value");
        }

        // Update the status
        $sql = "UPDATE transaction SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $trans_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode([
                'success' => true,
                'message' => 'Status Updated Successfully',
                'new_status' => $status
            ]);
        } else {
            throw new Exception("Database error: " . mysqli_error($db));
        }
    } else {
        throw new Exception("Missing required parameters");
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>