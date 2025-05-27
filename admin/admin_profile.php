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

// Handle profile picture update
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
//     $target_dir = "uploads/";
//     $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

//     if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
//         $update_pic_sql = "UPDATE admin_users SET profile_pic='$target_file' WHERE id='$admin_id'";
//         mysqli_query($conn, $update_pic_sql);
//         $_SESSION['success_message'] = "Profile Picture Updated!";
//         header("Location: admin_profile.php");
//         exit();
//     } else {
//         $_SESSION['error_message'] = "Failed to upload image!";
//     }
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Profile</h2>

        <?php if (isset($_SESSION['success_message'])) { ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "<?php echo $_SESSION['success_message']; ?>",
                    showConfirmButton: false,
                    timer: 2000
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
                    confirmButtonColor: '#d33'
                });
            </script>
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>

        <div class="card p-4">
            <h4>Profile Details</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $admin['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $admin['email']; ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <div class="card p-4 mt-4">
            <h4>Change Password</h4>
            <form method="POST">
                <div class="mb-3 position-relative">
                    <label>Current Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                        <span class="input-group-text" onclick="togglePassword('currentPassword', 'currentIcon')">
                            <i id="currentIcon" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3 position-relative">
                    <label>New Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="new_password" id="newPassword" required>
                        <span class="input-group-text" onclick="togglePassword('newPassword', 'newIcon')">
                            <i id="newIcon" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var icon = document.getElementById(iconId);
            
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
