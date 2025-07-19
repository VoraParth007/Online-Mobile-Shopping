<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle approval or rejection
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'approve') {
        mysqli_query($conn, "UPDATE delivery_boys SET status='Approved' WHERE id=$id");
    } elseif ($_GET['action'] == 'reject') {
        mysqli_query($conn, "UPDATE delivery_boys SET status='Rejected' WHERE id=$id");
    }
    header("Location: manage_delivery_boys.php");
    exit;
}

// Fetch delivery boys
$query = mysqli_query($conn, "SELECT * FROM delivery_boys");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Delivery Partners | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
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
            padding: 15px;
        }

        .admin-container {
            max-width: 1400px;
            margin: 1rem auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .dashboard-header {
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.2rem 1.5rem;
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
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .header-title h1 {
            font-weight: 600;
            font-size: 1.5rem;
            margin: 0;
        }

        .header-title span {
            font-weight: 300;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            transition: var(--transition);
            border: none;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            font-size: 0.9rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-success {
            background: var(--success);
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
            padding: 1.5rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            border-left: 4px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-title {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .stat-info {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
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
            padding: 1rem;
            background: #f9fafc;
            border-bottom: 1px solid #edf2f7;
        }

        .table-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--dark);
            margin: 0;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 10px;
            padding: 0.4rem 0.8rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            width: 100%;
        }

        .search-box input {
            border: none;
            padding: 0.3rem;
            outline: none;
            font-family: 'Poppins', sans-serif;
            width: 100%;
        }

        .search-box i {
            color: var(--gray);
            margin-right: 8px;
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
            padding: 1rem;
            font-weight: 600;
            color: var(--dark);
            text-align: left;
            border-bottom: 2px solid #edf2f7;
        }

        .table td {
            padding: 1rem;
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            margin-bottom: 3px;
            font-size: 0.95rem;
        }

        .user-email {
            font-size: 0.8rem;
            color: var(--gray);
        }

        .status-badge {
            padding: 0.5rem 0.8rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-approved {
            background: rgba(56, 176, 0, 0.1);
            color: var(--success);
        }

        .badge-pending {
            background: rgba(255, 170, 0, 0.1);
            color: var(--warning);
        }

        .badge-rejected {
            background: rgba(255, 61, 113, 0.1);
            color: var(--danger);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-approve {
            background: var(--success);
            color: white;
        }

        .btn-reject {
            background: var(--danger);
            color: white;
        }

        .no-action {
            font-size: 0.85rem;
            color: var(--gray);
            font-style: italic;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f9fafc;
            border-top: 1px solid #edf2f7;
        }

        .pagination-info {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .pagination {
            display: flex;
            gap: 6px;
        }

        .page-item {
            list-style: none;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: white;
            color: var(--primary);
            font-weight: 500;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
            text-decoration: none;
            font-size: 0.85rem;
        }

        .page-link:hover, .page-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* ================== MOBILE OPTIMIZATION ================== */
        @media (max-width: 992px) {
            .dashboard-header {
                flex-direction: column;
                text-align: center;
                padding: 1.2rem;
            }
            
            .header-title {
                justify-content: center;
                margin-bottom: 1rem;
            }
            
            .header-actions {
                justify-content: center;
                width: 100%;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 1.2rem;
            }
            
            .table-header {
                flex-direction: column;
                gap: 0.8rem;
                padding: 0.8rem;
                text-align: center;
            }
            
            .search-box {
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }
            
            .table th, .table td {
                padding: 0.8rem;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-value {
                font-size: 1.4rem;
            }
        }

        /* Enhanced Mobile View (below 576px) */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .admin-container {
                margin: 0.5rem auto;
                border-radius: 14px;
            }
            
            .dashboard-header {
                padding: 1rem;
            }
            
            .header-title h1 {
                font-size: 1.3rem;
            }
            
            .header-title i {
                width: 40px;
                height: 40px;
            }
            
            .dashboard-content {
                padding: 1rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .table-container {
                border-radius: 14px;
            }
            
            /* Card-based layout for mobile */
            .table-responsive {
                overflow-x: visible;
            }
            
            .table, .table thead, .table tbody, .table th, .table td, .table tr {
                display: block;
                width: 100%;
            }
            
            .table thead {
                display: none;
            }
            
            .table tr {
                margin-bottom: 1.2rem;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                background: white;
                padding: 0;
            }
            
            .table td {
                padding: 1rem;
                border: none;
                border-bottom: 1px solid #edf2f7;
                position: relative;
                padding-left: 40%;
            }
            
            .table td:last-child {
                border-bottom: none;
            }
            
            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                width: 35%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                color: var(--primary);
                font-size: 0.85rem;
            }
            
            /* Special handling for user info */
            .table td[data-label="Partner"] {
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
                background: rgba(67, 97, 238, 0.05);
            }
            
            .table td[data-label="Partner"]:before {
                top: 1.5rem;
                transform: none;
            }
            
            .table td[data-label="Partner"] .user-info {
                flex-direction: column;
                text-align: center;
                margin-left: 40%;
            }
            
            .table td[data-label="Partner"] .user-avatar {
                margin-bottom: 0.5rem;
            }
            
            /* Action buttons styling */
            .table td[data-label="Actions"] .action-buttons {
                flex-direction: column;
            }
            
            .table td[data-label="Actions"] .btn-action {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }
            
            .table td[data-label="Actions"] .btn-action:last-child {
                margin-bottom: 0;
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
                text-align: center;
            }
            
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
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
                <i class="bi bi-truck"></i>
                <div>
                    <h1>Delivery Partners</h1>
                    <span>Manage delivery partner applications</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="delivery_earnings.php" class="btn btn-success">
                    <i class="bi bi-graph-up"></i> Earnings
                </a>
                <a href="dashboard.php" class="btn btn-outline-light">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="stats-container">
                <div class="stat-card animate__animated animate__slideUp animate__delay-1">
                    <div class="stat-title">
                        <i class="bi bi-people"></i> Total Partners
                    </div>
                    <div class="stat-value"><?php echo mysqli_num_rows($query); ?></div>
                    <div class="stat-info">
                        <i class="bi bi-arrow-up"></i> Registered partners
                    </div>
                </div>
                <div class="stat-card animate__animated animate__slideUp animate__delay-2">
                    <div class="stat-title">
                        <i class="bi bi-hourglass-split"></i> Pending
                    </div>
                    <div class="stat-value">
                        <?php 
                        $pending_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM delivery_boys WHERE status='Pending'");
                        $pending = mysqli_fetch_assoc($pending_query);
                        echo $pending['count'];
                        ?>
                    </div>
                    <div class="stat-info">
                        <i class="bi bi-exclamation-circle"></i> Needs review
                    </div>
                </div>
                <div class="stat-card animate__animated animate__slideUp animate__delay-3">
                    <div class="stat-title">
                        <i class="bi bi-check-circle"></i> Approved
                    </div>
                    <div class="stat-value">
                        <?php 
                        $approved_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM delivery_boys WHERE status='Approved'");
                        $approved = mysqli_fetch_assoc($approved_query);
                        echo $approved['count'];
                        ?>
                    </div>
                    <div class="stat-info">
                        <i class="bi bi-check"></i> Active partners
                    </div>
                </div>
                <div class="stat-card animate__animated animate__slideUp animate__delay-4">
                    <div class="stat-title">
                        <i class="bi bi-x-circle"></i> Rejected
                    </div>
                    <div class="stat-value">
                        <?php 
                        $rejected_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM delivery_boys WHERE status='Rejected'");
                        $rejected = mysqli_fetch_assoc($rejected_query);
                        echo $rejected['count'];
                        ?>
                    </div>
                    <div class="stat-info">
                        <i class="bi bi-arrow-right"></i> View details
                    </div>
                </div>
            </div>

            <div class="table-container animate__animated animate__fadeInUp">
                <div class="table-header">
                    <h2 class="table-title">Delivery Partners</h2>
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search partners...">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Delivery Partner</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reset the pointer to the beginning
                            mysqli_data_seek($query, 0);
                            while ($row = mysqli_fetch_assoc($query)) { ?>
                                <tr class="animate__animated animate__fadeIn">
                                    <td data-label="Partner">
                                        <div class="user-info">
                                            <div class="user-avatar"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                                            <div class="user-details">
                                                <div class="user-name"><?= htmlspecialchars($row['name']) ?></div>
                                                <div class="user-email"><?= htmlspecialchars($row['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Contact"><?= htmlspecialchars($row['phone']) ?></td>
                                    <td data-label="Status">
                                        <?php if ($row['status'] == 'Approved') { ?>
                                            <span class="status-badge badge-approved">
                                                <i class="bi bi-check-circle"></i> Approved
                                            </span>
                                        <?php } elseif ($row['status'] == 'Rejected') { ?>
                                            <span class="status-badge badge-rejected">
                                                <i class="bi bi-x-circle"></i> Rejected
                                            </span>
                                        <?php } else { ?>
                                            <span class="status-badge badge-pending">
                                                <i class="bi bi-hourglass-split"></i> Pending
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <?php if ($row['status'] == 'Pending') { ?>
                                                <a href="?action=approve&id=<?= $row['id'] ?>" class="btn-action btn-approve">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </a>
                                                <a href="?action=reject&id=<?= $row['id'] ?>" class="btn-action btn-reject">
                                                    <i class="bi bi-x-lg"></i> Reject
                                                </a>
                                            <?php } else { ?>
                                                <span class="no-action">Action completed</span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-container">
                    <div class="pagination-info">Showing all <?php echo mysqli_num_rows($query); ?> partners</div>
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
            const buttons = document.querySelectorAll('.btn, .btn-action');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!this.classList.contains('btn-disabled')) {
                        this.classList.add('animate__animated', 'animate__pulse');
                        setTimeout(() => {
                            this.classList.remove('animate__animated', 'animate__pulse');
                        }, 500);
                    }
                });
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('.table tbody tr');
                    
                    tableRows.forEach(row => {
                        const rowText = row.textContent.toLowerCase();
                        if (rowText.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
            
            // Mobile touch feedback
            if (window.innerWidth < 768) {
                tableRows.forEach(row => {
                    row.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                        this.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
                    });
                    
                    row.addEventListener('touchend', function() {
                        this.style.transform = '';
                        this.style.boxShadow = '';
                    });
                });
            }
        });
    </script>
</body>

</html>
