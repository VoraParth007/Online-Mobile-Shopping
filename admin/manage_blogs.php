<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch all blogs
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");

// Delete Blog
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM blogs WHERE id=$id");
    $_SESSION['success_message'] = "Blog deleted successfully!";
    header("Location: manage_blogs.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
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

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .blog-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn {
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-primary"><i class="fa-solid fa-blog"></i> Manage Blogs</h2>
                <div>
                    <a href="./dashboard.php" class="btn btn-secondary me-2"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
                    <a href="add_blog.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add New Blog</a>
                </div>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle"></i> <?= $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['category']); ?></td>
                                <td><?= htmlspecialchars($row['author']); ?></td>
                                <td>
                                    <?php 
                                    $imagePath = (!empty($row['image']) && file_exists("../uploads/" . $row['image'])) 
                                        ? "../uploads/" . htmlspecialchars($row['image']) 
                                        : "../uploads/default.jpg"; 
                                    ?>
                                    <img src="<?= $imagePath ?>" class="blog-img" alt="Blog Image">
                                </td>
                                <td>
                                    <a href="edit_blog.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </a>
                                    <a href="manage_blogs.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog?')">
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
