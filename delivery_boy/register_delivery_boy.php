<?php
session_name("delivery_session");
session_start();
include ('../includes/config.php');

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check_email = mysqli_query($conn, "SELECT * FROM delivery_boys WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "⚠️ Email already registered!";
    } else {
        $query = "INSERT INTO delivery_boys (name, email, phone, password, status) 
                  VALUES ('$name', '$email', '$phone', '$password', 'Pending')";
        if (mysqli_query($conn, $query)) {
            $success = "✅ Registration successful! Wait for admin approval. Redirecting to login...";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login_delivery_boy.php';
                }, 3000);
            </script>";
        } else {
            $error = "❌ Registration failed! Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Boy Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f1f4f8;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="text-center mb-4 text-primary">🚴 Delivery Boy Registration</h3>
                
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php } ?>

                <?php if (isset($success)) { ?>
                    <div class="alert alert-success"><?= $success; ?></div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>

                <div class="text-center mt-3">
                    <small>Already registered? <a href="login_delivery_boy.php">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
