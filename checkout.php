<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to checkout.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch User Details
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($user_query);

// Fetch Cart Items
$cart_items = mysqli_query($conn, "SELECT c.*, p.product_name, p.price, p.stock FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");

$total_amount = 0;
$cart_data = [];

while ($row = mysqli_fetch_assoc($cart_items)) {
    $total_amount += $row['price'] * $row['quantity'];
    $cart_data[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-4">
        <h2>Checkout</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form id="checkout-form" method="POST" action="process_checkout.php">
            <div class="row">
                <!-- Billing Details -->
                <div class="col-md-6">
                    <h4>Billing Details</h4>

                   
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" value="<?= htmlspecialchars($user_data['username']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user_data['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($user_data['phone']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required><?= htmlspecialchars($user_data['address']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control" value="<?= htmlspecialchars($user_data['city']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="pincode" class="form-label">PIN Code</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" value="<?= htmlspecialchars($user_data['pincode']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" name="state" id="state" class="form-control" value="<?= htmlspecialchars($user_data['state']); ?>" required>
                        </div>

                </div>
                <!-- Include SweetAlert2 CDN -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    function validateForm() {
                        let valid = true;

                        function showError(id, message) {
                            let errorElement = document.getElementById(id);
                            errorElement.textContent = message;
                            errorElement.classList.remove("d-none");
                        }

                        function hideError(id) {
                            document.getElementById(id).classList.add("d-none");
                        }

                        // Validate Full Name
                        let fullname = document.getElementById("fullname").value.trim();
                        if (fullname === "") {
                            showError("fullnameError", "Full name is required.");
                            valid = false;
                        } else {
                            hideError("fullnameError");
                        }

                        // Validate Email (Strict Pattern)
                        let email = document.getElementById("email").value.trim();
                        let emailRegex = /^[a-zA-Z0-9]+[a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        if (!emailRegex.test(email)) {
                            showError("emailError", "Enter a valid email (e.g., user@example.com).");
                            valid = false;
                        } else {
                            hideError("emailError");
                        }

                        // Validate Phone Number (Exactly 10 digits, numeric only)
                        let phone = document.getElementById("phone").value.trim();
                        let phoneRegex = /^[0-9]{10}$/;
                        if (!phoneRegex.test(phone)) {
                            showError("phoneError", "Enter a valid 10-digit phone number (numbers only).");
                            valid = false;
                        } else {
                            hideError("phoneError");
                        }

                        // Validate Address
                        let address = document.getElementById("address").value.trim();
                        if (address === "") {
                            showError("addressError", "Address is required.");
                            valid = false;
                        } else {
                            hideError("addressError");
                        }

                        // Validate City
                        let city = document.getElementById("city").value.trim();
                        if (city === "") {
                            showError("cityError", "City is required.");
                            valid = false;
                        } else {
                            hideError("cityError");
                        }

                        // Validate PIN Code (Only 6 digits allowed)
                        let pincode = document.getElementById("pincode").value.trim();
                        let pincodeRegex = /^[0-9]{6}$/;
                        if (!pincodeRegex.test(pincode)) {
                            showError("pincodeError", "Enter a valid 6-digit PIN code.");
                            valid = false;
                        } else {
                            hideError("pincodeError");
                        }

                        // Validate State
                        let state = document.getElementById("state").value.trim();
                        if (state === "") {
                            showError("stateError", "State is required.");
                            valid = false;
                        } else {
                            hideError("stateError");
                        }

                        // If any field is invalid, show a popup message and prevent payment
                        if (!valid) {
                            Swal.fire({
                                title: "Incomplete Billing Details!",
                                text: "Please fill all required billing details correctly before proceeding to payment.",
                                icon: "warning",
                                confirmButtonText: "OK"
                            });
                        }

                        return valid;
                    }
                </script>




                <!-- Order Summary -->
                <div class="col-md-6">
                    <div class="card shadow-lg p-3">
                        <h4 class="text-center mb-3">Order Summary</h4>

                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_data as $item): ?>
                                    <tr>
                                        <td><?= $item['product_name']; ?></td>
                                        <td><?= $item['quantity']; ?></td>
                                        <td>₹<?= number_format($item['price'], 2); ?></td>
                                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between mt-3">
                            <h4 class="fw-bold">Total:</h4>
                            <h4 class="text-success fw-bold">₹<span id="total-amount"><?= number_format($total_amount, 2); ?></span></h4>
                        </div>
                        <button type="button" id="rzp-button" class="btn btn-success mt-3 w-100">Proccess to payment </button>


                    </div>
                </div>

            </div>
        </form>
    </div>

    <?php include('includes/footer.php'); ?>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
       document.getElementById('rzp-button').onclick = function(e) {
            e.preventDefault();
            var options = {
                "key": "rzp_test_6Amf1uHqGmsrHd",
                "amount": <?= $total_amount * 100; ?>,
                "currency": "INR",
                "name": "Online Mobile Bazar",
                "description": "Order Payment",
                "handler": function(response) {
                    var form = document.getElementById("checkout-form");
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "payment_id";
                    input.value = response.razorpay_payment_id;
                    form.appendChild(input);
                    form.submit();
                },
                "prefill": {
                    "name": document.getElementById("fullname").value,
                    "email": document.getElementById("email").value,
                    "contact": document.getElementById("phone").value
                },
                "theme": { "color": "#3399cc" }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
        };
    </script>
</body>

</html>