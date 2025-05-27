<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $delivery_boy_id = $_POST['delivery_boy_id'];

    // Fetch order status
    $statusQuery = "SELECT status FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $statusQuery);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $status);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if order is already delivered
    if ($status === 'Delivered') {
        $_SESSION['error'] = "You cannot change delivery boy for a delivered order.";
        header("Location: manage_orders.php");
        exit();
    }

    // Assign delivery boy if not delivered
    $query = "UPDATE orders SET delivery_boy_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $delivery_boy_id, $order_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Delivery boy assigned successfully.";
    } else {
        $_SESSION['error'] = "Failed to assign delivery boy.";
    }

    mysqli_stmt_close($stmt);
    header("Location: manage_orders.php");
    exit();
}
?>
