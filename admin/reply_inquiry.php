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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Inquiry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6f42c1;
            --success: #1cc88a;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #2e4374;
            --text: #5a5c69;
            --card-bg: #ffffff;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #e6e9ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .reply-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
        
        .reply-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .reply-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 25px;
            border-bottom: none;
        }
        
        .card-title {
            font-weight: 600;
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-back {
            background: white;
            color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-back:hover {
            background: #f0f2f5;
            color: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        .card-body {
            padding: 30px;
        }
        
        .inquiry-details {
            background: var(--light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .detail-item {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .detail-icon {
            color: var(--primary);
            font-size: 1.2rem;
            min-width: 24px;
        }
        
        .detail-content {
            flex: 1;
        }
        
        .detail-label {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1.05rem;
            color: var(--text);
            word-break: break-word;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s;
            min-height: 150px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.15);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--success) 0%, #17a673 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(28, 200, 138, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(28, 200, 138, 0.4);
        }
        
        .btn-cancel {
            background: white;
            color: var(--text);
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-cancel:hover {
            background: #f8f9fc;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .card-header {
                padding: 20px;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .card-body {
                padding: 25px;
            }
            
            .btn-back {
                padding: 9px 18px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 576px) {
            .card-header {
                padding: 18px;
                text-align: center;
                flex-direction: column;
                gap: 15px;
            }
            
            .card-title {
                font-size: 1.4rem;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .inquiry-details {
                padding: 15px;
            }
            
            .detail-item {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-submit, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="reply-container">
        <div class="reply-card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="card-title">
                    <i class="bi bi-reply-fill"></i> Reply to Inquiry
                </h1>
                <a href="manage_inquiries.php" class="btn btn-back">
                    <i class="bi bi-arrow-left-circle"></i> Back to Inquiries
                </a>
            </div>
            
            <div class="card-body">
                <div class="inquiry-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">User</div>
                            <div class="detail-value"><?= htmlspecialchars($inquiry['name']) ?></div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?= htmlspecialchars($inquiry['email']) ?></div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-chat-left-text-fill"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Message</div>
                            <div class="detail-value"><?= htmlspecialchars($inquiry['message']) ?></div>
                        </div>
                    </div>
                </div>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-pencil-square"></i> Your Response
                        </label>
                        <textarea name="response" class="form-control" placeholder="Type your response here..." required></textarea>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send-fill"></i> Send Reply
                        </button>
                        <a href="manage_inquiries.php" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
