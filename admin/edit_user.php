<?php
include('../includes/config.php');
session_name("admin_session");
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
    $user = mysqli_fetch_assoc($query);
}

// Update User
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "UPDATE users SET username='$username', email='$email', phone='$phone', address='$address', role='$role' WHERE id=$user_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "User updated successfully!";
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Full Name:</label>
            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Address:</label>
            <textarea class="form-control" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
        </div>
        <!-- <div class="mb-3">
            <label>Role:</label>
            <select class="form-control" name="role">
                <option value="User" <?php if ($user['role'] == 'User') echo 'selected'; ?>>User</option>
                <option value="Admin" <?php if ($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div> -->
        <button type="submit" class="btn btn-success">Update User</button>
        <a href="manage_users.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
