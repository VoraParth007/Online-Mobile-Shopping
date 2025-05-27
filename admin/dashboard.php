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
$reviewCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM reviews"))['count']; // Fetch reviews count
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Sidebar Styling */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background: #343a40;
            color: white;
            padding: 15px;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 12px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background: #495057;
        }

        /* Main Content */
        .dashboard-content {
            margin-left: 270px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Card Hover Effect */
        .card {
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 200px;
            }

            .dashboard-content {
                margin-left: 220px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 180px;
            }

            .dashboard-content {
                margin-left: 190px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 10px;
                text-align: center;
            }

            .sidebar a {
                display: inline-block;
                margin: 5px;
                padding: 8px;
            }

            .dashboard-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>

</head>

<body>
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <hr>
        <a href="admin_profile.php"><i class="fas fa-user"></i> Admin Profile</a>

        <a href="manage_categories.php"><i class="fas fa-list"></i> Manage Categories</a>
        <a href="manage_brands.php"><i class="fas fa-tags"></i> Manage Brands</a>
        <a href="manage_product.php"><i class="fas fa-box"></i> Manage Products</a>
        <a href="admin_new_arrivals.php"><i class="fas fa-box"></i> Manage new Arrivals products</a>
        <a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Manage Orders</a>
        <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
        <a href="manage_blogs.php"><i class="fas fa-users"></i> Manage Blogs</a>
        <a href="manage_delivery_boys.php"><i class="fas fa-users"></i> Manage deliverry Boys</a>
        <a href="manage_sales_reports.php"><i class="fas fa-chart-line"></i> Sales Reports</a>
        <a href="manage_reviews.php">📊 Reviews & Ratings</a>
        <a href="manage_subscribers.php"><i class="fas fa-envelope"></i> Subscribers</a>
        <a href="manage_inquiries.php"><i class="fas fa-envelope"></i> Customer Inquiries</a>
        <a href="./logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="dashboard-content">
        <h2 class="text-center">Admin Dashboard</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
            <div class="col">
                <div class="card text-center shadow bg-primary text-white h-100 card-link" data-href="manage_categories.php">
                    <div class="card-body">
                        <i class="fas fa-layer-group fa-3x"></i>
                        <h5 class="card-title mt-2">Categories</h5>
                        <p class="card-text">Total: <?php echo $categoryCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-success text-white h-100 card-link" data-href="manage_brands.php">
                    <div class="card-body">
                        <i class="fas fa-tags fa-3x"></i>
                        <h5 class="card-title mt-2">Brands</h5>
                        <p class="card-text">Total: <?php echo $brandCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-warning text-dark h-100 card-link" data-href="manage_users.php">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x"></i>
                        <h5 class="card-title mt-2">Users</h5>
                        <p class="card-text">Total: <?php echo $userCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-danger text-white h-100 card-link" data-href="manage_product.php">
                    <div class="card-body">
                        <i class="fas fa-box fa-3x"></i>
                        <h5 class="card-title mt-2">Products</h5>
                        <p class="card-text">Total: <?php echo $productCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-warning text-dark h-100 card-link" data-href="manage_reviews.php">
                    <div class="card-body">
                        <i class="fas fa-star fa-3x"></i>
                        <h5 class="card-title mt-2">Reviews & Ratings</h5>
                        <p class="card-text">Total: <?php echo $reviewCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-info text-white h-100 card-link" data-href="manage_orders.php">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-3x"></i>
                        <h5 class="card-title mt-2">Orders</h5>
                        <p class="card-text">Total: <?php echo $orderCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-secondary text-white h-100 card-link" data-href="manage_inquiries.php">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-3x"></i>
                        <h5 class="card-title mt-2">Inquiries</h5>
                        <p class="card-text">Total: <?php echo $inquiryCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow bg-dark text-white h-100 card-link" data-href="manage_sales_reports.php">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x"></i>
                        <h5 class="card-title mt-2">Sales Reports</h5>
                        <p class="card-text">Total: <?php echo $salesReportCount; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for Redirection -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let cards = document.querySelectorAll(".card-link");
                cards.forEach(card => {
                    card.style.cursor = "pointer";
                    card.addEventListener("click", function() {
                        let url = this.getAttribute("data-href");
                        window.location.href = url;
                    });
                });
            });
        </script>
    </div>

</body>

</html>