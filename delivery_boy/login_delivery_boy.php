<?php
session_name("delivery_session");
session_start();
include ('../includes/config.php');

if (isset($_SESSION['delivery_boy_id'])) {
    header("Location: delivery_dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM delivery_boys WHERE email='$email'");
    $row = mysqli_fetch_assoc($query);

    if ($row && password_verify($password, $row['password'])) {
        if ($row['status'] == 'Pending') {
            $error = "⚠️ Your account is pending approval.";
        } elseif ($row['status'] == 'Rejected') {
            $error = "❌ Your account has been rejected.";
        } else {
            $_SESSION['delivery_boy_id'] = $row['id'];
            $_SESSION['delivery_boy_name'] = $row['name'];
            header("Location: delivery_dashboard.php");
            exit;
        }
    } else {
        $error = "⚠️ Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Boy Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
        }
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-card h3 {
            color: #007bff;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .input-group-text {
            background-color: #e9ecef;
        }
        .form-control {
            height: 45px;
        }
        .btn-login {
            height: 45px;
            font-weight: 600;
            background: #007bff;
            border: none;
        }
        .btn-login:hover {
            background: #0056b3;
        }
        .register-link {
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <h3><i class="bi bi-bicycle"></i> Delivery Boy Login</h3>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger text-center"><?= $error; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email address" required>
            </div>
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-login w-100">Login</button>
        </form>

        <div class="register-link">
            <small>Don't have an account? <a href="register_delivery_boy.php">Register here</a></small>
        </div>
    </div>
</div>
</body>
</html>
