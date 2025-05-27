<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid order ID.";
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

$order_query = mysqli_query($conn, "SELECT delivery_boy_id FROM orders WHERE id = '$order_id' AND user_id = '$user_id'");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: orders.php");
    exit;
}

$delivery_boy_id = $order['delivery_boy_id'];

if (isset($_POST['submit_feedback'])) {
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    mysqli_query($conn, "INSERT INTO delivery_ratings (order_id, delivery_boy_id, rating, feedback) VALUES ('$order_id', '$delivery_boy_id', '$rating', '$feedback')");
    
    $_SESSION['success'] = "Feedback Submitted Successfully!";
    header("Location: orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <h2>Delivery Feedback</h2>
    <form method="POST">
        <label>Rate Delivery (1-5):</label>
        <input type="number" name="rating" min="1" max="5" class="form-control" required>
        
        <label>Feedback:</label>
        <textarea name="feedback" class="form-control" required></textarea>
        
        <button type="submit" name="submit_feedback" class="btn btn-success mt-2">Submit</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
