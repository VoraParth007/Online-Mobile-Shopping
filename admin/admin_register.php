<?php
include('../includes/config.php');
session_name("admin_session");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admin_users (name, email, password) VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Registration Successful! Redirecting to Login Page...";
        header("Location: admin_login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Registration Failed: " . mysqli_error($conn);
        header("Location: admin_register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
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
        
        .register-card {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: var(--transition);
            animation: fadeInUp 0.5s ease;
        }
        
        .register-header {
            background: var(--success-color);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .register-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .register-header i {
            font-size: 2rem;
            margin-bottom: 15px;
            display: block;
        }
        
        .register-body {
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
        
        .btn-register {
            background: var(--success-color);
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }
        
        .btn-register:hover {
            background: #17a673;
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .login-link a:hover {
            color: #3a5ccc;
            text-decoration: underline;
        }
        
        .password-strength {
            height: 5px;
            background: #eee;
            border-radius: 3px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }
        
        @media (max-width: 576px) {
            .register-card {
                margin: 0 15px;
            }
            
            .register-header {
                padding: 20px;
            }
            
            .register-body {
                padding: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="register-card animate__animated animate__fadeIn">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h2>Admin Registration</h2>
        </div>
        
        <div class="register-body">
            <form method="POST">
                <div class="mb-4 position-relative">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control ps-5" id="name" name="name" 
                               placeholder="Enter your full name" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4 position-relative">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control ps-5" id="email" name="email" 
                               placeholder="Enter your email" required autocomplete="email">
                    </div>
                </div>
                
                <div class="mb-4 position-relative">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control ps-5" id="password" name="password" 
                               placeholder="Create a password" required autocomplete="new-password">
                    </div>
                    <div class="password-strength">
                        <div class="strength-meter" id="strengthMeter"></div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success btn-register w-100 py-2 mb-3">
                    <i class="fas fa-user-check me-2"></i> Register
                </button>
                
                <div class="login-link">
                    Already have an account? <a href="admin_login.php">Login Here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/js/all.min.js"></script>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            let strength = 0;
            
            // Check for length
            if (password.length > 7) strength += 1;
            if (password.length > 11) strength += 1;
            
            // Check for uppercase letters
            if (/[A-Z]/.test(password)) strength += 1;
            
            // Check for numbers
            if (/[0-9]/.test(password)) strength += 1;
            
            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Update strength meter
            const width = strength * 20;
            strengthMeter.style.width = width + '%';
            
            // Update color
            if (strength <= 1) {
                strengthMeter.style.backgroundColor = '#e74a3b'; // Red
            } else if (strength <= 3) {
                strengthMeter.style.backgroundColor = '#f6c23e'; // Yellow
            } else {
                strengthMeter.style.backgroundColor = '#1cc88a'; // Green
            }
        });

        // Show success message if exists
        <?php if(isset($_SESSION['success_message'])) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?php echo $_SESSION['success_message']; ?>",
                showConfirmButton: false,
                timer: 2000,
                background: 'var(--light-color)'
            }).then(() => {
                window.location.href = 'admin_login.php';
            });
            <?php unset($_SESSION['success_message']); ?>
        <?php } ?>

        // Show error message if exists
        <?php if(isset($_SESSION['error_message'])) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed!',
                text: "<?php echo $_SESSION['error_message']; ?>",
                confirmButtonColor: '#d33',
                background: 'var(--light-color)'
            });
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>
    </script>
</body>
</html>
