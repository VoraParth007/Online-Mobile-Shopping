<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in.";
    header("Location: login.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid request!";
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Fetch order details
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    $_SESSION['error'] = "Invalid order!";
    header("Location: orders.php");
    exit;
}

// Fetch ordered products
$order_items_query = mysqli_query($conn, "SELECT od.*, p.product_name 
                                          FROM order_details od 
                                          INNER JOIN products p ON od.product_id = p.id 
                                          WHERE od.order_id = '$order_id'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-4">
        <div class="alert alert-success">
            <h2>🎉 Order Placed Successfully!</h2>
            <p>Your order has been confirmed. Thank you for shopping with us.</p>
        </div>

        <h3>Order Details</h3>
        <p><strong>Order ID:</strong> <?= $order['id']; ?></p>
        <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_price'], 2); ?></p>
        <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
        <p><strong>Status:</strong> <span class="badge bg-success"><?= $order['status']; ?></span></p>

        <h3>Ordered Products</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($order_items_query)): ?>
                    <tr>
                        <td><?= $item['product_name']; ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>₹<?= number_format($item['price'], 2); ?></td>
                        <td>₹<?= number_format($item['total_price'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="orders.php" class="btn btn-primary">View My Orders</a>
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>

    <?php include('includes/footer.php'); ?>
</body>

</html>
