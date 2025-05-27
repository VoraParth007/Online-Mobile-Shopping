<?php
session_name("admin_session");
session_start();
include('../includes/config.php'); // Database Connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $author = $_POST['author'];

    // Handle Image Upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Insert Data into Database
    $sql = "INSERT INTO blogs (title, content, image, category, author) 
            VALUES ('$title', '$content', '$image', '$category', '$author')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to manage_blogs.php with success message
        $_SESSION['success_message'] = "Blog Added Successfully!";
        header("Location: manage_blogs.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 700px;
            margin: 60px auto;
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
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="form-title"><i class="fa-solid fa-pen-to-square"></i> Add New Blog</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" placeholder="Enter blog title" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content:</label>
                <textarea name="content" class="form-control" rows="5" placeholder="Enter blog content" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category:</label>
                <input type="text" name="category" class="form-control" placeholder="e.g., Tech, Health" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Author:</label>
                <input type="text" name="author" class="form-control" placeholder="Author name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Image:</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-upload"></i> Add Blog
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
