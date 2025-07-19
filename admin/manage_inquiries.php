<?php 
session_name("admin_session");
session_start();
include '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Inquiries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6f42c1;
            --success: #1cc88a;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #2e4374;
            --text: #5a5c69;
            --text-light: #858796;
            --card-bg: #ffffff;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #e6e9ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            padding: 15px;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header-container {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            color: white;
        }
        
        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0;
            font-weight: 600;
            font-size: 1.7rem;
        }
        
        .btn-back {
            background: white;
            color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }
        
        .btn-back:hover {
            background: #f0f2f5;
            color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Inquiry Cards for Mobile */
        .inquiry-card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .inquiry-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }
        
        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        
        .inquiry-user {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--dark);
        }
        
        .inquiry-status {
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        
        .status-open {
            background-color: rgba(28, 200, 138, 0.15);
            color: #155724;
        }
        
        .status-closed {
            background-color: rgba(231, 74, 59, 0.15);
            color: #721c24;
        }
        
        .inquiry-detail {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .detail-value {
            font-size: 1rem;
            color: var(--text);
            word-break: break-word;
        }
        
        .inquiry-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            flex: 1;
            min-width: 120px;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-reply {
            background-color: rgba(28, 200, 138, 0.15);
            color: #155724;
        }
        
        .btn-reply:hover {
            background-color: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(28, 200, 138, 0.2);
        }
        
        .btn-close {
            background-color: rgba(231, 74, 59, 0.15);
            color: #721c24;
        }
        
        .btn-close:hover {
            background-color: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(231, 74, 59, 0.2);
        }
        
        /* Table for Desktop */
        .desktop-table {
            display: none;
        }
        
        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .empty-icon {
            font-size: 3.5rem;
            color: #d1d3e2;
            margin-bottom: 20px;
        }
        
        .empty-text {
            color: var(--text-light);
            font-size: 1.2rem;
            margin: 0;
        }
        
        /* Responsive Design */
        @media (min-width: 992px) {
            .mobile-cards {
                display: none;
            }
            
            .desktop-table {
                display: block;
            }
            
            .table-container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                padding: 20px;
            }
            
            .table {
                margin: 0;
            }
            
            .table thead {
                background-color: var(--dark);
                color: white;
            }
            
            .table th {
                font-weight: 600;
                padding: 16px 20px;
                border: none;
                vertical-align: middle;
            }
            
            .table td {
                padding: 14px 20px;
                vertical-align: middle;
                border-top: 1px solid #eaecf4;
            }
            
            .table tr:hover {
                background-color: rgba(78, 115, 223, 0.05);
            }
            
            .table .badge {
                font-weight: 600;
                padding: 8px 14px;
                border-radius: 20px;
                font-size: 0.9rem;
                text-transform: uppercase;
            }
            
            .table .btn-action {
                min-width: auto;
                padding: 8px 15px;
            }
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .header-container {
                padding: 18px;
            }
            
            .btn-back {
                padding: 9px 16px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 12px;
            }
            
            .page-title {
                font-size: 1.4rem;
            }
            
            .header-container {
                padding: 15px;
                border-radius: 10px;
            }
            
            .inquiry-card {
                padding: 18px;
            }
            
            .btn-action {
                padding: 10px;
                font-size: 0.9rem;
                min-width: 100px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="header-container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h1 class="page-title">
                    <i class="bi bi-chat-dots-fill"></i> Manage Inquiries
                </h1>
                <a href="dashboard.php" class="btn btn-back">
                    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <?php
        $result = $conn->query("
            SELECT inquiries.*, products.product_name AS product_name 
            FROM inquiries 
            JOIN products ON inquiries.product_id = products.id 
            ORDER BY inquiries.created_at DESC
        ");
        ?>

        <!-- Mobile Cards View -->
        <div class="mobile-cards">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="inquiry-user">
                                <?= htmlspecialchars($row['name']) ?>
                            </div>
                            <div class="inquiry-status <?= $row['status'] === 'Closed' ? 'status-closed' : 'status-open' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </div>
                        </div>
                        
                        <div class="inquiry-detail">
                            <span class="detail-label">
                                <i class="bi bi-box-seam me-1"></i> Product
                            </span>
                            <span class="detail-value"><?= htmlspecialchars($row['product_name']) ?></span>
                        </div>
                        
                        <div class="inquiry-detail">
                            <span class="detail-label">
                                <i class="bi bi-chat-left-text me-1"></i> Message
                            </span>
                            <span class="detail-value"><?= htmlspecialchars($row['message']) ?></span>
                        </div>
                        
                        <div class="inquiry-detail">
                            <span class="detail-label">
                                <i class="bi bi-reply me-1"></i> Response
                            </span>
                            <span class="detail-value">
                                <?= $row['response'] ? htmlspecialchars($row['response']) : '<span class="text-muted">No response yet</span>' ?>
                            </span>
                        </div>
                        
                        <div class="inquiry-actions">
                            <a href="reply_inquiry.php?id=<?= $row['id'] ?>" class="btn-action btn-reply">
                                <i class="bi bi-reply-fill"></i> Reply
                            </a>
                            <a href="close_inquiry.php?id=<?= $row['id'] ?>" class="btn-action btn-close">
                                <i class="bi bi-x-circle-fill"></i> Close
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <p class="empty-text">No inquiries found</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Desktop Table View -->
        <div class="desktop-table">
            <div class="table-container">
                <?php 
                // Reset pointer for desktop table
                $result->data_seek(0);
                ?>
                
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-person-fill me-1"></i> User</th>
                                    <th><i class="bi bi-box-seam me-1"></i> Product</th>
                                    <th><i class="bi bi-chat-left-text-fill me-1"></i> Message</th>
                                    <th><i class="bi bi-info-circle-fill me-1"></i> Status</th>
                                    <th><i class="bi bi-reply-fill me-1"></i> Response</th>
                                    <th><i class="bi bi-tools me-1"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                                        <td><?= htmlspecialchars($row['message']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $row['status'] === 'Closed' ? 'danger' : 'success' ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($row['response']) ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="reply_inquiry.php?id=<?= $row['id'] ?>" class="btn btn-reply btn-action">
                                                    <i class="bi bi-reply-fill"></i> Reply
                                                </a>
                                                <a href="close_inquiry.php?id=<?= $row['id'] ?>" class="btn btn-close btn-action">
                                                    <i class="bi bi-x-circle-fill"></i> Close
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <p class="empty-text">No inquiries found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
