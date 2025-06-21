<?php
session_name("admin_session");
session_start();
include('../includes/config.php');
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch counts from database
$categoryCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM categories"))['count'];
$brandCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM brands"))['count'];
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];
$productCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM products"))['count'];
$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM orders"))['count'];
$inquiryCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM inquiries"))['count'];
$salesReportCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM sales_reports"))['count'];
$reviewCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM reviews"))['count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --info-color: #560bad;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            height: 100vh;
            width: 280px;
            position: fixed;
            background: linear-gradient(180deg, var(--dark-color), var(--secondary-color));
            color: white;
            padding: 0;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
            text-align: center;
        }

        .sidebar-menu {
            padding: 15px 0;
            overflow-y: auto;
            height: calc(100% - 120px);
        }

        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            padding: 12px 25px;
            margin: 5px 10px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-menu a:hover i, .sidebar-menu a.active i {
            color: var(--accent-color);
        }

        .logout-btn {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            width: calc(100% - 40px);
            margin: 10px 20px;
            padding: 12px;
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background-color: rgba(255, 0, 0, 0.2);
            color: white;
        }

        .logout-btn i {
            margin-right: 8px;
        }

        /* Main Content */
        .dashboard-content {
            margin-left: 280px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .dashboard-header h2 {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }

        .dashboard-card.category-card::before {
            background-color: var(--primary-color);
        }

        .dashboard-card.brand-card::before {
            background-color: var(--success-color);
        }

        .dashboard-card.user-card::before {
            background-color: var(--warning-color);
        }

        .dashboard-card.product-card::before {
            background-color: var(--danger-color);
        }

        .dashboard-card.review-card::before {
            background-color: var(--accent-color);
        }

        .dashboard-card.order-card::before {
            background-color: var(--info-color);
        }

        .dashboard-card.inquiry-card::before {
            background-color: #7209b7;
        }

        .dashboard-card.sales-card::before {
            background-color: #3a0ca3;
        }

        .card-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .category-card .card-icon {
            background-color: var(--primary-color);
        }

        .brand-card .card-icon {
            background-color: var(--success-color);
        }

        .user-card .card-icon {
            background-color: var(--warning-color);
        }

        .product-card .card-icon {
            background-color: var(--danger-color);
        }

        .review-card .card-icon {
            background-color: var(--accent-color);
        }

        .order-card .card-icon {
            background-color: var(--info-color);
        }

        .inquiry-card .card-icon {
            background-color: #7209b7;
        }

        .sales-card .card-icon {
            background-color: #3a0ca3;
        }

        .card-text h3 {
            font-size: 14px;
            font-weight: 500;
            color: #666;
            margin-bottom: 5px;
        }

        .card-text h2 {
            font-size: 28px;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .card-text p {
            font-size: 12px;
            color: #999;
            margin: 5px 0 0;
        }

        /* Responsive Design */
        @media (max-width: 1199px) {
            .sidebar {
                width: 250px;
            }
            .dashboard-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1001;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .dashboard-content {
                margin-left: 0;
            }
            .mobile-menu-btn {
                display: block !important;
            }
        }

        @media (max-width: 767px) {
            .dashboard-cards {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }

        @media (max-width: 575px) {
            .dashboard-content {
                padding: 15px;
            }
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .user-profile {
                margin-top: 10px;
            }
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--dark-color);
            cursor: pointer;
            margin-right: 15px;
        }

        /* Overlay for mobile menu */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .overlay.active {
            display: block;
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn d-lg-none" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Overlay for mobile menu -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-shield-alt me-2"></i>Admin Panel</h4>
        </div>
        <div class="sidebar-menu">
            <a href="admin_profile.php"><i class="fas fa-user-cog"></i> Admin Profile</a>
            <a href="manage_categories.php"><i class="fas fa-list"></i> Manage Categories</a>
            <a href="manage_brands.php"><i class="fas fa-tags"></i> Manage Brands</a>
            <a href="manage_product.php"><i class="fas fa-boxes"></i> Manage Products</a>
            <a href="admin_new_arrivals.php"><i class="fas fa-star"></i> New Arrivals</a>
            <a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Manage Orders</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_blogs.php"><i class="fas fa-blog"></i> Manage Blogs</a>
            <a href="manage_delivery_boys.php"><i class="fas fa-truck"></i> Delivery Boys</a>
            <a href="manage_sales_reports.php"><i class="fas fa-chart-line"></i> Sales Reports</a>
            <a href="manage_reviews.php"><i class="fas fa-star-half-alt"></i> Reviews & Ratings</a>
            <a href="manage_subscribers.php"><i class="fas fa-envelope"></i> Subscribers</a>
            <a href="manage_inquiries.php"><i class="fas fa-question-circle"></i> Customer Inquiries</a>
        </div>
        <button class="logout-btn" onclick="window.location.href='./logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content" id="dashboardContent">
        <div class="dashboard-header">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h2>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                <span>Welcome, Admin</span>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="dashboard-card category-card" onclick="window.location.href='manage_categories.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Categories</h3>
                        <h2><?php echo $categoryCount; ?></h2>
                        <p>Total categories in system</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card brand-card" onclick="window.location.href='manage_brands.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Brands</h3>
                        <h2><?php echo $brandCount; ?></h2>
                        <p>Total brands registered</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card user-card" onclick="window.location.href='manage_users.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Users</h3>
                        <h2><?php echo $userCount; ?></h2>
                        <p>Total registered users</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card product-card" onclick="window.location.href='manage_product.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Products</h3>
                        <h2><?php echo $productCount; ?></h2>
                        <p>Products in inventory</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card review-card" onclick="window.location.href='manage_reviews.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Reviews</h3>
                        <h2><?php echo $reviewCount; ?></h2>
                        <p>Customer reviews</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card order-card" onclick="window.location.href='manage_orders.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Orders</h3>
                        <h2><?php echo $orderCount; ?></h2>
                        <p>Total orders placed</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card inquiry-card" onclick="window.location.href='manage_inquiries.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Inquiries</h3>
                        <h2><?php echo $inquiryCount; ?></h2>
                        <p>Customer inquiries</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

            <div class="dashboard-card sales-card" onclick="window.location.href='manage_sales_reports.php'">
                <div class="card-content">
                    <div class="card-text">
                        <h3>Sales Reports</h3>
                        <h2><?php echo $salesReportCount; ?></h2>
                        <p>Generated reports</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const dashboardContent = document.getElementById('dashboardContent');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Make all dashboard cards clickable
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', function() {
                window.location.href = this.getAttribute('onclick').match(/'(.*?)'/)[1];
            });
        });

        // Active menu item highlighting
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
