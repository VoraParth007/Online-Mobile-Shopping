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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Order Management | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-light: #A29BFE;
            --primary-dark: #5649C0;
            --secondary: #FD79A8;
            --success: #00B894;
            --warning: #FDCB6E;
            --danger: #E17055;
            --info: #0984E3;
            --light: #F5F6FA;
            --dark: #2D3436;
            --gray: #636E72;
            --card-bg: #FFFFFF;
            --app-bg: #F1F3F9;
            --border-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
        }
        
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--app-bg);
            color: var(--dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Glassmorphism Header */
       .app-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--dark);
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .app-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .header-btn {
            background: rgba(108, 92, 231, 0.1);
            border: none;
            color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
                .header-btn:hover {
            background: rgba(108, 92, 231, 0.2);
            transform: translateY(-2px);
        }

        /* Content area */
        .content {
            padding: 20px;
            padding-bottom: 30px;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-title {
            font-size: 0.85rem;
            color: var(--gray);
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }
        
        /* Order Cards */
        .order-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            position: relative;
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-light);
        }
        
        .order-header {
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .order-id {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1rem;
        }
        
        .order-time {
            font-size: 0.8rem;
            color: var(--gray);
        }
        
        .order-body {
            padding: 16px;
        }
        
        .customer-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .customer-details {
            flex: 1;
        }
        
        .customer-name {
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .customer-contact {
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .detail-item {
            background: var(--light);
            padding: 10px;
            border-radius: 8px;
        }
        
        .detail-label {
            font-size: 0.75rem;
            color: var(--gray);
            margin-bottom: 4px;
        }
        
        .detail-value {
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        /* Status Chips */
        .status-chip {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: rgba(253, 203, 110, 0.2);
            color: #B8860B;
        }
        
        .status-delivered {
            background: rgba(0, 184, 148, 0.2);
            color: var(--success);
        }
        
        .status-out {
            background: rgba(9, 132, 227, 0.2);
            color: var(--info);
        }
        
        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 92, 231, 0.3);
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background: rgba(108, 92, 231, 0.1);
        }
        
        /* Form Elements */
        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
            background-color: var(--light);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236C5CE7' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
        }
        
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
            outline: none;
        }
        
        /* Desktop Table View */
        .order-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        
        .order-table thead th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 16px;
            text-align: left;
            font-weight: 500;
            position: sticky;
            top: 0;
            font-size: 0.9rem;
        }
        
        .order-table tbody tr {
            transition: all 0.2s;
        }
        
        .order-table tbody tr:hover {
            background-color: rgba(108, 92, 231, 0.03);
        }
        
        .order-table td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
            font-size: 0.9rem;
        }
        
        .order-table tr:last-child td {
            border-bottom: none;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767px) {
            .table-container {
                display: none;
            }
            
            .order-cards-container {
                display: block;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (min-width: 768px) {
            .order-cards-container {
                display: none;
            }
            
            .table-container {
                display: block;
                max-height: calc(100vh - 220px);
                overflow-y: auto;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-sm);
                background: var(--card-bg);
            }
            
            .content {
                padding: 24px;
                padding-bottom: 24px;
            }
            
            .app-header {
                padding: 15px 24px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate {
            animation: fadeIn 0.4s ease-out forwards;
        }
        
        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            z-index: 90;
            transition: all 0.3s ease;
            border: none;
            font-size: 1.2rem;
        }
        
        .fab:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
    </style>
</head>
<body>
    <!-- Glassmorphism Header -->
      <header class="app-header">
        <button class="header-btn" onclick="window.location.href='dashboard.php'">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="app-title">
            <i class="fas fa-box-open"></i> Order Management
        </h1>
        <div style="width: 40px;"></div> <!-- Spacer for balance -->
    </header>


    <main class="content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show animate" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-container animate">
            <div class="stat-card">
                <div class="stat-title">Total Orders</div>
                <div class="stat-value"><?= mysqli_num_rows($orders); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Pending</div>
                <div class="stat-value">
                    <?php 
                    mysqli_data_seek($orders, 0);
                    $pending = 0;
                    while($row = mysqli_fetch_assoc($orders)) {
                        if($row['status'] == 'Pending') $pending++;
                    }
                    echo $pending;
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">In Delivery</div>
                <div class="stat-value">
                    <?php 
                    mysqli_data_seek($orders, 0);
                    $inDelivery = 0;
                    while($row = mysqli_fetch_assoc($orders)) {
                        if($row['status'] == 'Out for Delivery') $inDelivery++;
                    }
                    echo $inDelivery;
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Delivered</div>
                <div class="stat-value">
                    <?php 
                    mysqli_data_seek($orders, 0);
                    $delivered = 0;
                    while($row = mysqli_fetch_assoc($orders)) {
                        if($row['status'] == 'Delivered') $delivered++;
                    }
                    echo $delivered;
                    ?>
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="table-container">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Delivery Boy</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($orders, 0); // Reset pointer for second loop
                    while ($row = mysqli_fetch_assoc($orders)): ?>
                        <tr class="animate">
                            <td>#<?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td>&#8377;<?= number_format($row['total_price'], 2); ?></td>
                            <td>
                                <span class="status-chip 
                                    <?php if ($row['status'] == 'Pending') echo 'status-pending';
                                    elseif ($row['status'] == 'Delivered') echo 'status-delivered';
                                    elseif ($row['status'] == 'Out for Delivery') echo 'status-out'; ?>">
                                    <i class="fas fa-circle" style="font-size: 6px; margin-right: 6px;"></i>
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?= $row['delivery_boy_name'] 
                                    ? "<span class='text-success'>" . htmlspecialchars($row['delivery_boy_name']) . "</span>" 
                                    : "<span class='text-danger'>Not Assigned</span>"; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'Delivered'): ?>
                                    <span class="text-muted">Completed</span>
                                <?php else: ?>
                                    <form action="assign_delivery.php" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                        <select name="delivery_boy_id" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            <?php foreach ($deliveryBoysList as $boy): ?>
                                                <option value="<?= htmlspecialchars($boy['id']); ?>" <?= ($row['delivery_boy_id'] == $boy['id']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($boy['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="order-cards-container">
            <?php 
            mysqli_data_seek($orders, 0); // Reset pointer for second loop
            while ($row = mysqli_fetch_assoc($orders)): ?>
                <div class="order-card animate">
                    <div class="order-header">
                        <div class="order-id">Order #<?= htmlspecialchars($row['id']); ?></div>
                        <div class="order-time"><?= date('M j, Y g:i A', strtotime($row['created_at'])); ?></div>
                    </div>
                    <div class="order-body">
                        <div class="customer-info">
                            <div class="customer-avatar">
                                <?= strtoupper(substr(htmlspecialchars($row['username']), 0, 1)); ?>
                            </div>
                            <div class="customer-details">
                                <div class="customer-name"><?= htmlspecialchars($row['username']); ?></div>
                                <div class="customer-contact"><?= htmlspecialchars($row['phone']); ?></div>
                            </div>
                            <div class="status-chip 
                                <?php if ($row['status'] == 'Pending') echo 'status-pending';
                                elseif ($row['status'] == 'Delivered') echo 'status-delivered';
                                elseif ($row['status'] == 'Out for Delivery') echo 'status-out'; ?>">
                                <i class="fas fa-circle" style="font-size: 6px; margin-right: 6px;"></i>
                                <?= htmlspecialchars($row['status']); ?>
                            </div>
                        </div>
                        
                        <div class="order-details">
                            <div class="detail-item">
                                <div class="detail-label">Total Amount</div>
                                <div class="detail-value">&#8377;<?= number_format($row['total_price'], 2); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Delivery Boy</div>
                                <div class="detail-value">
                                    <?= $row['delivery_boy_name'] 
                                        ? "<span class='text-success'>" . htmlspecialchars($row['delivery_boy_name']) . "</span>" 
                                        : "<span class='text-danger'>Not Assigned</span>"; ?>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">City</div>
                                <div class="detail-value"><?= htmlspecialchars($row['city']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Pincode</div>
                                <div class="detail-value"><?= htmlspecialchars($row['pincode']); ?></div>
                            </div>
                        </div>
                        
                        <?php if ($row['status'] !== 'Delivered'): ?>
                        <div class="d-flex flex-column gap-2">
                            <form action="assign_delivery.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                <select name="delivery_boy_id" class="form-select mb-2" required>
                                    <option value="">Select Delivery Boy</option>
                                    <?php foreach ($deliveryBoysList as $boy): ?>
                                        <option value="<?= htmlspecialchars($boy['id']); ?>" <?= ($row['delivery_boy_id'] == $boy['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($boy['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-paper-plane"></i> Assign Delivery
                                </button>
                            </form>
                            
                          
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
       
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation class to elements as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.order-card, .order-table tbody tr, .stat-card').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
