<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Check if product ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID!";
    header("Location: manage_product.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details to delete image
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Product not found!";
    header("Location: manage_product.php");
    exit();
}

$product = $result->fetch_assoc();
$image_path = "uploads/" . $product['image'];

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    // Remove image file if exists
    if (file_exists($image_path)) {
        unlink($image_path);
    }
    $_SESSION['success'] = "Product deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete product!";
}

header("Location: manage_product.php");
exit();
?>
