<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['subscribe_status'] = ['type' => 'error', 'message' => 'Invalid email format.'];
    } else {
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $_SESSION['subscribe_status'] = ['type' => 'success', 'message' => 'Subscribed successfully!'];
        } else {
            if ($conn->errno == 1062) {
                $_SESSION['subscribe_status'] = ['type' => 'warning', 'message' => 'This email is already subscribed.'];
            } else {
                $_SESSION['subscribe_status'] = ['type' => 'error', 'message' => 'Something went wrong. Please try again.'];
            }
        }
        $stmt->close();
    }

    $conn->close();
    header("Location: index.php");
    exit();
}
?>
    