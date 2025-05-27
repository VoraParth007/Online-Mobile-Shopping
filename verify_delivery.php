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

// Agar OTP `orders` table me hai
$otp = $order['otp'];

// Agar OTP `order_otp` table me hai (Agar alag table use ho raha hai toh yeh query kaam karegi)
// $otp_query = mysqli_query($conn, "SELECT otp FROM order_otp WHERE order_id = '$order_id'");
// $otp_row = mysqli_fetch_assoc($otp_query);
// $otp = $otp_row['otp'] ?? "N/A"; // Agar OTP milta hai toh dikhaye, warna "N/A" dikhaye

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Delivery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Verify Delivery</h2>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id); ?></p>
        <p><strong>Customer Name:</strong> <?= htmlspecialchars($order['fullname']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
        <p><strong>Delivery OTP:</strong> <span class="text-danger fw-bold"><?= htmlspecialchars($otp); ?></span></p>

        <form action="process_delivery.php" method="POST">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id); ?>">
            <input type="text" name="entered_otp" class="form-control mt-2" placeholder="Enter OTP" required>
            <button type="submit" class="btn btn-success mt-3">Verify & Complete Delivery</button>
        </form>
    </div>
</body>
</html>
