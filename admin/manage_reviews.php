<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch reviews from the database
$query = "SELECT r.id, u.username, p.product_name, r.rating, r.review, r.created_at 
          FROM reviews r
          INNER JOIN users u ON r.user_id = u.id
          INNER JOIN products p ON r.product_id = p.id
          ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Ratings Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --accent-color: #36b9cc;
            --light-bg: #f8f9fc;
            --dark-bg: #2e4374;
            --text-dark: #5a5c69;
            --text-light: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #e6e9ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 15px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }
        
        .card-title {
            font-weight: 600;
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .btn-back {
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-back:hover {
            background: #f0f2f5;
            color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .table-responsive {
            border-radius: 0 0 12px 12px;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead {
            background-color: #f8f9fc;
        }
        
        .table th {
            font-weight: 600;
            color: var(--text-dark);
            padding: 15px 20px;
            border-top: none;
            border-bottom: 2px solid #e3e6f0;
        }
        
        .table td {
            padding: 15px 20px;
            vertical-align: middle;
            border-top: 1px solid #eaecf4;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        .rating-stars {
            color: #FFD700;
            font-size: 18px;
            letter-spacing: 2px;
            margin-right: 5px;
        }
        
        .review-content {
            max-width: 300px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .btn-delete {
            background: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-delete:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(231, 74, 59, 0.15);
        }
        
        .date-cell {
            min-width: 140px;
        }
        
        .alert-success {
            background: rgba(28, 200, 138, 0.15);
            border: 1px solid rgba(28, 200, 138, 0.3);
            color: #155724;
            border-radius: 8px;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .admin-container {
                margin: 20px auto;
                padding: 0 10px;
            }
            
            .dashboard-card {
                border-radius: 10px;
            }
            
            .card-header {
                padding: 15px;
            }
            
            .table th, .table td {
                padding: 12px 15px;
                font-size: 14px;
            }
            
            .rating-stars {
                font-size: 16px;
            }
            
            .btn-delete, .btn-back {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
        }
        
        @media (max-width: 576px) {
            .table {
                min-width: 600px;
            }
            
            .card-title {
                font-size: 1.2rem;
            }
            
            .btn-back {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="dashboard-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h2 class="card-title">
                        <i class="fas fa-star"></i> User Reviews & Ratings
                    </h2>
                    <a href="dashboard.php" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            
            <div class="p-4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success text-center mb-4">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Product</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td><?= htmlspecialchars($row['product_name']); ?></td>
                                    <td>
                                        <span class="rating-stars"><?= str_repeat('★', $row['rating']) ?></span>
                                        <span class="text-muted">(<?= $row['rating'] ?>)</span>
                                    </td>
                                    <td class="review-content"><?= htmlspecialchars($row['review']); ?></td>
                                    <td class="date-cell"><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <form action="delete_review.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            <input type="hidden" name="review_id" value="<?= $row['id']; ?>">
                                            <button type="submit" class="btn btn-delete">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
