<?php
session_name("user_session"); 
session_start();
include('./includes/config.php');

if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    header("Location: cart.php?error=Invalid Product ID");
    exit();
}

$product_id = intval($_GET['product_id']);
$user_id = $_SESSION['user_id'] ?? null;  // Ensure user is logged in

if (!$user_id) {
    header("Location: login.php?error=Please login to add items to cart");
    exit();
}

// Check if the product exists
$stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: cart.php?error=Product not found");
    exit();
}

$product = $result->fetch_assoc();
$price = $product['price'];

// Check if product is already in the cart
$checkCart = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$checkCart->bind_param("ii", $user_id, $product_id);
$checkCart->execute();
$cartResult = $checkCart->get_result();

if ($cartResult->num_rows > 0) {
    // Update quantity if already in cart
    $cartRow = $cartResult->fetch_assoc();
    $newQuantity = $cartRow['quantity'] + 1;
    $updateCart = $conn->prepare("UPDATE cart SET quantity = ?, total_price = ? WHERE id = ?");
    $total_price = $newQuantity * $price;
    $updateCart->bind_param("idi", $newQuantity, $total_price, $cartRow['id']);
    $updateCart->execute();
} else {
    // Insert new product into cart
    $insertCart = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, price, total_price) VALUES (?, ?, 1, ?, ?)");
    $total_price = $price;
    $insertCart->bind_param("iidd", $user_id, $product_id, $price, $total_price);
    $insertCart->execute();
}

// Redirect to cart page with success message
header("Location: cart.php?success=Product added to cart");
exit();
?>
