<?php
session_name("admin_session");
session_start();
include ('../includes/config.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_delivery_boys.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM delivery_boys WHERE id = '$id'");

if (mysqli_num_rows($result) == 0) {
    header("Location: manage_delivery_boys.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update_delivery_boy'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $update_query = "UPDATE delivery_boys SET name = '$name', phone = '$phone' WHERE id = '$id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: manage_delivery_boys.php?success=1");
        exit();
    } else {
        $error = "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Delivery Boy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="text-center">Edit Delivery Boy</h3>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>" required>
            </div>
            <button type="submit" name="update_delivery_boy" class="btn btn-primary w-100">Update</button>
            <a href="manage_delivery_boys.php" class="btn btn-secondary w-100 mt-2">Back to List</a>
        </form>
    </div>
</div>

</body>
</html>
