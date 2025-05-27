<?php
session_name("user_session"); 
session_start();
ob_start(); // Start output buffering
include('includes/config.php');
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT username, email, phone, address, city, pincode, state FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    echo "User not found!";
    exit();
}

$updateSuccess = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);

    // Check if any changes were made
    if ($username !== $user['username'] || $email !== $user['email'] || $phone !== $user['phone'] ||
        $address !== $user['address'] || $city !== $user['city'] || $pincode !== $user['pincode'] || $state !== $user['state']) {
        
        $update_sql = "UPDATE users SET username=?, email=?, phone=?, address=?, city=?, pincode=?, state=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "sssssssi", $username, $email, $phone, $address, $city, $pincode, $state, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $updateSuccess = true;
            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: edit_profile.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
}

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
        .profile-container {
            max-width: 500px; margin: 50px auto; background: white; padding: 20px;
            border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        .btn-container { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-container">
        <h2><i class="fa-solid fa-user-edit"></i> Edit Profile</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $_SESSION['success_message']; ?>',
                    confirmButtonColor: '#28a745'
                });
            </script>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Name:</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fa-solid fa-phone"></i> Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fa-solid fa-map-marker-alt"></i> Address:</label>
                <textarea name="address" class="form-control" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label><i class="fa-solid fa-city"></i> City:</label>
                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fa-solid fa-location-dot"></i> Pincode:</label>
                <input type="text" name="pincode" class="form-control" value="<?php echo htmlspecialchars($user['pincode']); ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fa-solid fa-flag"></i> State:</label>
                <input type="text" name="state" class="form-control" value="<?php echo htmlspecialchars($user['state']); ?>" required>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Update Profile
                </button>
                <a href="profile.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to My Account
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('includes/footer.php'); ?>