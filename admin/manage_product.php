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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            border-radius: 5px;
        }
        .product-img {
            border-radius: 8px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-primary"><i class="fa-solid fa-box"></i> Manage Products</h2>
                <a href="./dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-muted">All Products</h5>
                <a href="add_product.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add New Product</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td>
                                    <img src="uploads/<?= $row['image']; ?>" width="70" height="70" class="product-img" alt="Product Image">
                                </td>
                                <td><?= htmlspecialchars($row['product_name']); ?></td>
                                <td><?= htmlspecialchars($row['category_name']); ?></td>
                                <td><?= htmlspecialchars($row['brand_name']); ?></td>
                                <td>₹<?= number_format($row['price'], 2); ?></td>
                                <td><?= $row['stock']; ?></td>
                                <td>
                                    <span class="badge <?= $row['availability'] == 'In Stock' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?= $row['availability']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
