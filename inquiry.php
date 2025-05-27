<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM inquiries WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$inquiries = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Inquiries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container mt-5">
    <h2>My Inquiries</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Message</th>
                <th>Admin Reply</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $inquiries->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><?php echo $row['response'] ?: 'No reply yet'; ?></td>
                    <td><span class="badge bg-<?php echo $row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Replied' ? 'info' : 'success'); ?>">
                        <?php echo $row['status']; ?>
                    </span></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
