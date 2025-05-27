<?php
session_name("user_session"); 
session_start();
include ('includes/config.php'); 
include ('includes/header.php'); 

// Fetch all categories
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .category-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s;
            overflow: hidden;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .category-icon {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .category-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .category-btn {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 16px;
        }

        /* Mobile-friendly styling */
        @media (max-width: 576px) {
            .category-card {
                margin-bottom: 1.5rem;
            }

            .category-icon {
                font-size: 50px;
            }

            .category-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4"><i class="fa-solid fa-list"></i> All Categories</h2>
        <div class="row">
            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card category-card text-center p-3">
                        <i class="fa-solid fa-box category-icon"></i>
                        <div class="card-body">
                            <h5 class="category-title"><?= htmlspecialchars($category['category_name']); ?></h5>
                            <a href="category_products.php?category=<?= $category['id']; ?>" class="btn btn-primary category-btn"><i class="fa-solid fa-eye"></i> View Products</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include ('includes/footer.php'); ?>
