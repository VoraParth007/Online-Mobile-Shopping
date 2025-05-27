<?php
session_name("user_session"); 
session_start();
include('includes/config.php');
include('includes/header.php');

// Validate category ID
if (!isset($_GET['category']) || empty($_GET['category'])) {
    echo "<script>alert('Invalid Category!'); window.location.href='categories.php';</script>";
    exit();
}

$category_id = $_GET['category'];

// Fetch category name
$category_query = $conn->prepare("SELECT category_name FROM categories WHERE id = ?");
$category_query->bind_param("i", $category_id);
$category_query->execute();
$category_result = $category_query->get_result();
$category_data = $category_result->fetch_assoc();
$category_name = $category_data['category_name'] ?? 'Unknown';

// Pagination setup
$limit = 9;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Base query
$query = "SELECT * FROM products WHERE category_id = ?";
$params = [$category_id];
$types = "i";

// Filters
if (isset($_GET['min_price']) && $_GET['min_price'] !== '' && isset($_GET['max_price']) && $_GET['max_price'] !== '') {
    $query .= " AND price BETWEEN ? AND ?";
    $params[] = $_GET['min_price'];
    $params[] = $_GET['max_price'];
    $types .= "ii";
}

if (!empty($_GET['brand'])) {
    $query .= " AND brand_id = ?";
    $params[] = $_GET['brand'];
    $types .= "i";
}

if (!empty($_GET['rating'])) {
    $query .= " AND rating >= ?";
    $params[] = $_GET['rating'];
    $types .= "i";
}

// Get total products for pagination
$count_query = $conn->prepare($query);
$count_query->bind_param($types, ...$params);
$count_query->execute();
$count_result = $count_query->get_result();
$total_products = $count_result->num_rows;
$total_pages = ceil($total_products / $limit);

// Add LIMIT clause
$query .= " LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

// Final product fetch
$product_stmt = $conn->prepare($query);
$product_stmt->bind_param($types, ...$params);
$product_stmt->execute();
$result = $product_stmt->get_result();

// Get all brands
$brands = mysqli_query($conn, "SELECT * FROM brands");

// Preserve existing filters in query string
function buildQueryParams($override = []) {
    $params = $_GET;
    foreach ($override as $key => $value) {
        $params[$key] = $value;
    }
    return http_build_query($params);
}
?>

<div class="container mt-4">
    <h2 class="text-center fw-bold"><?= htmlspecialchars($category_name); ?> Products</h2>
    <div class="row">
        <!-- Filters -->
        <div class="col-md-3">
            <h4>Filter By</h4>
            <form method="GET" action="">
                <input type="hidden" name="category" value="<?= $category_id; ?>">

                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label">Price Range</label>
                    <input type="number" name="min_price" class="form-control mb-2" placeholder="Min Price" value="<?= $_GET['min_price'] ?? ''; ?>">
                    <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?= $_GET['max_price'] ?? ''; ?>">
                </div>

                <!-- Brand -->
                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <select name="brand" class="form-select">
                        <option value="">All Brands</option>
                        <?php while ($brand = mysqli_fetch_assoc($brands)) { ?>
                            <option value="<?= $brand['id']; ?>" <?= ($_GET['brand'] ?? '') == $brand['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($brand['brand_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Rating -->
                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select">
                        <option value="">All Ratings</option>
                        <option value="5" <?= ($_GET['rating'] ?? '') == '5' ? 'selected' : ''; ?>>5 Stars</option>
                        <option value="4" <?= ($_GET['rating'] ?? '') == '4' ? 'selected' : ''; ?>>4 Stars & Above</option>
                        <option value="3" <?= ($_GET['rating'] ?? '') == '3' ? 'selected' : ''; ?>>3 Stars & Above</option>
                        <option value="2" <?= ($_GET['rating'] ?? '') == '2' ? 'selected' : ''; ?>>2 Stars & Above</option>
                        <option value="1" <?= ($_GET['rating'] ?? '') == '1' ? 'selected' : ''; ?>>1 Star & Above</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                <a href="category_products.php?category=<?= $category_id; ?>" class="btn btn-outline-secondary w-100">Reset Filters</a>
            </form>
        </div>

        <!-- Product List -->
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($product = $result->fetch_assoc()) { ?>
                        <div class="col d-flex">
                            <div class="card shadow-sm h-100 w-100 rounded-4 overflow-hidden d-flex flex-column text-center">
                                <div class="position-relative d-flex align-items-center justify-content-center" style="height: 250px; background: #f8f9fa;">
                                    <img src="admin/uploads/<?= htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['product_name']); ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold text-dark"><?= htmlspecialchars($product['product_name']); ?></h5>
                                    <p class="text-danger fw-bold fs-5">₹<?= number_format($product['price'], 2); ?></p>
                                    <p class="card-text">Rating: <?= $product['rating']; ?> ★</p>
                                    <div class="mt-auto">
                                        <a href="product_details.php?product_id=<?= $product['id']; ?>" class="btn btn-primary w-100 fw-bold"><i class="fas fa-eye"></i> View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">No products found for selected filters.</div>
                    </div>
                <?php } ?>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1) { ?>
                        <li class="page-item"><a class="page-link" href="?<?= buildQueryParams(['page' => $page - 1]); ?>">Previous</a></li>
                    <?php } ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?= buildQueryParams(['page' => $i]); ?>"><?= $i; ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($page < $total_pages) { ?>
                        <li class="page-item"><a class="page-link" href="?<?= buildQueryParams(['page' => $page + 1]); ?>">Next</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
    .card img {
        transition: transform 0.3s ease-in-out;
    }

    .card img:hover {
        transform: scale(1.05);
    }

    @media (max-width: 767px) {
        .card-body h5 {
            font-size: 1rem;
        }

        .card-body p {
            font-size: 0.9rem;
        }
    }
</style>

<?php include('includes/footer.php'); ?>
