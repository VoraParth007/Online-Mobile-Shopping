<?php
session_name("delivery_session");
session_start();
include ('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST['order_id']);
    $entered_otp = $_POST['otp'];

    $query = "SELECT otp_code FROM orders WHERE id='$order_id' AND user_id='{$_SESSION['user_id']}'";
    $result = mysqli_query($conn, $query);
    $order = mysqli_fetch_assoc($result);

    if ($order && $order['otp_code'] == $entered_otp) {
        mysqli_query($conn, "UPDATE orders SET status='Delivered', status='Completed' WHERE id='$order_id'");
        $_SESSION['success'] = "Order successfully delivered!";
    } else {
        $_SESSION['error'] = "Invalid OTP!";
    }
}

header("Location: order.php");
exit();
?>
