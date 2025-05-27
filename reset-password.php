<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Update the password and remove reset token
    $sql = "UPDATE users SET password='$new_password', reset_token=NULL WHERE reset_token='$token'";
    if (mysqli_query($conn, $sql)) {
        // Redirect after successful reset
        header("Location: login.php?reset_success=1");
        exit(); // Ensure script stops execution after redirect
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Password reset failed. Please try again.',
                    confirmButtonColor: '#d33'
                });
              </script>";
    }
}

// Validate token before showing form
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");

    if (mysqli_num_rows($result) == 0) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Link!',
                    text: 'This reset link is invalid or expired.',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.location.href = 'login.php';
                });
              </script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="text-center">Reset Password</h3>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="mb-3">
                    <label>New Password:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
