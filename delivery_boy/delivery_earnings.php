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
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Commission per delivered order
$commission_per_order = 50;

// Count total delivered orders
$count_query = "SELECT COUNT(*) AS total FROM orders WHERE delivery_boy_id = '$delivery_boy_id' AND status = 'Delivered'";
$count_result = mysqli_query($conn, $count_query);
$total_orders = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_orders / $limit);

// Calculate total earnings
$total_earnings = $total_orders * $commission_per_order;

// Fetch delivered orders
$earnings_query = "SELECT * FROM orders WHERE delivery_boy_id = '$delivery_boy_id' AND status = 'Delivered' ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

$earnings_result = mysqli_query($conn, $earnings_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Earnings</title>
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
        <a href="delivery_dashboard.php">My Orders</a>
        <a href="delivery_earnings.php">My Earnings</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h2>My Earnings</h2>

        <div class="alert alert-success">
            <strong>Total Earnings:</strong> ₹<?= number_format($total_earnings, 2); ?> (₹<?= $commission_per_order; ?> x <?= $total_orders; ?> Delivered Orders)
        </div>

        <h4>Delivered Orders</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Price</th>
                    <th>Delivery Date</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($earnings_result)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td>₹<?= number_format($row['total_price'], 2); ?></td>
                        <td><?= date('d-m-Y H:i A', strtotime($row['created_at'])); ?></td>

                        <td>₹<?= $commission_per_order; ?></td>
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
