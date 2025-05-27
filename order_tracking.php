<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

$user_id = 1; // Temporary user ID
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Tracking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Track Your Orders</h2>
        <table class="table">
            <tr>
                <th>Order ID</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Tracking</th>
                <th>Action</th>
            </tr>
            <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td>₹<?php echo $order['total_price']; ?></td>
                    <td><?php echo $order['payment_method']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo ($order['tracking_status'] == 'Delivered') ? 'success' : (($order['tracking_status'] == 'Shipped') ? 'primary' : 'warning'); ?>">
                            <?php echo $order['tracking_status']; ?>
                        </span>
                    </td>
                    <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">View</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
