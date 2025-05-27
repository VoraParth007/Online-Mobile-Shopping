<?php
session_name("user_session");
session_start();
include('includes/config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}





include('includes/header.php');

// Fetch categories and brands for filters
$categories = mysqli_query($conn, "SELECT * FROM categories");
$brands = mysqli_query($conn, "SELECT * FROM brands");

// Filter Products based on Category, Brand, or Search
$filter = "";
if (isset($_GET['category']) && $_GET['category'] != '') {
    $category_id = $_GET['category'];
    $filter .= " WHERE category_id = '$category_id'";
}
if (isset($_GET['brand']) && $_GET['brand'] != '') {
    $brand_id = $_GET['brand'];
    $filter .= ($filter ? " AND" : " WHERE") . " brand_id = '$brand_id'";
}
if (isset($_GET['search']) && $_GET['search'] != '') {
    $search = $_GET['search'];
    $filter .= ($filter ? " AND" : " WHERE") . " product_name LIKE '%$search%'";
}

$products = mysqli_query($conn, "SELECT * FROM products $filter");

//blogs fetch 
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
// Fetch new arrival products
$newArrivalsQuery = mysqli_query($conn, "SELECT * FROM products WHERE new_arrival = 1 ORDER BY id DESC LIMIT 8");

?>

<!-- Banner Section -->
<div class="container-fluid p-0 position-relative">
    <img src="image/banner-poster.jpg" class="img-fluid w-100" alt="Mobile Store Banner" style="object-fit: cover; height: 100vh;">
    <div class="banner-text position-absolute text-white text-center top-50 start-50 translate-middle">
        <h1 class="display-6 fw-bold">WELCOME TO THE BEST MOBILE SHOP</h1>
        <p class="fs-4">Find the latest smartphones at unbeatable prices!</p>
        <a href="#products-section" class="btn btn-primary btn-lg mt-3">Shop Now</a>
    </div>
</div>

<style>
    .banner-text {
        z-index: 10;
        width: 90%;
    }

    @media (max-width: 768px) {
        .banner-text h1 {
            font-size: 1.5rem;
        }

        .banner-text p {
            font-size: 1rem;
        }
    }
</style>

<!-- Shop by Brands -->
<div class="container mt-4">
    <h2 class="mb-4">Shop by Brand</h2>
    <div class="row">
        <?php
        // Fetch distinct brands, ensuring each brand appears only once
        $brands = mysqli_query($conn, "SELECT DISTINCT brand_name, logo_image FROM brands");

        while ($brand = mysqli_fetch_assoc($brands)) {
            $logo = !empty($brand['logo_image']) ? $brand['logo_image'] : 'default.png';

            // Generate URL to display all products for this brand
            echo "
            <div class='col-md-2 text-center mb-3'>
                <a href='brand_products.php?brand_name=" . urlencode($brand['brand_name']) . "'>
                    <img src='admin/$logo' class='brand-logo img-fluid' alt='{$brand['brand_name']}'>
                </a>
                <p>{$brand['brand_name']}</p>
            </div>";
        }
        ?>
    </div>
</div>


<style>
    .brand-logo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
        padding: 5px;
    }

    /* Media Queries for Responsive Design */
    @media (max-width: 1024px) {
        .brand-logo {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 768px) {
        .brand-logo {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 480px) {
        .brand-logo {
            width: 80px;
            height: 80px;
            padding: 3px;
        }
    }
</style>



<!-- Bootstrap Icons CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<!-- Offer Section -->
<!-- <div class="container my-5">
    <div class="row">
       
        <div class="col-md-6">
            <div class="offer-box text-white p-5 rounded" style="background-color: #d32f2f;">
                <h5>Holiday Deals</h5>
                <h2 class="fw-bold">Up to <br> <span style="font-size: 50px;">30% off</span></h2>
                <p>Selected Smartphone Brands</p>
                <a href="#" class="btn btn-light btn-lg rounded-pill">Shop</a>
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="offer-box text-white p-5 rounded" style="background-color: #6a1b9a;">
                <h5>Just In</h5>
                <h2 class="fw-bold">Take Your <br> Sound Anywhere</h2>
                <p>Top Headphone Brands</p>
                <a href="#" class="btn btn-light btn-lg rounded-pill">Shop</a>
               
        </div>
    </div>
</div> -->

<!-- CSS -->
<!-- <style>
    .offer-box {
        position: relative;
        overflow: hidden;
        height: 450px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .offer-img {
        position: absolute;
        right: 10px;
        bottom: -20px;
        max-height: 180px;
    }
</style> -->
<div id="products-section" class="container mt-5">
    <h2 class="text-center fw-bold mb-4">Explore Our Products</h2>

    <!-- Product Grid -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php while ($product = mysqli_fetch_assoc($products)) { ?>
            <div class="col d-flex">
                <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden d-flex flex-column align-items-center text-center" style="width: 100%; height: 100%; min-height: 400px;">

                    <!-- Product Image -->
                    <div class="position-relative d-flex align-items-center justify-content-center w-100" style="height: 250px; background: #f8f9fa;">
                        <img src="admin/uploads/<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                        <span class="badge bg-success position-absolute top-0 end-0 m-2 p-2"><?php echo ($product['stock'] > 0) ? 'In Stock' : 'Out of Stock'; ?></span>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column w-100">
                        <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text text-danger fw-bold fs-5">₹<?php echo number_format($product['price'], 2); ?></p>

                        <div class="rating mb-2">
                            <?php
                            $rating = $product['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo ($i <= $rating) ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
                            }
                            ?>
                        </div>

                        <div class="mt-auto">
                            <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary w-100 fw-bold"><i class="fas fa-eye"></i> View Details</a>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- FontAwesome Icons for Stars -->
<script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>




<!-- Discount Section -->
<!-- <div class="container my-5">
    <div class="row row-cols-1 row-cols-md-2 g-4"> -->
<!-- Mobile Discount -->
<!-- <div class="col">
            <div class="offer-box text-white p-5 rounded position-relative" style="background-color: #00796b; height: 350px;">
                <h5>Exclusive Mobile Offer</h5>
                <h2 class="fw-bold">Save Up to <br> <span style="font-size: 60px;">40% Off</span></h2>
                <p>Latest Smartphones & Accessories</p>
                <a href="#" class="btn btn-light btn-lg rounded-pill">Shop Now</a>
                <img src="image/desscount.png" class="offer-img" alt="Mobile Offer">
            </div>
        </div> -->

<!-- Laptop Discount -->
<!-- <div class="col">
            <div class="offer-box text-white p-5 rounded position-relative" style="background-color: #283593; height: 350px;">
                <h5>Special Laptop Sale</h5>
                <h2 class="fw-bold">Flat <br> <span style="font-size: 60px;">35% Off</span></h2>
                <p>Top Brands & Best Performance Laptops</p>
                <a href="#" class="btn btn-light btn-lg rounded-pill">Shop Now</a>
                <img src="image/desscount3.png" class="offer-img" alt="Laptop Offer">
            </div>
        </div>
    </div>
</div> -->

<!-- CSS -->
<!-- <style>
    .offer-box {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: start;
        border-radius: 15px;
        height: 100%;
        /* Ensure height consistency */
    }

    .offer-img {
        position: absolute;
        right: 20px;
        bottom: 10px;
        max-height: 250px;
        opacity: 0.9;
    }
</style> -->


<!-- bolgs -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Our Recent Blog</h2>
        <a href="blog.php" class="text-primary fw-bold text-decoration-none fs-4">
            Read All Articles →
        </a>

    </div>

    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <?php
                    $imagePath = !empty($row['image']) ? "image/" . htmlspecialchars($row['image']) : "admin/uploads/default.jpg";
                    ?>
                    <img src="<?= $imagePath ?>" class="card-img-top" alt="Blog Image" style="height: 250px; object-fit: cover; border-radius: 10px 10px 0 0;">

                    <div class="card-body">
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($row['created_at'])); ?>
                            | <i class="bi bi-tags"></i> <?= htmlspecialchars($row['category']); ?>
                        </p>
                        <h5 class="fw-bold"><?= htmlspecialchars($row['title']); ?></h5>
                        <p class="text-muted"><?= substr(htmlspecialchars($row['content']), 0, 100); ?>...</p>
                        <a href="blog_details.php?id=<?= $row['id']; ?>" class="text-primary fw-bold">
                            Read More →
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Subscribe  -->
<!-- <style>
    body {
        background-color: #f8f9fa;
    }

    .discount-section {
        background-color: #e3f2fd;
        border-radius: 20px;
        padding: 50px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    }

    .discount-text {
        max-width: 50%;
    }

    .discount-text h2 {
        font-weight: bold;
        color: #333;
    }

    .discount-text span {
        color: #ffb300;
        font-size: 40px;
    }

    .discount-form {
        max-width: 50%;
    }

    .discount-form input {
        border-radius: 8px;
        padding: 12px;
    }

    .btn-dark {
        width: 100%;
        border-radius: 8px;
        padding: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .btn-dark:hover {
        background-color: #222;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }
</style> -->

<!-- 
<div class="container my-5">
    <div class="discount-section">

        <div class="discount-text">
            <h2>Get <span>25% Discount</span> on Your First Purchase</h2>
            <p>Sign up now and enjoy exclusive discounts on the latest mobile phones and accessories.</p>
        </div>


        <div class="discount-form">
            <form>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input">
                    <label class="form-check-label"> Subscribe to the newsletter</label>
                </div>
                <button type="submit" class="btn btn-dark">Submit</button>
            </form>
        </div>
    </div>
</div> -->

<!-- New Arrivals -->
<section class="container mt-5">
    <h2 class="text-center fw-bold mb-4">New Arrivals</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php while ($product = mysqli_fetch_assoc($newArrivalsQuery)) { ?>
            <div class="col d-flex">
                <div class="card shadow-sm h-100 w-100 rounded-4 overflow-hidden d-flex flex-column text-center">

                    <!-- Product Image -->
                    <div class="position-relative d-flex align-items-center justify-content-center" style="height: 250px; background: #f8f9fa;">
                        <img src="admin/uploads/<?php echo $product['image']; ?>" class="img-fluid" alt="Product Image" style="max-height: 100%; width: auto; object-fit: contain; transition: 0.3s ease-in-out;">
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">New</span>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold text-dark"><?php echo $product['product_name']; ?></h5>
                        <p class="text-danger fw-bold fs-5">₹<?php echo number_format($product['price'], 2); ?></p>

                        <div class="mt-auto">
                            <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary w-100 fw-bold">
                                <i class="fa-solid fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
</section>
<style>
    .badge {
        font-size: 14px;
        padding: 5px 10px;
    }

    .card img {
        transition: 0.3s ease-in-out;
    }

    .card img:hover {
        transform: scale(1.05);
    }

    .card {
        min-height: 400px;
    }

    /* Media Queries for Responsive Design */
    @media (max-width: 1024px) {
        .card {
            min-height: 350px;
        }

        .badge {
            font-size: 13px;
            padding: 4px 8px;
        }
    }

    @media (max-width: 768px) {
        .card {
            min-height: 300px;
        }

        .badge {
            font-size: 12px;
            padding: 3px 6px;
        }
    }

    @media (max-width: 480px) {
        .card {
            min-height: 250px;
        }

        .badge {
            font-size: 11px;
            padding: 2px 5px;
        }
    }
</style>


<!-- Mobile Store Services -->
<div class="container mt-5">
    <h2 class="text-center">Our Services</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <i class="bi bi-truck display-4 mb-2"></i>
            <h4>Fast Delivery</h4>
            <p>Get your orders delivered within 24 hours.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="bi bi-arrow-return-left display-4 mb-2"></i>
            <h4>Easy Returns</h4>
            <p>Hassle-free returns within 7 days.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="bi bi-headset display-4 mb-2"></i>
            <h4>24/7 Support</h4>
            <p>Dedicated customer support for all your queries.</p>
        </div>
    </div>
</div>


<script>
    function applyFilter() {
        let category = document.getElementById('categoryFilter').value;
        let brand = document.getElementById('brandFilter').value;
        let search = document.getElementById('searchBox').value;
        window.location.href = `products.php?category=${category}&brand=${brand}&search=${search}`;
    }

    function addToCart(productId) {
        fetch(`cart.php?action=add&id=${productId}`)
            .then(response => response.text())
            .then(data => alert('Product added to cart successfully!'));
    }
</script>


<?php include('includes/footer.php'); ?>