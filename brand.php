<?php
session_name("user_session"); 
session_start();
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .brand-card {
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .brand-card:hover {
            transform: scale(1.05);
        }

        .brand-img {
            height: 100px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Our Brands</h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            $brands = mysqli_query($conn, "SELECT * FROM brands");
            while ($brand = mysqli_fetch_assoc($brands)) {
                echo '
                <div class="col d-flex justify-content-center">
                    <div class="card p-3 shadow brand-card">
                        <img src="admin/uploads/brands/' . $brand['logo_image'] . '" alt="' . $brand['brand_name'] . '" class="img-fluid brand-img">
                        <h5 class="mt-2 text-center">' . $brand['brand_name'] . '</h5>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
