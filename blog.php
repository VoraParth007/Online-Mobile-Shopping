<?php
session_name("user_session"); 
session_start();
include('includes/config.php');
include('includes/header.php');
// Fetch blogs from the database
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .blog-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .blog-card:hover {
            transform: translateY(-5px);
        }

        .blog-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .blog-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .blog-meta {
            font-size: 0.9rem;
            color: gray;
        }

        .read-more {
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .blog-title {
                font-size: 1.1rem;
            }

            .blog-meta {
                font-size: 0.85rem;
            }

            .read-more {
                font-size: 0.9rem;
            }

            .blog-img {
                height: 180px;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <h2 class="text-center text-primary mb-4"><i class="fa-solid fa-newspaper"></i> Latest Blogs</h2>

        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card blog-card">
                        <?php 
                        $imagePath = !empty($row['image']) ? "image/" . htmlspecialchars($row['image']) : "admin/uploads/default.jpg"; 
                        ?>
                        <img src="<?= $imagePath ?>" class="blog-img img-fluid" alt="Blog Image">
                        <div class="card-body">
                            <h5 class="blog-title"><?= htmlspecialchars($row['title']); ?></h5>
                            <p class="blog-meta"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($row['author']); ?> | <i class="fa-solid fa-calendar"></i> <?= date('d M Y', strtotime($row['created_at'])); ?></p>
                            <p class="card-text"><?= substr(htmlspecialchars($row['content']), 0, 100); ?>...</p>
                            <a href="blog_details.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm read-more"><i class="fa-solid fa-book-open"></i> Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include('includes/footer.php'); ?>
</html>
