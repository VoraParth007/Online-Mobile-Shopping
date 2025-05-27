<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Fetch current status of the order
    $query = mysqli_query($conn, "SELECT status FROM orders WHERE id = '$order_id'");
    $order = mysqli_fetch_assoc($query);

    if (!$order) {
        $_SESSION['error'] = "Order not found!";
        header("Location: delivery_dashboard.php");
        exit();
    }

    // ✅ Check if order is already delivered
    if ($order['status'] === 'Delivered') {
        $_SESSION['error'] = "This order has already been delivered. No changes allowed.";
        header("Location: delivery_dashboard.php");
        exit();
    }

    // ✅ Update order status only if not delivered
    $updateQuery = "UPDATE orders SET status = '$status', delivery_status = '$status' WHERE id = '$order_id'";
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Order status updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating status.";
    }

    header("Location: delivery_dashboard.php");
    exit();
}
?>
