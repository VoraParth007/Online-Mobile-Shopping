<?php
include('../includes/config.php');
session_name("admin_session");
session_start();

// Delete user if requested
// if (isset($_GET['delete'])) {
//     $user_id = intval($_GET['delete']);
//     $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
//     mysqli_stmt_bind_param($stmt, "i", $user_id);
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_close($stmt);
//     $_SESSION['success_message'] = "User deleted successfully!";
//     header("Location: admin_manage_users.php");
//     exit();
// }

// Fetch all users
$result = mysqli_query($conn, "SELECT id, username, email, phone, address, city, pincode, state FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .table thead {
            background-color: #212529;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-people-fill me-2"></i> Manage Users</h3>
        <a href="./dashboard.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle-fill me-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Success message -->
    <?php if(isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?= $_SESSION['success_message']; ?>",
                confirmButtonColor: '#3085d6'
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- User Table Card -->
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th><i class="bi bi-person-circle me-1"></i> Name</th>
                        <th><i class="bi bi-envelope-fill me-1"></i> Email</th>
                        <th><i class="bi bi-telephone-fill me-1"></i> Phone</th>
                        <th><i class="bi bi-geo-alt-fill me-1"></i> Address</th>
                        <th>City</th>
                        <th>Pincode</th>
                        <th>State</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td><?= htmlspecialchars($row['city']); ?></td>
                            <td><?= htmlspecialchars($row['pincode']); ?></td>
                            <td><?= htmlspecialchars($row['state']); ?></td>
                            <!-- <td>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id']; ?>">
                                    <i class="bi bi-trash-fill"></i> Delete
                                </button>
                            </td> -->
                        </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="9" class="text-center text-danger">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation with SweetAlert -->
<script>
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            const userId = this.getAttribute("data-id");
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "admin_manage_users.php?delete=" + userId;
                }
            });
        });
    });
</script>

</body>
</html>
