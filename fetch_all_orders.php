<?php
include("connection/connect.php");
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['transaction_id'])) {
        $user_id = $_POST['user_id'];
        $transaction_id = $_POST['transaction_id'];

        $query = "SELECT *
                  FROM transaction
                  LEFT JOIN users_orders
                  ON users_orders.transaction_id = transaction.id
                  WHERE transaction.u_id = ? AND users_orders.transaction_id = ?";
                  
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $transaction_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }

        echo json_encode($orders);

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Missing parameters']);
    }
}

mysqli_close($db);
?>
