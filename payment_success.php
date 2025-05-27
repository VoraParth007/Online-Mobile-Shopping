<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to complete your order.";
    header("Location: login.php");
    exit;
}

if (!isset($_GET['payment_id'])) {
    $_SESSION['error'] = "Payment failed. Try again.";
    header("Location: checkout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$payment_id = $_GET['payment_id'];
$payment_method = "Razorpay";
$total_amount = 0;

// Fetch Cart Items
$cart_items = mysqli_query($conn, "SELECT c.*, p.product_name, p.price FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
$order_items = [];

while ($row = mysqli_fetch_assoc($cart_items)) {
    $total_amount += $row['price'] * $row['quantity'];
    $order_items[] = $row;
}

// Delivery Charge
$delivery_charge = 50.00;
$final_price = $total_amount + $delivery_charge;

// Insert into Orders Table
$order_query = "INSERT INTO orders (user_id, total_price, payment_method, payment_id, status, delivery_charge) 
                VALUES ('$user_id', '$final_price', '$payment_method', '$payment_id', 'Pending', '$delivery_charge')";

if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);

    // Insert Order Details
    foreach ($order_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $total_price = $price * $quantity;

        $order_details_query = "INSERT INTO order_details (order_id, product_id, quantity, price, total_price) 
                                VALUES ('$order_id', '$product_id', '$quantity', '$price', '$total_price')";
        mysqli_query($conn, $order_details_query);
    }

    // Clear the user's cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

    // ** Insert into Sales Reports **
    $sales_query = "INSERT INTO sales_reports (order_id, user_id, total_price, payment_method, created_at)
                    VALUES ('$order_id', '$user_id', '$final_price', 'Razorpay', NOW())";
    mysqli_query($conn, $sales_query);

    $_SESSION['success'] = "Your order has been placed successfully!";
    header("Location: orders.php");
    exit;
} else {
    $_SESSION['error'] = "Something went wrong: " . mysqli_error($conn);
    header("Location: checkout.php");
    exit;
}
?>
