<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch products from the database
$query = "SELECT p.*, c.category_name, b.brand_name FROM products p 
          JOIN categories c ON p.category_id = c.id 
          JOIN brands b ON p.brand_id = b.id ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #f72585;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #ef233c;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            --card-hover: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f8ff;
            color: var(--dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        
        /* App-like header */
        .app-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .app-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .app-title i {
            font-size: 1.1em;
        }
        
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-2px);
        }
        
        /* Content area */
        .content {
            padding: 20px;
            padding-bottom: 80px;
        }
        
        /* Floating action button */
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
            z-index: 90;
            transition: all 0.3s;
            border: none;
        }
        
        .fab:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s;
            border: none;
        }
        
        .card:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-3px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Product list */
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .product-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
        }
        
        .product-item:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-2px);
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .product-meta-item {
            font-size: 0.85rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .product-id {
            font-size: 0.8rem;
            color: var(--gray);
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 10px;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .edit-btn {
            background: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }
        
        .delete-btn {
            background: rgba(239, 35, 60, 0.1);
            color: var(--danger);
        }
        
        .action-btn:hover {
            transform: scale(1.05);
        }
        
        .availability-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .in-stock {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }
        
        .out-of-stock {
            background: rgba(239, 35, 60, 0.1);
            color: var(--danger);
        }
        
        /* Alert notifications */
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #e2e8f0;
        }
        
        /* Desktop table view */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }
        
        .table th {
            padding: 15px;
            font-weight: 500;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .table-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991px) {
            .table-responsive {
                display: none;
            }
            
            .product-list {
                display: block;
            }
        }
        
        @media (min-width: 992px) {
            .product-list {
                display: none;
            }
            
            .content {
                padding: 30px;
                padding-bottom: 100px;
            }
            
            .fab {
                bottom: 30px;
                right: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- App-like header -->
    <header class="app-header">
        <button class="back-btn" onclick="window.location.href='dashboard.php'">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="app-title">
            <i class="fas fa-box"></i> Manage Products
        </h1>
        <div style="width: 40px;"></div> <!-- Spacer for balance -->
    </header>

    <main class="content">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show animate" role="alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show animate" role="alert">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Desktop Table View -->
        <div class="table-responsive animate">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php mysqli_data_seek($result, 0); // Reset pointer
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td>
                                <img src="uploads/<?= $row['image']; ?>" class="table-img" alt="<?= htmlspecialchars($row['product_name']); ?>">
                            </td>
                            <td><?= htmlspecialchars($row['product_name']); ?></td>
                            <td><?= htmlspecialchars($row['category_name']); ?></td>
                            <td><?= htmlspecialchars($row['brand_name']); ?></td>
                            <td>₹<?= number_format($row['price'], 2); ?></td>
                            <td><?= $row['stock']; ?></td>
                            <td>
                                <span class="availability-badge <?= $row['availability'] == 'In Stock' ? 'in-stock' : 'out-of-stock'; ?>">
                                    <?= $row['availability']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['id']; ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?= $row['id']; ?>" class="action-btn delete-btn" onclick="return confirmDelete(<?= $row['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="product-list">
            <?php mysqli_data_seek($result, 0); // Reset pointer
            while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-item animate">
                    <div class="d-flex">
                        <img src="uploads/<?= $row['image']; ?>" class="product-image" alt="<?= htmlspecialchars($row['product_name']); ?>">
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($row['product_name']); ?></div>
                            <div class="product-id">ID: <?= $row['id']; ?></div>
                            
                            <div class="product-meta">
                                <div class="product-meta-item">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($row['category_name']); ?>
                                </div>
                                <div class="product-meta-item">
                                    <i class="fas fa-tags"></i> <?= htmlspecialchars($row['brand_name']); ?>
                                </div>
                                <div class="product-meta-item">
                                    <i class="fas fa-rupee-sign"></i> ₹<?= number_format($row['price'], 2); ?>
                                </div>
                                <div class="product-meta-item">
                                    <i class="fas fa-boxes"></i> <?= $row['stock']; ?> in stock
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="availability-badge <?= $row['availability'] == 'In Stock' ? 'in-stock' : 'out-of-stock'; ?>">
                                    <?= $row['availability']; ?>
                                </span>
                            </div>
                            
                            <div class="product-actions">
                                <a href="edit_product.php?id=<?= $row['id']; ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?= $row['id']; ?>" class="action-btn delete-btn" onclick="return confirmDelete(<?= $row['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Floating Action Button -->
        <a href="add_product.php" class="fab">
            <i class="fas fa-plus"></i>
        </a>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Delete confirmation with SweetAlert2
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Product?',
                text: "This will permanently delete the product",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--gray)',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                background: 'white',
                backdrop: `
                    rgba(0,0,0,0.4)
                    url("/images/trash-animation.gif")
                    center top
                    no-repeat
                `,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "delete_product.php?id=" + id;
                }
            });
            return false;
        }

        // Add animation class to elements as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.product-item, .alert, .table-responsive').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
