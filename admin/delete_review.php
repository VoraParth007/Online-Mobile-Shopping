<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_id'])) {
    $review_id = intval($_POST['review_id']);

    $delete_query = "DELETE FROM reviews WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $review_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Review deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete the review.";
    }

    mysqli_stmt_close($stmt);
    header("Location: reviews.php");
    exit();
}
?>
