<?php
session_name("delivery_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update order status
    $query = "UPDATE orders SET status = '$status', delivery_status = '$status' WHERE id = '$order_id'";
    if (mysqli_query($conn, $query)) {
        header("Location: delivery_dashboard.php");
        exit();
    } else {
        echo "Error updating status.";
    }
}
?>
