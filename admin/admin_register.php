<?php
include ('../includes/config.php');
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #343a40;
        }
        .register-container {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-register {
            width: 100%;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2><i class="fa-solid fa-user-plus"></i> Admin Registration</h2>

        <form method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Name:</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock"></i> Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
            </div>

            <button type="submit" class="btn btn-success btn-register">
                <i class="fa-solid fa-user-check"></i> Register
            </button>

            <div class="login-link">
                <a href="admin_login.php">Already have an account? Login Here</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Show success message if exists
    <?php if(isset($_SESSION['success_message'])) { ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "<?php echo $_SESSION['success_message']; ?>",
            showConfirmButton: false,
            timer: 2000
        });
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>

    // Show error message if exists
    <?php if(isset($_SESSION['error_message'])) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed!',
            text: "<?php echo $_SESSION['error_message']; ?>",
            confirmButtonColor: '#d33'
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>
</script>

</body>
</html>
