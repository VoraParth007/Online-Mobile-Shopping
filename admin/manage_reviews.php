<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch reviews from the database
$query = "SELECT r.id, u.username, p.product_name, r.rating, r.review, r.created_at 
          FROM reviews r
          INNER JOIN users u ON r.user_id = u.id
          INNER JOIN products p ON r.product_id = p.id
          ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Ratings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        .btn-back {
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        table {
            margin-top: 10px;
            border-radius: 5px;
            overflow: hidden;
        }
        thead {
            background-color: #343a40;
            color: white;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .rating-stars {
            color: gold;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <h2><i class="fas fa-star"></i> User Reviews & Ratings</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success text-center">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['product_name']); ?></td>
                            <td class="rating-stars"><?= str_repeat('⭐', $row['rating']) . " (" . $row['rating'] . ")"; ?></td>
                            <td><?= htmlspecialchars($row['review']); ?></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                            <td>
                                <form action="delete_review.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    <input type="hidden" name="review_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
