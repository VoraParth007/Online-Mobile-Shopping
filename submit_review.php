<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $review = mysqli_real_escape_string($conn, $_POST['review']);

    // Check if the user already reviewed this product
    $check_query = mysqli_query($conn, "SELECT * FROM reviews WHERE order_id = '$order_id' AND product_id = '$product_id' AND user_id = '$user_id'");
    
    if (mysqli_num_rows($check_query) == 0) {
        // Insert the review into the database
        $query = "INSERT INTO reviews (user_id, order_id, product_id, rating, review) 
                  VALUES ('$user_id', '$order_id', '$product_id', '$rating', '$review')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Thank you for your review!";
        } else {
            $_SESSION['error'] = "Error submitting review.";
        }
    } else {
        $_SESSION['error'] = "You have already reviewed this product.";
    }
}

header("Location: order_details.php?order_id=$order_id");
exit;
?>
