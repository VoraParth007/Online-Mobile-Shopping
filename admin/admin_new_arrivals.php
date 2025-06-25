<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

$message = ""; // Message variable for success notification

// Check if form is submitted
if (isset($_POST['update_arrivals'])) {
    // Set all products to 0 first (remove from new arrivals)
    mysqli_query($conn, "UPDATE products SET new_arrival = 0");

    // If at least one checkbox is checked, update the status
    if (isset($_POST['new_arrivals'])) {
        foreach ($_POST['new_arrivals'] as $product_id) {
            mysqli_query($conn, "UPDATE products SET new_arrival = 1 WHERE id = '$product_id'");
        }
        $_SESSION['message'] = "New Arrivals updated successfully!";
    } else {
        $_SESSION['message'] = "Products removed from New Arrivals!";
    }

    // Redirect to avoid form resubmission issue
    header("Location: admin_new_arrivals.php");
    exit();
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage New Arrivals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-bottom: none;
            padding: 1.25rem;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-color);
            border-bottom-width: 1px;
        }
        
        .table td, .table th {
            vertical-align: middle;
            padding: 1rem;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                border: 1px solid #dee2e6;
                border-radius: 8px;
            }
            
            .table thead {
                display: none;
            }
            
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 8px;
            }
            
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #dee2e6;
            }
            
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: calc(50% - 1rem);
                padding-right: 1rem;
                font-weight: 600;
                text-align: left;
                color: var(--dark-color);
            }
            
            .product-image {
                width: 80px;
                height: 80px;
                margin: 0 auto;
                display: block;
            }
            
            .action-buttons {
                justify-content: center;
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-star me-2"></i>Manage New Arrivals</h4>
                        <a href="dashboard.php" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Dashboard
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Show Success Message After Update -->
                        <?php if (isset($_SESSION['message'])) { ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: '<?php echo $_SESSION['message']; ?>',
                                    confirmButtonColor: 'var(--primary-color)',
                                    confirmButtonText: 'OK'
                                });
                            </script>
                            <?php unset($_SESSION['message']); // Clear message after displaying ?>
                        <?php } ?>

                        <form method="post">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th class="text-center">New Arrival</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                                            <tr>
                                                <td data-label="Image">
                                                    <img src="uploads/<?php echo $product['image']; ?>" 
                                                         class="product-image" 
                                                         alt="<?php echo $product['product_name']; ?>">
                                                </td>
                                                <td data-label="Product Name"><?php echo $product['product_name']; ?></td>
                                                <td data-label="Price">$<?php echo number_format($product['price'], 2); ?></td>
                                                <td data-label="New Arrival" class="text-center">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               name="new_arrivals[]" 
                                                               value="<?php echo $product['id']; ?>"
                                                               <?php echo ($product['new_arrival'] == 1) ? 'checked' : ''; ?>>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="action-buttons mt-4">
                                <button type="submit" name="update_arrivals" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update New Arrivals
                                </button>
                                <a href="dashboard.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add responsive behavior to table cells
        document.addEventListener('DOMContentLoaded', function() {
            const cells = document.querySelectorAll('td');
            const headers = document.querySelectorAll('th');
            
            if (window.innerWidth <= 768) {
                cells.forEach((cell, index) => {
                    const headerIndex = index % headers.length;
                    cell.setAttribute('data-label', headers[headerIndex].textContent);
                });
            }
        });
    </script>
</body>
</html>
