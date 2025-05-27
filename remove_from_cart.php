<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $delete_query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Product removed from cart successfully.";
    } else {
        $_SESSION['error'] = "Failed to remove product from cart.";
    }
    
    mysqli_stmt_close($stmt);
}

header("Location: cart.php");
exit();
?>
