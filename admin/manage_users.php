<?php
include('../includes/config.php');
session_name("admin_session");
session_start();

// Delete user if requested
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $_SESSION['success_message'] = "User deleted successfully!";
    header("Location: admin_manage_users.php");
    exit();
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --danger-color: #ef233c;
            --success-color: #4cc9f0;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 500;
        }
        
        .table th {
            padding: 1rem;
            vertical-align: middle;
            white-space: nowrap;
        }
        
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        
        .btn-action {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        
        .btn-back {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-back:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-id {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
        }
        
        .mobile-card {
            display: none;
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .mobile-card .user-info {
            margin-bottom: 1rem;
        }
        
        .mobile-card .user-info div {
            margin-bottom: 0.5rem;
        }
        
        .mobile-card .label {
            font-weight: 600;
            color: var(--dark-color);
            display: inline-block;
            min-width: 80px;
        }
        
        @media (max-width: 992px) {
            .table-responsive {
                display: none;
            }
            
            .mobile-card {
                display: block;
            }
            
            .admin-header {
                border-radius: 0;
                padding: 1rem 0;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="admin-header py-3 mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-bold mb-0"><i class="bi bi-people-fill me-2"></i> Manage Users</h3>
            <a href="./dashboard.php" class="btn-back">
                <i class="bi bi-arrow-left-circle-fill me-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Success message -->
    <?php if(isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?= $_SESSION['success_message']; ?>",
                confirmButtonColor: '#4361ee',
                backdrop: 'rgba(67, 97, 238, 0.1)'
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Desktop Table -->
    <div class="card p-4 d-none d-lg-block animate__animated animate__fadeIn">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><i class="bi bi-person-circle me-1"></i> Name</th>
                        <th><i class="bi bi-envelope-fill me-1"></i> Email</th>
                        <th><i class="bi bi-telephone-fill me-1"></i> Phone</th>
                        <th><i class="bi bi-geo-alt-fill me-1"></i> Address</th>
                        <th>City</th>
                        <th>Pincode</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="badge-id">#<?= $row['id']; ?></span></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td><?= htmlspecialchars($row['city']); ?></td>
                            <td><?= htmlspecialchars($row['pincode']); ?></td>
                            <td><?= htmlspecialchars($row['state']); ?></td>
                      
                        </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">No users found in the database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php mysqli_data_seek($result, 0); // Reset result pointer ?>
        <div class="d-lg-none animate__animated animate__fadeIn">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="mobile-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge-id">#<?= $row['id']; ?></span>
          
                    </div>
                    
                    <div class="user-info">
                        <div><span class="label"><i class="bi bi-person-fill me-1"></i>Name:</span> <?= htmlspecialchars($row['username']); ?></div>
                        <div><span class="label"><i class="bi bi-envelope-fill me-1"></i>Email:</span> <?= htmlspecialchars($row['email']); ?></div>
                        <div><span class="label"><i class="bi bi-telephone-fill me-1"></i>Phone:</span> <?= htmlspecialchars($row['phone']); ?></div>
                        <div><span class="label"><i class="bi bi-geo-alt-fill me-1"></i>Address:</span> <?= htmlspecialchars($row['address']); ?></div>
                        <div><span class="label">City:</span> <?= htmlspecialchars($row['city']); ?></div>
                        <div><span class="label">Pincode:</span> <?= htmlspecialchars($row['pincode']); ?></div>
                        <div><span class="label">State:</span> <?= htmlspecialchars($row['state']); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="d-lg-none text-center text-muted py-5">
            <i class="bi bi-people display-4 opacity-50"></i>
            <p class="mt-3">No users found in the database.</p>
        </div>
    <?php endif; ?>
</div>


<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
