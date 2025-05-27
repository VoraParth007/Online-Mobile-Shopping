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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Product</h2>
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
                <select name="category_id" class="form-control" required>
                    <?php
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat = mysqli_fetch_assoc($categories)) {
                        $selected = ($cat['id'] == $product['category_id']) ? "selected" : "";
                        echo "<option value='{$cat['id']}' $selected>{$cat['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Brand</label>
                <select name="brand_id" class="form-control" required>
                    <?php
                    $brands = mysqli_query($conn, "SELECT * FROM brands WHERE category_id = " . $product['category_id']);
                    while ($brand = mysqli_fetch_assoc($brands)) {
                        $selected = ($brand['id'] == $product['brand_id']) ? "selected" : "";
                        echo "<option value='{$brand['id']}' $selected>{$brand['brand_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" value="<?= $product['product_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5" class="form-control" value="<?= $product['rating']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Features</label>
                <textarea name="features" class="form-control" required><?= $product['features']; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Specifications</label>
                <textarea class="form-control" name="specifications[screen]" placeholder="Screen"><?= $specifications['screen'] ?? ''; ?></textarea>
                <textarea class="form-control" name="specifications[processor]" placeholder="Processor"><?= $specifications['processor'] ?? ''; ?></textarea>
                <textarea class="form-control" name="specifications[ram]" placeholder="RAM"><?= $specifications['ram'] ?? ''; ?></textarea>
                <textarea class="form-control" name="specifications[storage]" placeholder="Storage"><?= $specifications['storage'] ?? ''; ?></textarea>
                <textarea class="form-control" name="specifications[battery]" placeholder="Battery"><?= $specifications['battery'] ?? ''; ?></textarea>
                <textarea class="form-control" name="specifications[camera]" placeholder="Camera"><?= $specifications['camera'] ?? ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Details</label>
                <textarea name="details" class="form-control" required><?= $product['details']; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control">
                <p>Current Image: <img src="./uploads/<?= $product['image']; ?>" width="100"></p>
            </div>
            <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>