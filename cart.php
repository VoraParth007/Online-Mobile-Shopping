<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to view your cart.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_items = mysqli_query($conn, "SELECT c.id AS cart_id, c.*, p.product_name, p.image, p.stock FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Shopping Cart</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "<?php echo $_SESSION['success']; ?>",
                    confirmButtonColor: '#3085d6'
                });
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "<?php echo $_SESSION['error']; ?>",
                    confirmButtonColor: '#d33'
                });
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($cart_items)) { ?>
                        <tr>
                            <td><img src="admin/uploads/<?php echo $row['image']; ?>" width="50" class="img-fluid"></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td>₹<?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <input type="number" value="<?php echo $row['quantity']; ?>" min="1" max="<?php echo $row['stock']; ?>" class="form-control quantity-input" data-cart-id="<?php echo $row['cart_id']; ?>">
                            </td>

                            <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo $row['stock'] > 0 ? 'Available' : 'Out of Stock'; ?></td>
                            <td>
                                <button onclick="confirmRemove(<?php echo $row['cart_id']; ?>)" class="btn btn-danger btn-sm">Remove</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between">
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
        </div><br><br>
    </div>

    <script>
        function confirmRemove(cartId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this item!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "remove_from_cart.php?id=" + cartId;
                }
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".quantity-input").on("change", function() {
            var cartId = $(this).data("cart-id");
            var quantity = $(this).val();

            $.ajax({
                url: "update_cart.php",
                type: "POST",
                data: { cart_id: cartId, quantity: quantity },
                success: function(response) {
                    location.reload(); // Reload to update total price
                }
            });
        });
    });
    </script>

</body>
<?php include('includes/footer.php'); ?>

</html>
