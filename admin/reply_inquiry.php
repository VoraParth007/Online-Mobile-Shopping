<?php
session_name("admin_session");
session_start();
include '../includes/config.php';

if (isset($_GET['id'])) {
    $inquiry_id = $_GET['id'];

    // Fetch inquiry details
    $stmt = $conn->prepare("SELECT * FROM inquiries WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $inquiry = $result->fetch_assoc();

    if (!$inquiry) {
        echo "<script>alert('Inquiry not found!'); window.location.href='manage_inquiries.php';</script>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = $_POST['response'];
    
    // Update inquiry with response
    $stmt = $conn->prepare("UPDATE inquiries SET response = ?, status = 'Responded' WHERE id = ?");
    $stmt->bind_param("si", $response, $inquiry_id);

    if ($stmt->execute()) {
        echo "<script>alert('Inquiry replied successfully!'); window.location.href='manage_inquiries.php';</script>";
    } else {
        echo "<script>alert('Failed to reply. Try again!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reply to Inquiry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="bi bi-reply-fill me-2 text-success"></i>Reply to Inquiry</h4>
                <a href="manage_inquiries.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left-circle"></i> Back to Inquiries
                </a>
            </div>

            <div class="mb-4">
                <p><i class="bi bi-person-fill"></i> <strong>User:</strong> <?= htmlspecialchars($inquiry['name']) ?></p>
                <p><i class="bi bi-envelope-fill"></i> <strong>Email:</strong> <?= htmlspecialchars($inquiry['email']) ?></p>
                <p><i class="bi bi-chat-left-text-fill"></i> <strong>Message:</strong> <?= htmlspecialchars($inquiry['message']) ?></p>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-pencil-square"></i> Your Response</label>
                    <textarea name="response" class="form-control" rows="5" placeholder="Type your response here..." required></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send-fill"></i> Send Reply
                    </button>
                    <a href="manage_inquiries.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
