<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// [Previous PHP code remains exactly the same...]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        
        /* Card styling */
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s;
            border: none;
        }
        
        .form-card:hover {
            box-shadow: var(--card-hover);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 15px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Form elements */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background-color: white;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        /* Specification fields */
        .spec-group {
            margin-bottom: 15px;
        }
        
        .spec-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 5px;
            display: block;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            gap: 8px;
        }
        
        .btn i {
            font-size: 1em;
        }
        
        .btn-block {
            display: flex;
            width: 100%;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        /* Alert notifications */
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }
        
        /* Responsive grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .span-2 {
                grid-column: span 2;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        /* File input styling */
        .file-upload {
            position: relative;
            overflow: hidden;
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border: 1px dashed #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .file-upload-text {
            color: var(--gray);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- App-like header -->
    <header class="app-header">
        <button class="back-btn" onclick="window.location.href='manage_product.php'">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="app-title">
            <i class="fas fa-plus-circle"></i> Add Product
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

        <div class="form-card animate">
            <div class="card-header">
                <i class="fas fa-box"></i> Product Information
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="productForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php
                                $categories = mysqli_query($conn, "SELECT * FROM categories");
                                while ($cat = mysqli_fetch_assoc($categories)) {
                                    echo "<option value='{$cat['id']}'>{$cat['category_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" id="brand" class="form-select" required>
                                <option value="">Select Brand</option>
                            </select>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" class="form-control" required>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Features</label>
                            <textarea name="features" class="form-control" required></textarea>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Specifications</label>
                            <div class="spec-group">
                                <span class="spec-label">Screen</span>
                                <textarea class="form-control" name="specifications[screen]" required></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Processor</span>
                                <textarea class="form-control" name="specifications[processor]" required></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">RAM</span>
                                <textarea class="form-control" name="specifications[ram]" required></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Storage</span>
                                <textarea class="form-control" name="specifications[storage]" required></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Battery</span>
                                <textarea class="form-control" name="specifications[battery]" required></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Camera</span>
                                <textarea class="form-control" name="specifications[camera]" required></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Details</label>
                            <textarea name="details" class="form-control" required></textarea>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Product Image</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <span class="file-upload-text">Choose an image file...</span>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </label>
                                <input type="file" name="image" class="file-upload-input" required>
                            </div>
                            <small class="text-muted">Allowed formats: jpg, jpeg, png, gif, webp</small>
                        </div>
                    </div>
                    
                    <button type="submit" name="add_product" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-save"></i> Add Product
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            // Brand dropdown population
            $('#category').change(function() {
                var category_id = $(this).val();
                $.ajax({
                    url: 'fetch_brands.php',
                    method: 'POST',
                    data: {category_id: category_id},
                    success: function(response) {
                        $('#brand').html(response);
                    }
                });
            });

            // File upload display
            $('.file-upload-input').change(function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.file-upload-label').find('.file-upload-text').text(fileName || 'Choose an image file...');
            });

            // Form validation
            $('#productForm').submit(function(event) {
                let isValid = true;

                // Validate Price
                let price = parseFloat($("input[name='price']").val());
                if (isNaN(price) || price < 100 || price > 200000) {
                    alert("Price must be between ₹100 and ₹200,000");
                    isValid = false;
                }

                // Validate Stock
                let stock = parseInt($("input[name='stock']").val());
                if (isNaN(stock) || stock < 1 || stock > 100) {
                    alert("Stock must be between 1 and 100");
                    isValid = false;
                }

                // Validate RAM
                let ram = $("textarea[name='specifications[ram]']").val().trim();
                let validRamValues = ["2GB", "4GB", "8GB", "12GB", "16GB", "32GB", "64GB"];
                if (!validRamValues.includes(ram)) {
                    alert("Please enter a valid RAM value (e.g., 8GB)");
                    isValid = false;
                }

                // Validate Storage
                let storage = $("textarea[name='specifications[storage]']").val().trim();
                let validStorageValues = ["64GB", "128GB", "256GB", "512GB", "1TB", "1TB SSD"];
                if (!validStorageValues.includes(storage)) {
                    alert("Please enter a valid storage value (e.g., 256GB)");
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
