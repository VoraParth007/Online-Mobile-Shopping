<?php
session_name("user_session"); 
session_start();
include('includes/config.php');
include('includes/header.php');

if (!isset($_GET['brand_name'])) {
    header("Location: index.php");
    exit();
}

$brand_name = urldecode($_GET['brand_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - <?= htmlspecialchars($brand_name); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script> <!-- Include FontAwesome -->
    <style>
        /* Styling the Product Cards */
        .card img {
            transition: transform 0.3s ease-in-out;
        }

        .card img:hover {
            transform: scale(1.05);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .card-body h5 {
                font-size: 1rem;
            }

            .card-body p {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 576px) and (max-width: 768px) {
            .card-body h5 {
                font-size: 1.1rem;
            }

            .card-body p {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center fw-bold mb-4">Products by <?= htmlspecialchars($brand_name); ?></h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            // Fetch all products related to this brand
            $query = $conn->prepare("SELECT products.* FROM products 
                                     JOIN brands ON products.brand_id = brands.id
                                     WHERE brands.brand_name = ?");
            $query->bind_param("s", $brand_name);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) { ?>
                    <div class="col d-flex">
                        <div class="card shadow-sm border-0 rounded-4 w-100 d-flex flex-column align-items-center text-center" style="min-height: 400px;">
                             <!-- Product Image -->
                            <div class="position-relative d-flex align-items-center justify-content-center w-100" style="height: 250px; background: #f8f9fa;">
                                <img src="admin/uploads/<?= htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['product_name']); ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                            </div>

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column w-100">
                                <h5 class="fw-bold text-dark"><?= htmlspecialchars($product['product_name']); ?></h5>
                                <p class="text-danger fw-bold fs-5">₹<?= number_format($product['price'], 2); ?></p>
                                <div class="mt-auto">
                                    <a href="product_details.php?product_id=<?= $product['id']; ?>" class="btn btn-primary w-100"><i class="fas fa-eye"></i> View Product</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo "<p class='text-center fw-bold text-danger'>No products found for this brand.</p>";
            }

            $query->close();
            ?>
        </div>
    </div>
</body>
<?php include('includes/footer.php'); ?>
</html>
