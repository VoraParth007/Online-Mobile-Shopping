<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch all delivery boys
$delivery_boys_query = "SELECT id, name FROM delivery_boys";
$delivery_boys_result = mysqli_query($conn, $delivery_boys_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Delivery Earnings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Delivery Boy Earnings</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Delivery Boy</th>
                    <th>Total Deliveries</th>
                    <th>Total Earnings</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($delivery_boy = mysqli_fetch_assoc($delivery_boys_result)) { 
                    $delivery_boy_id = $delivery_boy['id'];
                    
                    // Get total earnings and delivered orders
                    $earnings_query = "SELECT COUNT(*) AS total_orders, SUM(delivery_charge) AS total_earnings 
                                       FROM orders WHERE delivery_boy_id = '$delivery_boy_id' AND status = 'Delivered'";
                    $earnings_result = mysqli_query($conn, $earnings_query);
                    $earnings = mysqli_fetch_assoc($earnings_result);

                    $total_orders = $earnings['total_orders'] ?? 0;
                    $total_earnings = $earnings['total_earnings'] ?? 0;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($delivery_boy['name']); ?></td>
                        <td><?= $total_orders; ?></td>
                        <td>&#8377;<?= number_format($total_earnings, 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
