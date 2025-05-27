<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Handle Category Addition
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE category_name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Category already exists!";
            $_SESSION['msg_type'] = "warning";
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $_SESSION['message'] = "Category added successfully!";
            $_SESSION['msg_type'] = "success";
        }
    } else {
        $_SESSION['message'] = "Category name cannot be empty!";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: manage_categories.php");
    exit();
}

// Handle Category Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Category deleted successfully!";
        $_SESSION['msg_type'] = "danger";
    } else {
        $_SESSION['message'] = "Failed to delete category!";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: manage_categories.php");
    exit();
}

// Handle Category Editing
if (isset($_POST['edit_category'])) {
    $id = intval($_POST['category_id']);
    $new_name = trim($_POST['edit_name']);
    if (!empty($new_name)) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE category_name = ? AND id != ?");
        $stmt->bind_param("si", $new_name, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Category name already exists!";
            $_SESSION['msg_type'] = "warning";
        } else {
            $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE id = ?");
            $stmt->bind_param("si", $new_name, $id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Category updated successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to update category!";
                $_SESSION['msg_type'] = "danger";
            }
        }
    } else {
        $_SESSION['message'] = "Category name cannot be empty!";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: manage_categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this category?")) {
                window.location.href = "manage_categories.php?delete=" + id;
            }
        }

        function editCategory(id, name) {
            document.getElementById('category_id').value = id;
            document.getElementById('edit_name').value = name;

            // Ensure the modal opens
            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        }
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Manage Categories</h2>
        <a href="./dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card p-4">
            <h3>Add Category</h3>
            <form method="post">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Category Name" required>
                </div>
                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
            </form>
        </div>

        <h3 class="mt-4">All Categories</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM categories");
                while ($cat = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                        <td>
                            <button class="btn btn-warning" onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars(addslashes($cat['category_name'])); ?>')">Edit</button>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $cat['id']; ?>)">Delete</button>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="category_id" id="category_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name</label>
                            <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                        </div>
                        <button type="submit" name="edit_category" class="btn btn-success">Update Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
