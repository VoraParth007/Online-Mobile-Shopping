<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in.";
    header("Location: login.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid order request.";
    header("Location: track_order.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

// Fetch order details
$query = "SELECT orders.*, 
                 users.username AS fullname, 
                 users.address, 
                 users.city, 
                 users.state, 
                 users.pincode, 
                 users.phone, 
                 delivery_boys.name AS delivery_boy_name
          FROM orders 
          INNER JOIN users ON orders.user_id = users.id 
          LEFT JOIN delivery_boys ON orders.delivery_boy_id = delivery_boys.id
          WHERE orders.id = '$order_id'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);



if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: track_order.php");
    exit;
}

// Fetch order items
$order_items_query = mysqli_query($conn, "SELECT od.*, p.product_name, p.image 
                                          FROM order_details od 
                                          INNER JOIN products p ON od.product_id = p.id 
                                          WHERE od.order_id = '$order_id'");

// Handle Order Cancellation
if (isset($_POST['cancel_order'])) {
    if ($order['status'] == 'Pending') {
        mysqli_query($conn, "UPDATE orders SET status='Cancelled', tracking_status='Cancelled' WHERE id='$order_id'");
        $_SESSION['success'] = "Order has been cancelled successfully!";
        header("Location: order_details.php?order_id=$order_id");
        exit;
    } else {
        $_SESSION['error'] = "Order cannot be cancelled at this stage.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-4">
        <h2>🛍 Order Details</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card p-3 shadow-lg">
            <h4>Order #<?= $order['id']; ?> <span class="badge bg-info"> <?= $order['status']; ?> </span></h4>
            <p><strong>Order Date:</strong> <?= $order['created_at']; ?></p>
            <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_price'], 2); ?></p>
            <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
            <p><strong>Tracking Status:</strong> <span class="badge bg-warning"> <?= $order['status']; ?> </span></p>
            <h4 class="mt-3">📦 Shipping Address</h4>
            <p><?= isset($order['fullname']) ? htmlspecialchars($order['fullname']) : 'N/A'; ?></p>
            <p>
                <?= isset($order['address']) ? htmlspecialchars($order['address']) : 'N/A'; ?>,
                <?= isset($order['city']) ? htmlspecialchars($order['city']) : 'N/A'; ?>,
                <?= isset($order['state']) ? htmlspecialchars($order['state']) : 'N/A'; ?> -
                <?= isset($order['pincode']) ? htmlspecialchars($order['pincode']) : 'N/A'; ?>
            </p>
            <p><strong>Phone:</strong> <?= isset($order['phone']) ? htmlspecialchars($order['phone']) : 'N/A'; ?></p>

            <h4 class="mt-3">🚚 Assigned Delivery Boy</h4>
            <p><strong>Name:</strong>
                <?php echo isset($order['delivery_boy_name']) && $order['delivery_boy_name'] ?
                    htmlspecialchars($order['delivery_boy_name']) : '<span class="text-danger">Not Assigned</span>'; ?>
            </p>

            <!-- Order Cancellation Button --> <?php if ($order['status'] == 'Pending'): ?>
                <form method="POST" class="mt-3">
                    <button type="submit" name="cancel_order" class="btn btn-danger">❌ Cancel Order</button>
                </form>
            <?php endif; ?>
        </div>

        <h4 class="mt-4">🛒 Ordered Items</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($order_items_query)): ?>
                    <tr>
                        <td><?= $item['product_name']; ?></td>
                        <td><img src="admin/uploads/<?= $item['image']; ?>" alt="Product Image" width="50"></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>₹<?= number_format($item['price'], 2); ?></td>
                        <td>₹<?= number_format($item['total_price'], 2); ?></td>
                        <td>
                            <?php if ($order['status'] == 'Delivered'): ?>
                                <form action="submit_review.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                                    <select name="rating" class="form-select" required>
                                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4)</option>
                                        <option value="3">⭐⭐⭐ (3)</option>
                                        <option value="2">⭐⭐ (2)</option>
                                        <option value="1">⭐ (1)</option>
                                    </select>
                                    <textarea name="review" class="form-control mt-2" placeholder="Write your review..." required></textarea>
                                    <button type="submit" class="btn btn-success mt-2">Submit Review</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">Available after delivery</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

      
            <a href="track_order.php" class="btn btn-primary">📍 Track your Order</a>

            <a href="generate_invoice.php?order_id=<?= $order['id']; ?>" target="_blank" class="btn btn-primary">
                🧾 Download Invoice
            </a>

            <a href="orders.php" class="btn btn-primary">⬅ Back to Orders</a>
       

</div>

    <?php include('includes/footer.php'); ?>
</body>

</html>