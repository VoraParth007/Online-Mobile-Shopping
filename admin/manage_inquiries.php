<?php 
session_name("admin_session");
session_start();
include '../includes/config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin - Manage Inquiries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-chat-dots-fill me-2"></i>Manage Inquiries</h2>
            <a href="dashboard.php" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th><i class="bi bi-person-fill"></i> User</th>
                                <th><i class="bi bi-box-seam"></i> Product</th>
                                <th><i class="bi bi-chat-left-text-fill"></i> Message</th>
                                <th><i class="bi bi-info-circle-fill"></i> Status</th>
                                <th><i class="bi bi-reply-fill"></i> Response</th>
                                <th><i class="bi bi-tools"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("
                                SELECT inquiries.*, products.product_name AS product_name 
                                FROM inquiries 
                                JOIN products ON inquiries.product_id = products.id 
                                ORDER BY inquiries.created_at DESC
                            ");

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                                        <td><?= htmlspecialchars($row['message']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $row['status'] === 'Closed' ? 'danger' : 'success' ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($row['response']) ?></td>
                                        <td class="text-center">
                                            <a href="reply_inquiry.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mb-1">
                                                <i class="bi bi-reply-fill"></i> Reply
                                            </a>
                                            <a href="close_inquiry.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle-fill"></i> Close
                                            </a>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="bi bi-exclamation-circle-fill"></i> No inquiries found.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
