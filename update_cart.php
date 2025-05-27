<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Fetch product price
    $query = mysqli_query($conn, "SELECT price FROM cart WHERE id = $cart_id");
    $row = mysqli_fetch_assoc($query);
    $price = $row['price'];

    $total_price = $price * $quantity;

    // Update cart quantity and total price
    mysqli_query($conn, "UPDATE cart SET quantity = $quantity, total_price = $total_price WHERE id = $cart_id");
}
?>
