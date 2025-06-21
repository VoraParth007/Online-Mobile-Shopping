<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID!";
    header("Location: manage_product.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Product not found!";
    header("Location: manage_product.php");
    exit();
}

$product = $result->fetch_assoc();
// Decode specifications JSON
$specifications = json_decode($product['specifications'], true);

if (isset($_POST['update_product'])) {
    $product_name = trim($_POST['product_name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $rating = $_POST['rating'];
    $features = trim($_POST['features']);
    $specifications = isset($_POST['specifications']) ? json_encode($_POST['specifications']) : "{}";
    $details = trim($_POST['details']);
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $availability = ($stock > 0) ? "In Stock" : "Out of Stock";

    // Image Handling
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($image_ext, $allowed_ext)) {
            $_SESSION['error'] = "Invalid image format! Allowed: jpg, jpeg, png, gif, webp";
            header("Location: edit_product.php?id=$product_id");
            exit();
        }

        $new_image_name = time() . "_" . uniqid() . "." . $image_ext;
        $image_path = "./uploads/" . $new_image_name;
        move_uploaded_file($image_tmp, $image_path);
    } else {
        $new_image_name = $product['image'];
    }

    // Update Product
    $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, stock=?, availability=?, rating=?, features=?, specifications=?, details=?, image=?, category_id=?, brand_id=? WHERE id=?");
    $stmt->bind_param("sdissssssiii", $product_name, $price, $stock, $availability, $rating, $features, $specifications, $details, $new_image_name, $category_id, $brand_id, $product_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update product!";
    }

    header("Location: manage_product.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Product</title>
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
            transform: translateY(-3px);
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
            margin-bottom: 15px;
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
        
        /* Current image preview */
        .current-image {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        
        .current-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        .current-image-text {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        /* Interactive elements */
        .interactive-card {
            transition: all 0.3s ease;
        }
        
        .interactive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Mobile optimizations */
        @media (max-width: 767px) {
            .content {
                padding: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .form-control, .form-select, .btn {
                padding: 10px 15px;
            }
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
            <i class="fas fa-edit"></i> Edit Product
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

        <div class="form-card animate interactive-card">
            <div class="card-header">
                <i class="fas fa-box"></i> Edit Product Details
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="productForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="category" class="form-select" required>
                                <?php
                                $categories = mysqli_query($conn, "SELECT * FROM categories");
                                while ($cat = mysqli_fetch_assoc($categories)) {
                                    $selected = ($cat['id'] == $product['category_id']) ? "selected" : "";
                                    echo "<option value='{$cat['id']}' $selected>{$cat['category_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" id="brand" class="form-select" required>
                                <?php
                                $brands = mysqli_query($conn, "SELECT * FROM brands WHERE category_id = " . $product['category_id']);
                                while ($brand = mysqli_fetch_assoc($brands)) {
                                    $selected = ($brand['id'] == $product['brand_id']) ? "selected" : "";
                                    echo "<option value='{$brand['id']}' $selected>{$brand['brand_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" class="form-control" value="<?= $product['rating']; ?>" required>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Features</label>
                            <textarea name="features" class="form-control" required><?= htmlspecialchars($product['features']); ?></textarea>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Specifications</label>
                            <div class="spec-group">
                                <span class="spec-label">Screen</span>
                                <textarea class="form-control" name="specifications[screen]" required><?= htmlspecialchars($specifications['screen'] ?? ''); ?></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Processor</span>
                                <textarea class="form-control" name="specifications[processor]" required><?= htmlspecialchars($specifications['processor'] ?? ''); ?></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">RAM</span>
                                <textarea class="form-control" name="specifications[ram]" required><?= htmlspecialchars($specifications['ram'] ?? ''); ?></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Storage</span>
                                <textarea class="form-control" name="specifications[storage]" required><?= htmlspecialchars($specifications['storage'] ?? ''); ?></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Battery</span>
                                <textarea class="form-control" name="specifications[battery]" required><?= htmlspecialchars($specifications['battery'] ?? ''); ?></textarea>
                            </div>
                            <div class="spec-group">
                                <span class="spec-label">Camera</span>
                                <textarea class="form-control" name="specifications[camera]" required><?= htmlspecialchars($specifications['camera'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Details</label>
                            <textarea name="details" class="form-control" required><?= htmlspecialchars($product['details']); ?></textarea>
                        </div>
                        
                        <div class="form-group span-2">
                            <label class="form-label">Product Image</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <span class="file-upload-text">Choose new image (optional)</span>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </label>
                                <input type="file" name="image" class="file-upload-input">
                            </div>
                            <div class="current-image">
                                <img src="./uploads/<?= $product['image']; ?>" alt="Current product image">
                                <span class="current-image-text">Current image</span>
                            </div>
                            <small class="text-muted">Allowed formats: jpg, jpeg, png, gif, webp</small>
                        </div>
                    </div>
                    
                    <button type="submit" name="update_product" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Update brand dropdown when category changes
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
                $(this).siblings('.file-upload-label').find('.file-upload-text').text(fileName || 'Choose new image (optional)');
            });
        });
    </script>
</body>
</html>
