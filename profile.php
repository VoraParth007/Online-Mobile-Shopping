<?php
session_name("user_session"); 
session_start();
include('includes/config.php');
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details securely using prepared statement
$stmt = $conn->prepare("SELECT username, email, phone, address, city, pincode, state FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
        .account-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .account-container h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        .btn-container { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="account-container">
        <h2><i class="fa-solid fa-user"></i> My Account</h2>

        <form>
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Name:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-phone"></i> Phone:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" disabled>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-map-marker-alt"></i> Address:</label>
                <textarea class="form-control" disabled><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-city"></i> City:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>" disabled>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-map-pin"></i> Pincode:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['pincode']); ?>" disabled>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-location-dot"></i> State:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['state']); ?>" disabled>
            </div>

            <div class="btn-container">
                <a href="edit_profile.php" class="btn btn-primary">
                    <i class="fa-solid fa-edit"></i> Edit Profile
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmLogout()">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmLogout() {
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your account!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('includes/footer.php'); ?>