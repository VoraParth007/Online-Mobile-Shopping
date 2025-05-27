<?php
session_name("delivery_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login_delivery_boy.php");
    exit();
}

$delivery_boy_id = $_SESSION['delivery_boy_id'];

// Pagination settings
$limit = 7;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total orders assigned to this delivery boy
$count_query = "SELECT COUNT(*) AS total FROM orders WHERE delivery_boy_id = '$delivery_boy_id'";
$count_result = mysqli_query($conn, $count_query);
$total_orders = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_orders / $limit);

// Fetch total earnings
$fixed_commission = 50; // Example: ₹50 per delivered order
$earnings_query = "SELECT COUNT(*) AS delivered_orders FROM orders WHERE delivery_boy_id = '$delivery_boy_id' AND status = 'Delivered'";
$earnings_result = mysqli_query($conn, $earnings_query);
$delivered_orders = mysqli_fetch_assoc($earnings_result)['delivered_orders'] ?? 0;
$earnings = $delivered_orders * $fixed_commission;


// Fetch orders with user details
$query = "SELECT orders.*, users.username, users.phone, users.address, users.city, users.state, users.pincode 
          FROM orders 
          INNER JOIN users ON orders.user_id = users.id 
          WHERE orders.delivery_boy_id = '$delivery_boy_id' 
          ORDER BY orders.created_at DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; height: 100vh; }
        .sidebar { width: 250px; background-color: #343a40; color: white; padding: 20px; height: 100vh; }
        .sidebar a { color: white; text-decoration: none; padding: 10px; display: block; border-radius: 5px; }
        .sidebar a:hover { background-color: #495057; }
        .content { flex-grow: 1; padding: 20px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Delivery Panel</h4>
        <a href="delivery_boy_profile.php">Profile</a>
        <a href="#">My Orders</a>
        <a href="delivery_earnings.php">My Earnings</a>
        <a href="./logout.php">Logout</a>
    </div>
    
    <div class="content">
        <h2>Welcome, <?php echo $_SESSION['delivery_boy_name']; ?></h2>
        
        <!-- Earnings Section -->
        <div class="alert alert-info">
            <h4>Total Earnings: &#8377;<?= number_format($earnings, 2); ?></h4>
        </div>
        
        <h3>Assigned Orders</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <?= (isset($row['address']) ? htmlspecialchars($row['address']) : 'N/A') . ', ' .
                                (isset($row['city']) ? htmlspecialchars($row['city']) : 'N/A') . ', ' .
                                (isset($row['state']) ? htmlspecialchars($row['state']) : 'N/A') . ' - ' .
                                (isset($row['pincode']) ? htmlspecialchars($row['pincode']) : 'N/A'); ?>
                        </td>
                        <td>&#8377;<?= htmlspecialchars($row['total_price']); ?></td>
                        <td><span class="badge bg-info"><?= htmlspecialchars($row['status']); ?></span></td>
                        
                        <td>
                            <?php if ($row['status'] == "Out for Delivery") { ?>
                                <form action="process_delivery.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                    <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                                    <button type="submit" class="btn btn-success mt-2">Verify & Deliver</button>
                                </form>
                            <?php } elseif ($row['status'] != "Delivered") { ?>
                                <form action="update_delivery_status.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                    <select name="status" class="form-select">
                                        <option value="Picked">Picked</option>
                                        <option value="Out for Delivery">Out for Delivery</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2">Update</button>
                                </form>
                            <?php } else { ?>
                                <span class="text-success">Delivered</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a></li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
