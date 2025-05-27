<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to cancel an order.";
    header("Location: login.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Check if the order is eligible for cancellation using prepared statement to avoid SQL injection
$order_query = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'Processing'");
$order_query->bind_param("ii", $order_id, $user_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    $_SESSION['error'] = "Order cannot be canceled.";
    header("Location: orders.php");
    exit;
}

// Cancel the order using prepared statement
$cancel_query = $conn->prepare("UPDATE orders SET status = ?, tracking_status = ? WHERE id = ?");
$status = 'Canceled';
$tracking_status = 'N/A';
$cancel_query->bind_param("ssi", $status, $tracking_status, $order_id);

if ($cancel_query->execute()) {
    $_SESSION['success'] = "Order successfully canceled.";
} else {
    $_SESSION['error'] = "Failed to cancel the order. Please try again.";
}

header("Location: orders.php");
exit;
?>
