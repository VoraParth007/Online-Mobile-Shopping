<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch subscribers
$query = "SELECT * FROM subscribers ORDER BY subscribed_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscribers List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <!-- Header with Icon and Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-envelope-paper-fill me-2"></i> Subscribers List</h2>
        <a href="dashboard.php" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left-circle-fill me-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Card Container -->
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col"><i class="bi bi-envelope-at-fill me-1"></i>Email Address</th>
                        <th scope="col"><i class="bi bi-clock-history me-1"></i>Subscribed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): 
                        while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= date('d-m-Y H:i:s', strtotime($row['subscribed_at'])) ?></td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-danger">No Subscribers Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
