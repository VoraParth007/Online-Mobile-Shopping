<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to view your orders.";
    header("Location: login.php");
    exit;
}

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$query = "SELECT orders.*, users.username, users.email, users.phone 
          FROM orders 
          INNER JOIN users ON orders.user_id = users.id 
          WHERE orders.user_id = '$user_id'";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-4">
        <h2>My Orders</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Tracking Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']); ?></td>
                        <td><?= isset($order['username']) ? htmlspecialchars($order['username']) : 'N/A'; ?></td>
                        <td><?= isset($order['email']) ? htmlspecialchars($order['email']) : 'N/A'; ?></td>
                        <td><?= isset($order['phone']) ? htmlspecialchars($order['phone']) : 'N/A'; ?></td>
                        <td>&#8377;<?= number_format($order['total_price'], 2); ?></td>
                        <td><?= htmlspecialchars($order['payment_method']); ?></td>
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td>
                            <a href="order_details.php?order_id=<?= urlencode($order['id']); ?>" class="btn btn-info btn-sm">View</a>
                            <?php if ($order['status'] == 'Out for Delivery'): ?>
                                <a href="show_otp.php?order_id=<?= urlencode($order['id']); ?>" class="btn btn-success btn-sm">Verify Delivery</a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('includes/footer.php'); ?>
</body>

</html>