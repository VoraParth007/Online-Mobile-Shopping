<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Handle Brand Addition
if (isset($_POST['add_brand'])) {
    $name = trim($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $logo = '';

    if (empty($name) || empty($category_id)) {
        $_SESSION['error'] = "Brand name and category cannot be empty!";
        header("Location: manage_brands.php");
        exit();
    }

    // Check if brand name already exists in the same category
    $stmt = $conn->prepare("SELECT id FROM brands WHERE brand_name = ? AND category_id = ?");
    $stmt->bind_param("si", $name, $category_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "This brand already exists in the selected category!";
        header("Location: manage_brands.php");
        exit();
    }
    $stmt->close();

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "uploads/brands/";
        $logo = $target_dir . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], $logo);
    }

    // Insert new brand
    $stmt = $conn->prepare("INSERT INTO brands (brand_name, logo_image, category_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $logo, $category_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Brand added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add brand!";
    }

    $stmt->close();
    header("Location: manage_brands.php");
    exit();
}

// Handle Brand Editing
if (isset($_POST['edit_brand'])) {
    $brand_id = intval($_POST['brand_id']);
    $new_name = trim($_POST['edit_name']);
    $new_category_id = intval($_POST['edit_category_id']);
    $logo = $_POST['existing_logo'];

    // Check if brand name already exists in the same category (excluding current brand)
    $stmt = $conn->prepare("SELECT id FROM brands WHERE brand_name = ? AND category_id = ? AND id != ?");
    $stmt->bind_param("sii", $new_name, $new_category_id, $brand_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "This brand already exists in the selected category!";
        header("Location: manage_brands.php");
        exit();
    }
    $stmt->close();

    // Handle new logo upload
    if (!empty($_FILES['edit_logo']['name'])) {
        $target_dir = "uploads/brands/";
        $new_logo = $target_dir . basename($_FILES["edit_logo"]["name"]);
        move_uploaded_file($_FILES["edit_logo"]["tmp_name"], $new_logo);

        // Delete the old logo file if it exists
        if (!empty($logo) && file_exists($logo)) {
            unlink($logo);
        }

        $logo = $new_logo;
    }

    // Update brand details
    $stmt = $conn->prepare("UPDATE brands SET brand_name = ?, logo_image = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $new_name, $logo, $new_category_id, $brand_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Brand updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update brand!";
    }

    $stmt->close();
    header("Location: manage_brands.php");
    exit();
}

// Handle Brand Deletion
if (isset($_GET['delete_brand'])) {
    $brand_id = intval($_GET['delete_brand']);

    // Get the logo file path before deletion
    $stmt = $conn->prepare("SELECT logo_image FROM brands WHERE id = ?");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $stmt->bind_result($logoPath);
    $stmt->fetch();
    $stmt->close();

    // Delete brand from database
    $stmt = $conn->prepare("DELETE FROM brands WHERE id = ?");
    $stmt->bind_param("i", $brand_id);
    
    if ($stmt->execute()) {
        // Remove the logo file from the server if it exists
        if (!empty($logoPath) && file_exists($logoPath)) {
            unlink($logoPath);
        }
        $_SESSION['success'] = "Brand deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete brand!";
    }

    $stmt->close();
    header("Location: manage_brands.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brands</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Manage Brands</h2>
        <a href="./dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"> <?= $_SESSION['success'];
                                                unset($_SESSION['success']); ?> </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"> <?= $_SESSION['error'];
                                                unset($_SESSION['error']); ?> </div>
        <?php endif; ?>

        <div class="card p-3">
            <h3>Add Brand</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php
                        $categories = mysqli_query($conn, "SELECT * FROM categories");
                        while ($category = mysqli_fetch_assoc($categories)) {
                            echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>
                <button type="submit" name="add_brand" class="btn btn-primary">Add Brand</button>
            </form>
        </div>

        <h3 class="mt-4">All Brands</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $brands = mysqli_query($conn, "SELECT brands.*, categories.category_name FROM brands JOIN categories ON brands.category_id = categories.id");
                while ($brand = mysqli_fetch_assoc($brands)): ?>
                    <tr>
                        <td><?= $brand['id']; ?></td>
                        <td><?= $brand['category_name']; ?></td>
                        <td><img src="<?= $brand['logo_image']; ?>" style="max-width: 50px;"></td>
                        <td><?= $brand['brand_name']; ?></td>
                        <td>
                            <button class="btn btn-warning" onclick="editBrand(<?= $brand['id']; ?>, '<?= addslashes($brand['brand_name']); ?>', '<?= $brand['category_id']; ?>', '<?= $brand['logo_image']; ?>')">Edit</button>

                            <button class="btn btn-danger" onclick="deleteBrand(<?= $brand['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="brand_id" id="brand_id">
                        <input type="hidden" name="existing_logo" id="existing_logo">

                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="edit_category_id" id="edit_category_id" class="form-control">
                                <?php
                                $categories = mysqli_query($conn, "SELECT * FROM categories");
                                while ($category = mysqli_fetch_assoc($categories)) {
                                    echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand Logo</label>
                            <input type="file" name="edit_logo" class="form-control">
                        </div>
                        <button type="submit" name="edit_brand" class="btn btn-success">Update Brand</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript to Handle Delete Confirmation -->
    <script>
        function deleteBrand(id) {
            if (confirm("Are you sure you want to delete this brand?")) {
                window.location.href = "manage_brands.php?delete_brand=" + id;
            }
        }
    </script>
    <script>
        function editBrand(id, name, category, logo) {
            $('#brand_id').val(id);
            $('#edit_name').val(name);
            $('#edit_category_id').val(category);
            $('#existing_logo').val(logo);
            new bootstrap.Modal($('#editModal')).show();
        }
    </script>
</body>

</html>