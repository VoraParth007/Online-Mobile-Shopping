<?php
session_name("delivery_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login_delivery_boy.php");
    exit();
}

$delivery_boy_id = $_SESSION['delivery_boy_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);

    if (isset($_POST['accept'])) {
        // Accept the order
        $update_query = "UPDATE orders SET delivery_boy_status = 'Accepted' WHERE id = '$order_id' AND delivery_boy_id = '$delivery_boy_id'";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_msg'] = "Order Accepted Successfully!";
        } else {
            $_SESSION['error_msg'] = "Error Accepting Order!";
        }
    } elseif (isset($_POST['reject'])) {
        // Reject the order
        $update_query = "UPDATE orders SET delivery_boy_status = 'Rejected', delivery_boy_id = NULL WHERE id = '$order_id' AND delivery_boy_id = '$delivery_boy_id'";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_msg'] = "Order Rejected Successfully!";
        } else {
            $_SESSION['error_msg'] = "Error Rejecting Order!";
        }
    }
}

header("Location: delivery_dashboard.php");
exit();
