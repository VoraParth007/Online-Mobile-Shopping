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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
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
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 16px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        
        .register-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            animation: fadeInUp 0.5s cubic-bezier(0.22, 0.61, 0.36, 1) both;
            margin: 16px;
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
            color: white;
            padding: 24px;
            text-align: center;
            position: relative;
        }
        
        .register-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: clamp(1.5rem, 4vw, 1.8rem);
        }
        
        .register-header i {
            font-size: clamp(2rem, 6vw, 2.5rem);
            margin-bottom: 12px;
            display: block;
        }
        
        .register-body {
            padding: clamp(20px, 5vw, 30px);
        }
        
        .form-control {
            height: 52px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding-left: 50px;
            transition: var(--transition);
            font-size: 1rem;
            -webkit-appearance: none;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
        }
        
        .input-group-text {
            position: absolute;
            z-index: 5;
            height: 52px;
            width: 50px;
            background: transparent;
            border: none;
            color: var(--primary-color);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
            border: none;
            height: 52px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: var(--transition);
            font-size: 1rem;
            width: 100%;
            padding: 0;
        }
        
        .btn-register:active {
            transform: scale(0.98);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.95rem;
        }
        
        .login-link a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
        }
        
        .password-strength {
            height: 6px;
            background: #f0f0f0;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            transition: var(--transition);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
            display: block;
        }
        
        /* Mobile-specific optimizations */
        @media (max-width: 576px) {
            body {
                padding: 12px;
                align-items: flex-start;
                padding-top: 20px;
            }
            
            .register-card {
                margin: 0;
                border-radius: 12px;
                animation: mobileFadeInUp 0.5s ease both;
            }
            
            .register-header {
                padding: 20px;
            }
            
            .register-body {
                padding: 20px;
            }
            
            .form-control {
                height: 48px;
                font-size: 0.95rem;
            }
            
            .input-group-text {
                height: 48px;
                font-size: 1rem;
            }
            
            .btn-register {
                height: 48px;
            }
            
            .login-link {
                margin-top: 16px;
            }
        }
        
        @media (max-width: 400px) {
            .register-header h2 {
                font-size: 1.4rem;
            }
            
            .register-header i {
                font-size: 1.8rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes mobileFadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h2>Admin Registration</h2>
        </div>
        
        <div class="register-body">
            <form method="POST" id="registrationForm">
                <div class="mb-3 position-relative">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Your full name" required autofocus>
                    </div>
                </div>
                
                <div class="mb-3 position-relative">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="your.email@example.com" required autocomplete="email">
                    </div>
                </div>
                
                <div class="mb-4 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Create password" required autocomplete="new-password">
                    </div>
                    <div class="password-strength">
                        <div class="strength-meter" id="strengthMeter"></div>
                    </div>
                    <small class="text-muted">8+ characters with uppercase, number & symbol</small>
                </div>
                
                <button type="submit" class="btn btn-register mb-3">
                    <i class="fas fa-user-check me-2"></i> Register
                </button>
                
                <div class="login-link">
                    Have an account? <a href="admin_login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Complexity checks
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Update meter
            const width = Math.min(strength * 20, 100);
            strengthMeter.style.width = width + '%';
            
            // Update color
            if (strength <= 1) {
                strengthMeter.style.backgroundColor = '#e74a3b';
            } else if (strength <= 3) {
                strengthMeter.style.backgroundColor = '#f6c23e';
            } else {
                strengthMeter.style.backgroundColor = '#1cc88a';
            }
        });

        // Form submission handling
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
            btn.disabled = true;
        });

        // Mobile viewport height adjustment
        function adjustViewport() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        window.addEventListener('resize', adjustViewport);
        adjustViewport();

        // Success message
        <?php if(isset($_SESSION['success_message'])) { ?>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "<?php echo $_SESSION['success_message']; ?>",
                    showConfirmButton: false,
                    timer: 2000,
                    background: 'white'
                }).then(() => {
                    window.location.href = 'admin_login.php';
                });
            }, 300);
            <?php unset($_SESSION['success_message']); ?>
        <?php } ?>

        // Error message
        <?php if(isset($_SESSION['error_message'])) { ?>
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: "<?php echo $_SESSION['error_message']; ?>",
                    confirmButtonColor: '#d33',
                    background: 'white'
                });
            }, 300);
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>
    </script>
</body>
</html>
