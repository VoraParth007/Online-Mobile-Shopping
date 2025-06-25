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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #4e73df;
            --dark-color: #2c3e50;
            --light-color: #f8f9fc;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: var(--transition);
            animation: fadeInUp 0.5s ease;
        }
        
        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .login-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .login-header i {
            font-size: 2rem;
            margin-bottom: 15px;
            display: block;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-control {
            height: 50px;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding-left: 45px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .input-group-text {
            position: absolute;
            z-index: 5;
            height: 50px;
            background: transparent;
            border: none;
            color: var(--primary-color);
        }
        
        .btn-login {
            background: var(--primary-color);
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }
        
        .btn-login:hover {
            background: #3a5ccc;
            transform: translateY(-2px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-link a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .register-link a:hover {
            color: #3a5ccc;
            text-decoration: underline;
        }
        
        @media (max-width: 576px) {
            .login-card {
                margin: 0 15px;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-body {
                padding: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card animate__animated animate__fadeIn">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h2>Admin Login</h2>
        </div>
        
        <div class="login-body">
            <form method="POST">
                <div class="mb-4 position-relative">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control ps-5" id="email" name="email" 
                               placeholder="Enter your email" required autofocus autocomplete="email">
                    </div>
                </div>
                
                <div class="mb-4 position-relative">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control ps-5" id="password" name="password" 
                               placeholder="Enter your password" required autocomplete="current-password">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login w-100 py-2 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
                
                <div class="register-link">
                    New Admin? <a href="admin_register.php">Register Here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/js/all.min.js"></script>

    <script>
        // Show success message if exists
        <?php if (isset($_SESSION['success_message'])) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: 'Redirecting to Admin Dashboard...',
                showConfirmButton: false,
                timer: 2000,
                background: 'var(--light-color)',
                backdrop: `
                    rgba(0,0,0,0.5)
                    url("/images/nyan-cat.gif")
                    left top
                    no-repeat
                `
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
                confirmButtonColor: '#d33',
                background: 'var(--light-color)'
            });
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>
    </script>
</body>
</html>
