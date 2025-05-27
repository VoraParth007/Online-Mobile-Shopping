<?php
include('../includes/config.php');
session_name("admin_session");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM admin_users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['success_message'] = "Login Successful! Redirecting to Dashboard...";
        header("Location:dashboard.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid Credentials! Please check your email and password.";
        header("Location: admin_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #343a40;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-login {
            width: 100%;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="login-container">
            <h2><i class="fa-solid fa-user-shield"></i> Admin Login</h2>

            <form method="POST">
                <div class="form-group">
                    <label><i class="fa-solid fa-envelope"></i> Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-lock"></i> Password:</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fa-solid fa-sign-in-alt"></i> Login
                </button>

                <div class="register-link">
                    <a href="admin_register.php">New Admin? Register Here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show success message if exists
        <?php if (isset($_SESSION['success_message'])) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: 'Redirecting to Admin Dashboard...',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'dashboard.php';
            });
            <?php unset($_SESSION['success_message']); ?>
        <?php } ?>

        // Show error message if exists
        <?php if (isset($_SESSION['error_message'])) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed!',
                text: "<?php echo $_SESSION['error_message']; ?>",
                confirmButtonColor: '#d33'
            });
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>
    </script>

</body>

</html>