<?php
session_name("admin_session");
session_start();
include('../includes/config.php');
$id = $_GET['id'];

// Fetch blog details
$result = mysqli_query($conn, "SELECT * FROM blogs WHERE id=$id");
$row = mysqli_fetch_assoc($result);

// Update blog
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $author = $_POST['author'];

    // Check if new image is uploaded
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $update_query = "UPDATE blogs SET title='$title', content='$content', category='$category', author='$author', image='$image' WHERE id=$id";
    } else {
        $update_query = "UPDATE blogs SET title='$title', content='$content', category='$category', author='$author' WHERE id=$id";
    }

    if (mysqli_query($conn, $update_query)) {
        header("Location: manage_blogs.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error updating blog: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 700px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .form-title {
            text-align: center;
            margin-bottom: 25px;
            color: #343a40;
        }

        .form-label {
            font-weight: 600;
        }

        img.preview {
            max-width: 150px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="form-title"><i class="fa-solid fa-pen-to-square"></i> Edit Blog</h2>
        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content:</label>
                <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($row['content']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category:</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($row['category']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Author:</label>
                <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($row['author']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image:</label><br>
                <?php if (!empty($row['image']) && file_exists("../uploads/" . $row['image'])): ?>
                    <img src="../uploads/<?= $row['image'] ?>" alt="Current Image" class="preview">
                <?php else: ?>
                    <p class="text-muted">No image uploaded.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload New Image:</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update Blog</button>
                <a href="manage_blogs.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
