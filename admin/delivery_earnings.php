<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Settings
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$fixed_commission = 50; // ₹50 per delivered order

// Count total delivery boys
$count_query = "SELECT COUNT(*) as total FROM delivery_boys WHERE status = 'Approved'";
$count_result = mysqli_query($conn, $count_query);
$total = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total / $limit);

// Fetch earnings data
$query = "SELECT db.id, db.name, db.phone, 
                 COUNT(o.id) AS delivered_orders,
                 (COUNT(o.id) * $fixed_commission) AS earnings
          FROM delivery_boys db
          LEFT JOIN orders o ON db.id = o.delivery_boy_id AND o.status = 'Delivered'
          WHERE db.status = 'Approved'
          GROUP BY db.id
          ORDER BY earnings DESC
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Earnings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 30px; background-color: #f8f9fa; }
        h2 { margin-bottom: 25px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delivery Boy Earnings</h2>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Total Delivered Orders</th>
                    <th>Earnings (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= $row['delivered_orders']; ?></td>
                        <td><strong>₹<?= number_format($row['earnings'], 2); ?></strong></td>
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
