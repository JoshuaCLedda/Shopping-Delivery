<?php
include("../connection/connect.php");

$sql = "SELECT users.username, users.u_id, users_orders.title, 
            SUM(users_orders.quantity) AS total_quantity, 
            SUM(users_orders.quantity * users_orders.price) AS total_price, 
            users_orders.address, users_orders.status, users_orders.date, users_orders.o_id
        FROM users
        INNER JOIN users_orders ON users.u_id = users_orders.u_id
        GROUP BY users.u_id, users_orders.title, users_orders.o_id";

$query = mysqli_query($db, $sql);

$output = '';

if (mysqli_num_rows($query) > 0) {
    while ($rows = mysqli_fetch_array($query)) {
        $output .= '<tr>
                        <td>' . $rows['username'] . '</td>
                        <td>' . $rows['title'] . '</td>
                        <td>' . $rows['total_quantity'] . '</td>
                        <td>₱' . number_format($rows['total_price'], 2) . '</td>
                        <td>' . $rows['address'] . '</td>
                        <td>' . $rows['status'] . '</td>
                        <td>' . date("F j, Y", strtotime($rows['date'])) . '</td>
                        <td>
                            <a href="delete_orders.php?order_del=' . $rows['o_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\');">Delete</a>
                        </td>
                    </tr>';
    }
} else {
    $output = '<tr><td colspan="8">No orders found.</td></tr>';
}

echo $output;
?>
