<?php
session_name("admin_session");
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$queryOrders = "SELECT orders.*, users.username, delivery_boys.name AS delivery_boy 
                FROM orders 
                LEFT JOIN users ON orders.user_id = users.id 
                LEFT JOIN delivery_boys ON orders.delivery_boy_id = delivery_boys.id
                WHERE orders.tracking_status != 'Pending'
                ORDER BY orders.created_at DESC";
$resultOrders = mysqli_query($conn, $queryOrders);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Tracking</title>
</head>
<body>
    <h2>Order Tracking</h2>
    <a href="dashboard.php">Back to Dashboard</a>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Total Price</th>
            <th>Tracking Status</th>
            <th>Delivery Boy</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($resultOrders)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td>₹<?php echo $row['total_price']; ?></td>
                <td><?php echo $row['tracking_status']; ?></td>
                <td><?php echo $row['delivery_boy'] ? $row['delivery_boy'] : "Not Assigned"; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
