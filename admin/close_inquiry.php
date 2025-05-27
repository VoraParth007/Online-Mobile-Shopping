<?php
session_name("admin_session");
session_start();
include '../includes/config.php';

if (isset($_GET['id'])) {
    $inquiry_id = $_GET['id'];

    // Update status to "Closed"
    $stmt = $conn->prepare("UPDATE inquiries SET status = 'Closed' WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);

    if ($stmt->execute()) {
        echo "<script>alert('Inquiry closed successfully!'); window.location.href='manage_inquiries.php';</script>";
    } else {
        echo "<script>alert('Failed to close inquiry. Try again!');</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='manage_inquiries.php';</script>";
}
?>
