<?php
include('includes/config.php');
session_name("user_session"); 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $customer = mysqli_fetch_assoc($result);

    if ($customer && password_verify($password, $customer['password'])) {
        $_SESSION['user_id'] = $customer['id'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            background: white;
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
            position: relative;
        }
        .btn-login {
            width: 100%;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h2><i class="fa-solid fa-user"></i> Customer Login</h2>

        <form method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock"></i> Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                    <span class="input-group-text toggle-password">
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <i class="fa-solid fa-sign-in-alt"></i> Login
            </button>

            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register Here</a></p>
                <p><a href="forgot-password.php">Forgot Password?</a></p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        let passwordInput = document.getElementById("password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        }
    });

    <?php if(isset($_SESSION['error_message'])) { ?>
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
