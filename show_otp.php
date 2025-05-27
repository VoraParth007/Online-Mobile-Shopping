<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid order!";
    header("Location: orders.php");
    exit;
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// Order aur OTP fetch karna
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    $_SESSION['error'] = "Order not found!";
    header("Location: orders.php");
    exit;
}

$delivery_otp = $order['delivery_otp']; // OTP fetch kiya
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery OTP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<?php include('includes/header.php');?>
<body>
    <div class="container mt-5">
        <h2>Delivery OTP</h2>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id); ?></p>
        <p><strong>Your OTP:</strong> <span class="text-danger fw-bold"><?= htmlspecialchars($delivery_otp); ?></span></p>
        <p>Give this OTP to the delivery boy to verify your order.</p>
        <a href="orders.php" class="btn btn-primary">Back to Orders</a>
    </div>
</body>
<?php include('includes/footer.php');?>
</html>
