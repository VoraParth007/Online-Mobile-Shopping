<?php
session_name("user_session"); 
session_start();
include('./includes/config.php');

if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {

    echo "Invalid product ID!";
    exit();
}

$product_id = $_GET['product_id'];


$stmt = $conn->prepare("SELECT p.*, c.category_name, b.brand_name FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        JOIN brands b ON p.brand_id = b.id 
                        WHERE p.id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Product not found!";
    exit();
}

$product = $result->fetch_assoc();
$availability = ($product['stock'] > 0) ? "In Stock" : "Out of Stock";
$specifications = json_decode($product['specifications'], true);

// Fetch product reviews
$reviews_query = "SELECT r.*, u.username FROM reviews r INNER JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC";
$stmt_reviews = $conn->prepare($reviews_query);
$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();

// Check if user can review
$user_can_review = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check_purchase_query = "SELECT COUNT(*) AS count FROM orders o 
                             JOIN order_details od ON o.id = od.order_id 
                             WHERE o.user_id = ? AND od.product_id = ? AND o.status = 'Delivered'";
    $stmt_purchase = $conn->prepare($check_purchase_query);
    $stmt_purchase->bind_param("ii", $user_id, $product_id);
    $stmt_purchase->execute();
    $purchase_result = $stmt_purchase->get_result()->fetch_assoc();
    if ($purchase_result['count'] > 0) {
        $user_can_review = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .product-container {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .product-image {
            flex: 1;
            max-width: 500px;
            text-align: center;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            /* box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); */
        }

        .product-details {
            flex: 2;
            max-width: 600px;
        }

        .accordion-button {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="container mt-5">
        <div class="product-container">
            <div class="product-image">
                <img src="admin/uploads/<?php echo $product['image']; ?>" alt="Product Image" class="img-fluid">
            </div>
            <div class="product-details">
                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand_name']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo number_format($product['price'], 2); ?></p>
                <p><strong>Availability:</strong> <span class="badge bg-<?php echo ($availability == 'In Stock') ? 'success' : 'danger'; ?>"> <?php echo $availability; ?> </span></p>
                <p><strong>Rating:</strong> <?php echo $product['rating']; ?> / 5</p>

                <?php if ($availability == "In Stock") { ?>
                    <a href="add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                <?php } else { ?>
                    <button class="btn btn-secondary" disabled>Out of Stock</button>
                <?php } ?>

                <a href="index.php" class="btn btn-outline-secondary">Back to Shop</a>

                <!-- Interactive Sections -->
                <div class="accordion mt-4" id="productAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Features
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($product['features'])); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Specifications
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <?php foreach ($specifications as $key => $value) { ?>
                                        <li><strong><?php echo ucfirst($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Details
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($product['details'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Reviews & Rating -->
    <div class="container mt-5 text-center">
        <h3 class="mb-4 fw-bold text-primary">⭐ Product Reviews</h3>
        <div class="reviews row justify-content-center">
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                    <div class="card shadow-lg p-3 border-primary">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark">
                                <?php echo htmlspecialchars($review['username']); ?>
                            </h5>
                            <p class="text-black fs-5">⭐ <?php echo $review['rating']; ?> / 5</p>
                            <p class="text-muted">"<?php echo nl2br(htmlspecialchars($review['review'])); ?>"</p>
                            <small class="text-muted">Reviewed on <?php echo $review['created_at']; ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- inquiry form field users table data fetch  -->
    <?php
$userId = $_SESSION['user_id'] ?? null;
$userName = $userEmail = $userPhone = '';

if ($userId) {
    include('includes/config.php');

    $stmt = $conn->prepare("SELECT username, email, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($userName, $userEmail, $userPhone);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}
?>

<!-- Inquiry Form -->
<div class="container mt-5 p-5 border rounded bg-light shadow-lg w-75 text-center">
    <h4 class="mb-4 fw-bold text-primary">📩 Have a Question? Ask Here!</h4>
    <form id="inquiryForm" class="row g-3 justify-content-center" method="POST" action="submit_inquiry.php">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

        <div class="col-md-6">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name" class="form-control shadow-sm" 
                   value="<?= htmlspecialchars($userName) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control shadow-sm" 
                   value="<?= htmlspecialchars($userEmail) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Phone (Optional)</label>
            <input type="text" name="phone" class="form-control shadow-sm"
                   value="<?= htmlspecialchars($userPhone) ?>">
        </div>
        <div class="col-md-12">
            <label class="form-label fw-bold">Your Inquiry</label>
            <textarea name="message" class="form-control shadow-sm" rows="4" required></textarea>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary w-50">Submit Inquiry</button>
        </div>
    </form>
</div>

    <script>
        document.getElementById("inquiryForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("submit_inquiry.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        Swal.fire("Success", "Inquiry submitted successfully!", "success").then(() => {
                            document.getElementById("inquiryForm").reset();
                        });
                    } else {
                        Swal.fire("Error", "Failed to submit inquiry!", "error");
                    }
                });
        });
    </script>


    <?php include('includes/footer.php'); ?>
</body>

</html>