<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Check if admin is logged in (Replace this with your admin authentication logic)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle approval or rejection
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'approve') {
        mysqli_query($conn, "UPDATE delivery_boys SET status='Approved' WHERE id=$id");
    } elseif ($_GET['action'] == 'reject') {
        mysqli_query($conn, "UPDATE delivery_boys SET status='Rejected' WHERE id=$id");
    }
    header("Location: manage_delivery_boys.php");
    exit;
}

// Fetch delivery boys
$query = mysqli_query($conn, "SELECT * FROM delivery_boys");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Delivery Boys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-action {
            transition: 0.3s ease-in-out;
        }

        .btn-action:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary"><i class="bi bi-people"></i> Manage Delivery Boys</h3>
            <div>
                <a href="delivery_earnings.php" class="btn btn-success me-2">
                    <i class="bi bi-currency-rupee"></i> My Earnings
                </a>
                <a href="dashboard.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status'] == 'Approved' ? 'success' : ($row['status'] == 'Rejected' ? 'danger' : 'warning') ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Pending') { ?>
                                    <a href="?action=approve&id=<?= $row['id']; ?>" class="btn btn-success btn-sm btn-action"><i class="bi bi-check-circle"></i> Approve</a>
                                    <a href="?action=reject&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm btn-action"><i class="bi bi-x-circle"></i> Reject</a>
                                <?php } else { ?>
                                    <span class="text-muted">No Action</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>