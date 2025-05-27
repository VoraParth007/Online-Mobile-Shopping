<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch orders with user details and delivery boy assignment
$query = "SELECT orders.*, users.username, users.phone, users.address, users.city, users.state, users.pincode, 
                 delivery_boys.name AS delivery_boy_name 
          FROM orders
          LEFT JOIN users ON orders.user_id = users.id
          LEFT JOIN delivery_boys ON orders.delivery_boy_id = delivery_boys.id
          ORDER BY orders.created_at DESC";

$orders = mysqli_query($conn, $query);

// Fetch only approved delivery boys
$deliveryBoysQuery = "SELECT * FROM delivery_boys WHERE status = 'Approved'";
$deliveryBoys = mysqli_query($conn, $deliveryBoysQuery);
$deliveryBoysList = mysqli_fetch_all($deliveryBoys, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
        }
        thead th {
            position: sticky;
            top: 0;
            background: #212529;
            color: white;
            z-index: 2;
        }
        .btn-back {
            margin-bottom: 15px;
        }
        .btn-assign {
            width: 100%;
        }
        .badge {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="dashboard.php" class="btn btn-secondary btn-back"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        <h2 class="text-center mb-4 text-primary"><i class="bi bi-clipboard-check"></i> Manage Orders</h2>

        <div class="table-container">
            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Delivery Boy</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($orders)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td>
                                <?= htmlspecialchars($row['address']) . ', ' .
                                    htmlspecialchars($row['city']) . ', ' .
                                    htmlspecialchars($row['state']) . ' - ' .
                                    htmlspecialchars($row['pincode']); ?>
                            </td>
                            <td>&#8377;<?= number_format($row['total_price'], 2); ?></td>
                            <td>
                                <span class="badge 
                                    <?php if ($row['status'] == 'Pending') echo 'bg-warning';
                                    elseif ($row['status'] == 'Delivered') echo 'bg-success';
                                    elseif ($row['status'] == 'Out for Delivery') echo 'bg-primary';
                                    else echo 'bg-info'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?= $row['delivery_boy_name'] 
                                    ? "<span class='text-success'>" . htmlspecialchars($row['delivery_boy_name']) . "</span>" 
                                    : "<span class='text-danger'>Not Assigned</span>"; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'Delivered') { ?>
                                    <span class="text-muted">No Changes Allowed</span>
                                <?php } else { ?>
                                    <form action="assign_delivery.php" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                        <select name="delivery_boy_id" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach ($deliveryBoysList as $boy) { ?>
                                                <option value="<?= htmlspecialchars($boy['id']); ?>" <?= ($row['delivery_boy_id'] == $boy['id']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($boy['name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" class="btn btn-success btn-sm mt-1 btn-assign">
                                            <i class="bi bi-check-circle"></i> Assign
                                        </button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
