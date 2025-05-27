<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

if (isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $rating = $_POST['rating'];
    $features = trim($_POST['features']);
    $specifications = isset($_POST['specifications']) ? json_encode($_POST['specifications']) : "{}";
    $details = trim($_POST['details']);
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    
    // Stock Management - Availability
    $availability = ($stock > 0) ? "In Stock" : "Out of Stock";

    // Check if product already exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Product already exists!";
        header("Location: add_product.php");
        exit();
    }

    // Image Handling
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($image_ext, $allowed_ext)) {
        $_SESSION['error'] = "Invalid image format! Allowed: jpg, jpeg, png, gif, webp";
        header("Location: add_product.php");
        exit();
    }

    $new_image_name = time() . "_" . uniqid() . "." . $image_ext;
    $image_path = "./uploads/" . $new_image_name; // Save path

    if (move_uploaded_file($image_tmp, $image_path)) {
        // Insert Product
        $stmt = $conn->prepare("INSERT INTO products (product_name, price, stock, availability, rating, features, specifications, details, image, category_id, brand_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdissssssii", $product_name, $price, $stock, $availability, $rating, $features, $specifications, $details, $new_image_name, $category_id, $brand_id);


        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add product!";
        }
    } else {
        $_SESSION['error'] = "Failed to upload image!";
    }

    header("Location: manage_product.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h2>Add Product</h2>
        <a href="manage_product.php" class="btn btn-secondary mb-3">Back to Product List</a>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" id="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat = mysqli_fetch_assoc($categories)) {
                        echo "<option value='{$cat['id']}'>{$cat['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Brand</label>
                <select name="brand_id" id="brand" class="form-control" required>
                    <option value="">Select Brand</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Features</label>
                <textarea name="features" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Specifications</label>
                <textarea class="form-control" name="specifications[screen]" placeholder="Screen"></textarea>
                <textarea class="form-control" name="specifications[processor]" placeholder="Processor"></textarea>
                <textarea class="form-control" name="specifications[ram]" placeholder="RAM"></textarea>
                <textarea class="form-control" name="specifications[storage]" placeholder="Storage"></textarea>
                <textarea class="form-control" name="specifications[battery]" placeholder="Battery"></textarea>
                <textarea class="form-control" name="specifications[camera]" placeholder="Camera"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Details</label>
                <textarea name="details" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
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
        });

        $(document).ready(function () {
    $("form").submit(function (event) {
        let isValid = true;

        // Validate Price
        let price = parseFloat($("input[name='price']").val());
        if (isNaN(price) || price < 100 || price >200000) {
            alert("Price must be a valid number between 100 and 200000!");
            $("input[name='price']").addClass("is-invalid");
            isValid = false;
        } else {
            $("input[name='price']").removeClass("is-invalid");
        }

        // Validate Stock
        let stock = parseInt($("input[name='stock']").val());
        if (isNaN(stock) || stock < 1 || stock > 100) {
            alert("Stock must be a valid integer between 1 and 100!");
            $("input[name='stock']").addClass("is-invalid");
            isValid = false;
        } else {
            $("input[name='stock']").removeClass("is-invalid");
        }
     // Validate RAM Selection
     let ram = $("textarea[name='specifications[ram]']").val().trim();
        let validRamValues = ["2GB", "4GB", "8GB", "12GB", "16GB", "32GB", "64GB"];
        if (!validRamValues.includes(ram)) {
            alert("Invalid RAM selection! Please choose: 2GB, 4GB, 8GB, 12GB, 16GB, 32GB, or 64GB.");
            $("textarea[name='specifications[ram]']").addClass("is-invalid");
            isValid = false;
        } else {
            $("textarea[name='specifications[ram]']").removeClass("is-invalid");
        }

        // Validate Storage Selection
        let storage = $("textarea[name='specifications[storage]']").val().trim();
        let validStorageValues = ["64GB", "128GB", "256GB", "512GB", "1TB", "1TB SSD"];
        if (!validStorageValues.includes(storage)) {
            alert("Invalid Storage selection! Please choose: 64GB, 128GB, 256GB, 512GB, 1TB, or 1TB SSD.");
            $("textarea[name='specifications[storage]']").addClass("is-invalid");
            isValid = false;
        } else {
            $("textarea[name='specifications[storage]']").removeClass("is-invalid");
        }

        // Prevent form submission if any validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});

    </script>
</body>
</html>