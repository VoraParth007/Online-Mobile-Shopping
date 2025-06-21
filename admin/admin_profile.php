<?php
include('../includes/config.php');
session_name("admin_session");
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$sql = "SELECT * FROM admin_users WHERE id = '$admin_id'";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Update query
    $update_sql = "UPDATE admin_users SET name='$name', email='$email' WHERE id='$admin_id'";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success_message'] = "Profile Updated Successfully!";
        header("Location: admin_profile.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating profile: " . mysqli_error($conn);
    }
}

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    if (password_verify($current_password, $admin['password'])) {
        $update_password_sql = "UPDATE admin_users SET password='$new_password' WHERE id='$admin_id'";
        if (mysqli_query($conn, $update_password_sql)) {
            $_SESSION['success_message'] = "Password Updated Successfully!";
            header("Location: admin_profile.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating password!";
        }
    } else {
        $_SESSION['error_message'] = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --info-color: #560bad;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
        }
        
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 15px;
        }
        
        .profile-card {
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-warning:hover {
            background-color: #e07d0e;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--dark-color);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #1a1b2e;
            transform: translateY(-2px);
        }
        
        .password-toggle {
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .profile-container {
                margin: 1rem auto;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="text-center mb-4">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['name']); ?>&background=random" alt="Admin Avatar" class="profile-avatar">
            <h2 class="mb-1"><?php echo htmlspecialchars($admin['name']); ?></h2>
            <p class="text-muted">Administrator</p>
        </div>

        <?php if (isset($_SESSION['success_message'])) { ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "<?php echo $_SESSION['success_message']; ?>",
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#f5f7fa',
                    backdrop: `
                        rgba(0,0,0,0.1)
                        url("/images/checkmark.gif")
                        center top
                        no-repeat
                    `
                });
            </script>
            <?php unset($_SESSION['success_message']); ?>
        <?php } ?>

        <?php if (isset($_SESSION['error_message'])) { ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "<?php echo $_SESSION['error_message']; ?>",
                    confirmButtonColor: var(--danger-color),
                    background: '#f5f7fa'
                });
            </script>
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>

        <div class="profile-card mb-4">
            <div class="card-header">
                <h4><i class="fas fa-user-cog me-2"></i>Profile Details</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="profile-card mb-4">
            <div class="card-header">
                <h4><i class="fas fa-lock me-2"></i>Change Password</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-4 position-relative">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword('currentPassword', 'currentIcon')">
                                <i id="currentIcon" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-4 position-relative">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword('newPassword', 'newIcon')">
                                <i id="newIcon" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="change_password" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
