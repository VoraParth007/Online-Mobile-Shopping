<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id_filter = '';
$order_query = null;
$error_message = "";

// Handle form submission
if (isset($_POST['search_order'])) {
    $order_id_filter = intval($_POST['order_id']);

    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id_filter' AND user_id = '$user_id'");
    
    if (mysqli_num_rows($order_query) == 0) {
        $error_message = "No order found with this ID or you are not authorized to view it.";
    }
} else {
    // Show all orders if no search
    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-4">
        <h2>📦 Track Your Orders</h2>

        <!-- Search Order ID Form -->
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="number" name="order_id" class="form-control" placeholder="Enter Order ID" value="<?= htmlspecialchars($order_id_filter); ?>" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="search_order" class="btn btn-primary">🔍 Track Order</button>
            </div>
            <div class="col-md-6">
                <a href="track_order.php" class="btn btn-secondary">🔄 View All Orders</a>
            </div>
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message; ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($order_query) > 0): ?>
            <table class="table table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                     
                        <th>Tracking</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($order_query)): ?>
                        <tr>
                            <td><?= $order['id']; ?></td>
                            <td>₹<?= number_format($order['total_price'], 2); ?></td>
                            <td><?= $order['payment_method']; ?></td>
                  
                            <td><span class="badge bg-warning"><?= $order['status']; ?></span></td>
                            <td>
                                <a href="order_details.php?order_id=<?= $order['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['search_order'])): ?>
            <!-- Already handled above -->
        <?php else: ?>
            <div class="alert alert-info">
                <p>No orders found. <a href="index.php">Shop Now</a></p>
            </div>
        <?php endif; ?>
    </div>

    <?php include('includes/footer.php'); ?>
</body>

</html>
