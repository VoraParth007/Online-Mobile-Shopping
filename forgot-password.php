<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50));

        // Ensure the 'reset_token' column exists before updating
        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'reset_token'");
        if (mysqli_num_rows($checkColumn) == 0) {
            mysqli_query($conn, "ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL");
        }

        // Update the reset token in the database
        mysqli_query($conn, "UPDATE users SET reset_token='$token' WHERE email='$email'");

        // Redirect to reset-password.php with the token
        header("Location: reset-password.php?token=$token");
        exit(); // Stop script execution after redirection
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Email Not Found!',
                    text: 'No account found with this email.',
                    confirmButtonColor: '#d33'
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="text-center">Forgot Password</h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
