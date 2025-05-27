<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $state = trim($_POST['state']);

    $error = "";

    // Validations
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Phone number must be 10 digits and numeric!";
    } elseif (!preg_match("/^[0-9]{6}$/", $pincode)) {
        $error = "Pincode must be 6 digits!";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password_raw)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol!";
    } elseif (empty($username) || empty($address) || empty($city) || empty($state)) {
        $error = "Please fill all required fields!";
    }

    if ($error) {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Validation Failed!',
            'text' => $error
        ];
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['swal'] = [
                'icon' => 'error',
                'title' => 'Registration Failed!',
                'text' => 'Email already exists!'
            ];
        } else {
            $password = password_hash($password_raw, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, phone, address, city, pincode, state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $username, $email, $password, $phone, $address, $city, $pincode, $state);

            if ($stmt->execute()) {
                $_SESSION['swal'] = [
                    'icon' => 'success',
                    'title' => 'Registered Successfully!',
                    'text' => 'Redirecting to login...',
                    'redirect' => 'login.php'
                ];
            } else {
                $_SESSION['swal'] = [
                    'icon' => 'error',
                    'title' => 'Registration Failed!',
                    'text' => 'Database error: ' . $stmt->error
                ];
            }
        }
        $stmt->close();
    }

    // Redirect back to the same page to show SweetAlert
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
        .btn-register {
            width: 100%;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2><i class="fa-solid fa-user-plus"></i> Customer Registration</h2>

        <form method="POST" id="registerForm">
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Full Name:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Your Name" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Your Email" required>
            </div>

            <div class="form-group password-wrapper">
                <label><i class="fa-solid fa-lock"></i> Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Your Password" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-phone"></i> Phone:</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Your Phone Number" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-map-marker-alt"></i> Address:</label>
                <textarea class="form-control" name="address" id="address" placeholder="Enter Your Address" required></textarea>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-city"></i> City:</label>
                <input type="text" class="form-control" name="city" id="city" placeholder="Enter Your City" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-map-pin"></i> Pincode:</label>
                <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Enter Your Pincode" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-location-dot"></i> State:</label>
                <input type="text" class="form-control" name="state" id="state" placeholder="Enter Your State" required>
            </div>

            <button type="submit" class="btn btn-success btn-register">
                <i class="fa-solid fa-user-check"></i> Register
            </button>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");
    const registerForm = document.getElementById("registerForm");

    togglePassword.addEventListener("click", function () {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            togglePassword.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            passwordField.type = "password";
            togglePassword.classList.replace("fa-eye-slash", "fa-eye");
        }
    });

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let username = document.getElementById("username").value.trim();
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value.trim();
        let phone = document.getElementById("phone").value.trim();
        let address = document.getElementById("address").value.trim();
        let city = document.getElementById("city").value.trim();
        let pincode = document.getElementById("pincode").value.trim();
        let state = document.getElementById("state").value.trim();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^\d{10}$/;
        const pincodeRegex = /^\d{6}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!username || !email || !password || !phone || !address || !city || !pincode || !state) {
            Swal.fire('Missing Fields!', 'Please fill in all required fields.', 'warning');
        } else if (!emailRegex.test(email)) {
            Swal.fire('Invalid Email!', 'Please enter a valid email address.', 'warning');
        } else if (!phoneRegex.test(phone)) {
            Swal.fire('Invalid Phone!', 'Phone number must be 10 digits and numeric.', 'warning');
        } else if (!pincodeRegex.test(pincode)) {
            Swal.fire('Invalid Pincode!', 'Pincode must be 6 digits.', 'warning');
        } else if (!passwordRegex.test(password)) {
            Swal.fire('Weak Password!', 'Password must be at least 8 characters long and include uppercase, lowercase, number, and symbol.', 'warning');
        } else {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to proceed with registration?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Register",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    registerForm.submit();
                }
            });
        }
    });
});
</script>

<?php if (isset($_SESSION['swal'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION["swal"]["icon"] ?>',
        title: '<?= $_SESSION["swal"]["title"] ?>',
        text: '<?= $_SESSION["swal"]["text"] ?>',
        showConfirmButton: <?= isset($_SESSION["swal"]["redirect"]) ? 'false' : 'true' ?>,
        timer: <?= isset($_SESSION["swal"]["redirect"]) ? '2000' : 'null' ?>
    }).then(() => {
        <?php if (isset($_SESSION["swal"]["redirect"])): ?>
        window.location.href = "<?= $_SESSION["swal"]["redirect"] ?>";
        <?php endif; ?>
    });
</script>
<?php unset($_SESSION['swal']); ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
