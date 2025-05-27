<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to checkout.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = mysqli_query($conn, "SELECT email, phone, address, city, pincode, state FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

if (!$user) {
    $_SESSION['error'] = "User details not found. Please update your profile.";
    header("Location: checkout.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = mysqli_real_escape_string($conn, $_POST['payment_id']);

    // Fetch cart items
    $cart_query = mysqli_query($conn, "SELECT c.*, p.price FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
    
    if (mysqli_num_rows($cart_query) == 0) {
        $_SESSION['error'] = "Your cart is empty!";
        header("Location: cart.php");
        exit;
    }

    $total_price = 0;
    while ($row = mysqli_fetch_assoc($cart_query)) {
        $total_price += $row['price'] * $row['quantity'];
    }

    // Delivery Charge
    $delivery_charge = 50.00; 
    $final_price = $total_price + $delivery_charge;

    // Generate OTP
    $delivery_otp = rand(100000, 999999);

    // Insert Order
    $order_query = "INSERT INTO orders (user_id, payment_method, payment_id, total_price, delivery_charge, status, delivery_otp)
                    VALUES ('$user_id', 'Razorpay', '$payment_id', '$final_price', '$delivery_charge', 'Pending', '$delivery_otp')";

    if (mysqli_query($conn, $order_query)) {
        $order_id = mysqli_insert_id($conn);

        // Insert Order Details
        $order_details_query = "INSERT INTO order_details (order_id, product_id, quantity, price, total_price) 
                                SELECT '$order_id', product_id, quantity, price, (quantity * price) FROM cart WHERE user_id = '$user_id'";
        mysqli_query($conn, $order_details_query);

        // Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

        // ** Insert into Sales Reports **
        $sales_query = "INSERT INTO sales_reports (order_id, user_id, total_price, payment_method, created_at)
                        VALUES ('$order_id', '$user_id', '$final_price', 'Razorpay', NOW())";
        mysqli_query($conn, $sales_query);

        $_SESSION['success'] = "Order placed successfully";
        header("Location: orders.php?order_id=" . $order_id);
        exit;
    } else {
        $_SESSION['error'] = "Failed to place order.";
    }
}

// Redirect in case of failure
header("Location: checkout.php");
exit;
?>
