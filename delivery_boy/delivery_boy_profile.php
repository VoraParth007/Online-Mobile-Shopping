<?php
session_name("delivery_session");
session_start();
include('../includes/config.php');

if (!isset($_SESSION['delivery_boy_id'])) {
    header("Location: login.php");
    exit;
}

$delivery_boy_id = $_SESSION['delivery_boy_id'];

// Fetch delivery boy details
$query = mysqli_query($conn, "SELECT * FROM delivery_boys WHERE id = '$delivery_boy_id'");
$delivery_boy = mysqli_fetch_assoc($query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Update details
    mysqli_query($conn, "UPDATE delivery_boys SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$delivery_boy_id'");
    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: delivery_boy_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Boy Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .profile-card {
            max-width: 500px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background: white;
            text-align: center;
        }
        .profile-header {
            background-color:rgb(7, 143, 255);
            color: #fff;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .profile-body {
            padding: 20px;
        }
        .edit-icon {
            cursor: pointer;
            color: #ff2407;
            margin-left: 10px;
        }
        .dashboard-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="card profile-card">
        <div class="profile-header">
            <h4><i class="bi bi-person-circle"></i> My Profile</h4>
        </div>
        <div class="profile-body">
            <p><strong><i class="bi bi-person-fill"></i> Name:</strong> <?= $delivery_boy['name']; ?> <i class="bi bi-pencil-square edit-icon" onclick="editProfile('<?= $delivery_boy['name']; ?>', '<?= $delivery_boy['email']; ?>', '<?= $delivery_boy['phone']; ?>')"></i></p>
            <p><strong><i class="bi bi-envelope-fill"></i> Email:</strong> <?= $delivery_boy['email']; ?></p>
            <p><strong><i class="bi bi-telephone-fill"></i> Phone:</strong> <?= $delivery_boy['phone']; ?></p>
            <a href="delivery_dashboard.php" class="dashboard-btn">Back to Dashboard</a>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Notification -->
    <?php if (isset($_SESSION['success'])): ?>
        <script>
            $(document).ready(function () {
                let successToast = `<div class="toast show position-fixed top-0 end-0 mt-3 me-3 bg-success text-white" role="alert" style="z-index: 1050;">
                    <div class="toast-body">
                        <?= $_SESSION['success']; ?>
                        <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="toast"></button>
                    </div>
                </div>`;
                $('body').append(successToast);
                setTimeout(() => $('.toast').remove(), 3000);
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <script>
        function editProfile(name, email, phone) {
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_phone').val(phone);
            new bootstrap.Modal($('#editModal')).show();
        }
    </script>
</body>
</html>