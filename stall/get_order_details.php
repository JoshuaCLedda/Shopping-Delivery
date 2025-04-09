<?php
include("../connection/connect.php");
error_reporting(E_ALL);
session_start();

if (isset($_GET['trans_id'])) {
    $trans_id = mysqli_real_escape_string($db, $_GET['trans_id']);

    // Fetch order details based on transaction ID
    $sql = "SELECT * FROM transaction WHERE id = '$trans_id'";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<table class="table">';
        echo '<thead>
                <tr>
                     <th>Address</th>
                    <th>Item Title</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
              </thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['address']) . '</td>';
            echo '<td>' . htmlspecialchars($row['titles']) . '</td>';
            echo '<td>' . $row['total_quantity'] . '</td>';
            echo '<td>₱' . number_format($row['total_price'], 2) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo 'No order details found for this transaction.';
    }
} else {
    echo 'Transaction ID not provided.';
}
?>