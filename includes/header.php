<?php
include('includes/config.php');

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']);
$userName = "";

if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $userQuery = mysqli_query($conn, "SELECT username FROM users WHERE id = '$userId'");
    $userData = mysqli_fetch_assoc($userQuery);
    $userName = $userData['username'];
}

// Fetch Categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

// Fetch Brands
$brands = mysqli_query($conn, "SELECT * FROM brands");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
        /* Heading animation */
        .shop-heading {
            font-size: 2.5rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            animation: fadeInScale 1.5s ease-in-out forwards;
        }

        /* Animation for fade-in and scale-up effect */
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* SHOP glowing effect */
        /* .shop-heading span {
            display: inline-block;
            animation: glow 1.5s infinite alternate;
        }

        @keyframes glow {
            0% {
                text-shadow: 0 0 5px #ffc107, 0 0 10px #ffcc00;
            }
            100% {
                text-shadow: 0 0 10px #ffea00, 0 0 20px #ffcc00;
            }
        } */

        /* Hover Effect */
        .shop-heading:hover {
            color: #007bff;
            text-shadow: 0 0 10px rgba(0, 123, 255, 0.8);
            transition: all 0.4s ease-in-out;
        }
    </style>    
<body>
    <header class="bg-light py-3">
        <div class="container d-flex justify-content-between align-items-center">
        <h2 class="shop-heading text-primary">MOBILE <span class="text-warning">SHOP</span></h2>
            <?php if ($loggedIn) { ?>
                <span class="fw-bold text-dark fs-3">Hi, <?php echo htmlspecialchars($userName); ?>!</span>
            <?php } ?>
        </div>
    </header>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link fw-bold fs-5" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold fs-5" href="#" data-bs-toggle="dropdown">Categories</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item fw-bold fs-5" href="categories.php">All</a></li>
                            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                                <li><a class="dropdown-item fw-bold fs-5" href="index.php?category=<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold fs-5" href="#" data-bs-toggle="dropdown">Brands</a>
                        <ul class="dropdown-menu">
                            <?php while ($brand = mysqli_fetch_assoc($brands)) { ?>
                                <li><a class="dropdown-item fw-bold fs-5" href="index.php?brand=<?php echo $brand['id']; ?>"><?php echo $brand['brand_name']; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-bold fs-5" href="blog.php">Blogs</a></li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold fs-5" href="inquiry.php"><i class="bi bi-chat-dots"></i> My Inquiry</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-bold fs-5" href="orders.php"><i class="bi bi-box-seam"></i> My Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold fs-5" href="cart.php"><i class="bi bi-cart fs-4"></i></a>
                    </li>
                    <?php if ($loggedIn) { ?>
                        <li class="nav-item"><a class="nav-link fw-bold fs-5" href="profile.php"><i class="bi bi-person fs-4"></i></a></li>

                        <!-- <li class="nav-item"><a class="nav-link text-danger fw-bold fs-5" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li> -->
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
