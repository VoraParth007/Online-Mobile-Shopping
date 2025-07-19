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

// Calculate total earnings for all delivery boys
$total_earnings_query = "SELECT SUM(earnings) AS total_earnings FROM (
    SELECT (COUNT(o.id) * $fixed_commission) AS earnings
    FROM delivery_boys db
    LEFT JOIN orders o ON db.id = o.delivery_boy_id AND o.status = 'Delivered'
    WHERE db.status = 'Approved'
    GROUP BY db.id
) AS earnings_table";
$total_earnings_result = mysqli_query($conn, $total_earnings_query);
$total_earnings = mysqli_fetch_assoc($total_earnings_result)['total_earnings'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Earnings - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #4cc9f0;
            --success: #38b000;
            --warning: #ffaa00;
            --danger: #ff3d71;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --gray: #8d99ae;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #eef1f8 100%);
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            min-height: 100vh;
            padding: 20px;
        }

        .admin-container {
            max-width: 1400px;
            margin: 2rem auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .dashboard-header {
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title i {
            font-size: 1.8rem;
            background: rgba(255, 255, 255, 0.15);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .header-title h1 {
            font-weight: 600;
            font-size: 1.8rem;
            margin: 0;
        }

        .header-title span {
            font-weight: 300;
            opacity: 0.9;
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            border-radius: 10px;
            padding: 0.7rem 1.2rem;
            transition: var(--transition);
            border: none;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-outline-light {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .dashboard-content {
            padding: 2rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-card-primary {
            border-top: 4px solid var(--primary);
        }

        .stat-card-success {
            border-top: 4px solid var(--success);
        }

        .stat-card-warning {
            border-top: 4px solid var(--warning);
        }

        .stat-title {
            font-size: 0.95rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stat-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: var(--success);
        }

        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            background: #f9fafc;
            border-bottom: 1px solid #edf2f7;
        }

        .table-title {
            font-weight: 600;
            font-size: 1.3rem;
            color: var(--dark);
            margin: 0;
        }

        .commission-info {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            padding: 1.1rem 1.5rem;
            font-weight: 600;
            color: var(--dark);
            text-align: left;
            border-bottom: 2px solid #edf2f7;
        }

        .table td {
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
            transition: var(--transition);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
        }

        .table tbody tr:hover td {
            background: transparent;
        }

        .earnings-cell {
            font-weight: 700;
            color: var(--success);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .user-phone {
            font-size: 0.85rem;
            color: var(--gray);
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            background: #f9fafc;
            border-top: 1px solid #edf2f7;
        }

        .pagination-info {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .page-item {
            list-style: none;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: white;
            color: var(--primary);
            font-weight: 500;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
            text-decoration: none;
        }

        .page-link:hover, .page-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Mobile optimizations */
        @media (max-width: 992px) {
            .dashboard-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }
            
            .header-title {
                justify-content: center;
            }
            
            .header-actions {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .commission-info {
                width: 100%;
                justify-content: center;
            }
            
            .table th, .table td {
                padding: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .admin-container {
                margin: 1rem auto;
                border-radius: 12px;
            }
            
            .dashboard-content {
                padding: 1.5rem 1rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                border-radius: 12px;
            }
            
            .table th {
                display: none;
            }
            
            .table td {
                display: block;
                padding: 1rem;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .table tr {
                border-bottom: 1px solid #e2e8f0;
                display: block;
                margin-bottom: 1rem;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }
            
            .table tr:last-child {
                margin-bottom: 0;
            }
            
            .table td:before {
                content: attr(data-label);
                font-weight: 600;
                display: block;
                margin-bottom: 0.5rem;
                color: var(--primary);
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
        }

        /* Animation */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="admin-container animate__animated animate__fadeIn">
        <header class="dashboard-header">
            <div class="header-title">
                <i class="bi bi-currency-rupee"></i>
                <div>
                    <h1>Delivery Earnings Report</h1>
                    <span>Track earnings and performance of delivery partners</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="manage_delivery_boys.php" class="btn btn-outline-light">
                    <i class="bi bi-people"></i> Manage Partners
                </a>
                <a href="dashboard.php" class="btn btn-outline-light">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="stats-container">
                <div class="stat-card stat-card-primary animate__animated animate__slideUp animate__delay-1">
                    <div class="stat-title">
                        <i class="bi bi-people"></i> Active Partners
                    </div>
                    <div class="stat-value"><?php echo $total; ?></div>
                    <div class="stat-info">
                        <i class="bi bi-arrow-up"></i> Approved delivery partners
                    </div>
                </div>
                <div class="stat-card stat-card-success animate__animated animate__slideUp animate__delay-2">
                    <div class="stat-title">
                        <i class="bi bi-cash-coin"></i> Total Earnings
                    </div>
                    <div class="stat-value">₹<?php echo number_format($total_earnings, 2); ?></div>
                    <div class="stat-info">
                        <i class="bi bi-graph-up-arrow"></i> All-time earnings
                    </div>
                </div>
                <div class="stat-card stat-card-warning animate__animated animate__slideUp animate__delay-3">
                    <div class="stat-title">
                        <i class="bi bi-box-seam"></i> Total Orders
                    </div>
                    <div class="stat-value">
                        <?php 
                        $total_orders_query = "SELECT COUNT(*) AS count FROM orders WHERE status = 'Delivered'";
                        $total_orders_result = mysqli_query($conn, $total_orders_query);
                        $total_orders = mysqli_fetch_assoc($total_orders_result)['count'] ?? 0;
                        echo $total_orders;
                        ?>
                    </div>
                    <div class="stat-info">
                        <i class="bi bi-check-circle"></i> Delivered orders
                    </div>
                </div>
                <div class="stat-card stat-card-primary animate__animated animate__slideUp animate__delay-4">
                    <div class="stat-title">
                        <i class="bi bi-percent"></i> Commission Rate
                    </div>
                    <div class="stat-value">₹<?php echo $fixed_commission; ?></div>
                    <div class="stat-info">
                        <i class="bi bi-arrow-repeat"></i> Per delivered order
                    </div>
                </div>
            </div>

            <div class="table-container animate__animated animate__fadeInUp">
                <div class="table-header">
                    <h2 class="table-title">Delivery Partner Earnings</h2>
                    <div class="commission-info">
                        <i class="bi bi-info-circle"></i>
                        Commission: ₹<?php echo $fixed_commission; ?> per delivered order
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Delivery Partner</th>
                                <th>Contact</th>
                                <th>Delivered Orders</th>
                                <th>Earnings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr class="animate__animated animate__fadeIn">
                                    <td data-label="Partner">
                                        <div class="user-info">
                                            <div class="user-avatar"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                                            <div class="user-details">
                                                <div class="user-name"><?= htmlspecialchars($row['name']) ?></div>
                                                <div class="user-phone">ID: <?= $row['id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Contact"><?= htmlspecialchars($row['phone']) ?></td>
                                    <td data-label="Delivered Orders">
                                        <span class="badge bg-primary rounded-pill p-2">
                                            <?= $row['delivered_orders']; ?>
                                        </span>
                                    </td>
                                    <td data-label="Earnings" class="earnings-cell">₹<?= number_format($row['earnings'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-container">
                    <div class="pagination-info">
                        Showing <?php echo min($offset + 1, $total); ?> to <?php echo min($offset + $limit, $total); ?> of <?php echo $total; ?> partners
                    </div>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1; ?>"><i class="bi bi-chevron-left"></i></a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>"><i class="bi bi-chevron-right"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effect to table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.07)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            });
            
            // Add animation to buttons
            const buttons = document.querySelectorAll('.btn, .page-link');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.add('animate__animated', 'animate__pulse');
                    setTimeout(() => {
                        this.classList.remove('animate__animated', 'animate__pulse');
                    }, 500);
                });
            });
        });
    </script>
</body>
</html>
