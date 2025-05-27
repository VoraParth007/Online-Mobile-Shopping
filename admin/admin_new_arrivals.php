<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

$message = ""; // Message variable for success notification

// Check if form is submitted
if (isset($_POST['update_arrivals'])) {
    // Set all products to 0 first (remove from new arrivals)
    mysqli_query($conn, "UPDATE products SET new_arrival = 0");

    // If at least one checkbox is checked, update the status
    if (isset($_POST['new_arrivals'])) {
        foreach ($_POST['new_arrivals'] as $product_id) {
            mysqli_query($conn, "UPDATE products SET new_arrival = 1 WHERE id = '$product_id'");
        }
        $_SESSION['message'] = "New Arrivals updated successfully!";
    } else {
        $_SESSION['message'] = "Products removed from New Arrivals!";
    }

    // Redirect to avoid form resubmission issue
    header("Location: admin_new_arrivals.php");
    exit();
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage New Arrivals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Manage New Arrivals</h2>

        <!-- Show Success Message After Update -->
        <?php if (isset($_SESSION['message'])) { ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $_SESSION['message']; ?>',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            </script>
            <?php unset($_SESSION['message']); // Clear message after displaying ?>
        <?php } ?>

        <form method="post">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>New Arrival</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                        <tr>
                            <td><img src="uploads/<?php echo $product['image']; ?>" width="50"></td>
                            <td><?php echo $product['product_name']; ?></td>
                            <td>$<?php echo $product['price']; ?></td>
                            <td>
                                <input type="checkbox" name="new_arrivals[]" value="<?php echo $product['id']; ?>"
                                    <?php echo ($product['new_arrival'] == 1) ? 'checked' : ''; ?>>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" name="update_arrivals" class="btn btn-primary">Update</button>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
