<?php
session_name("delivery_session");
session_start();
include('../includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $entered_otp = mysqli_real_escape_string($conn, $_POST['otp']);

    // Fetch original OTP from the database
    $query = mysqli_query($conn, "SELECT delivery_otp FROM orders WHERE id = '$order_id'");
    $order = mysqli_fetch_assoc($query);

    if (!$order) {
        $_SESSION['error'] = "Order not found!";
        header("Location: delivery_dashboard.php");
        exit;
    }

    $original_otp = $order['delivery_otp'];

    if ($entered_otp == $original_otp) {
        // ✅ OTP correct, update order status (Removed `delivered_at`)
        $update_query = "UPDATE orders SET status = 'Delivered' WHERE id = '$order_id'";
        mysqli_query($conn, $update_query);

        $_SESSION['success'] = "Order successfully delivered!";
    } else {
        $_SESSION['error'] = "Invalid OTP! Please try again.";
    }

    header("Location: delivery_dashboard.php");
    exit;
}
?>
