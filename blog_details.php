<?php
session_name("user_session"); 
session_start();
include('includes/config.php');
include('includes/header.php');
$id = $_GET['id'];

// Fetch Blog Details
$result = mysqli_query($conn, "SELECT * FROM blogs WHERE id = $id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['title']; ?> - Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .blog-container { 
            max-width: 800px; 
            margin: auto; 
            padding: 20px; 
        }
        .blog-title { 
            font-size: 2rem; 
            font-weight: bold; 
        }
        .blog-img { 
            width: 100%; 
            border-radius: 10px; 
            margin-bottom: 20px; 
        }
        .blog-content { 
            font-size: 1.125rem; 
            line-height: 1.6; 
        }
        .blog-meta { 
            font-size: 0.875rem; 
            color: #6c757d; 
        }
        .back-btn { 
            margin-top: 20px; 
        }

        @media (max-width: 768px) {
            .blog-title {
                font-size: 1.5rem;
            }
            .blog-content {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container blog-container mt-4">
    <h2 class="blog-title text-center"><?php echo $row['title']; ?></h2>
    <img src="image/<?php echo $row['image']; ?>" class="blog-img img-fluid" alt="<?php echo $row['title']; ?>">
    
    <p class="blog-content"><?php echo nl2br($row['content']); ?></p>

    <p class="blog-meta">
        <strong>Category:</strong> <span class="badge bg-info"><?php echo $row['category']; ?></span> |
        <strong>Author:</strong> <?php echo $row['author']; ?> |
        <strong>Published on:</strong> <?php echo date("F j, Y", strtotime($row['created_at'])); ?>
    </p>

    <a href="blog.php" class="btn btn-primary back-btn">← Back to Blogs</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include('includes/footer.php'); ?>
</html>
