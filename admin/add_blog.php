<?php
session_name("admin_session");
session_start();
include('../includes/config.php'); // Database Connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $author = $_POST['author'];

    // Handle Image Upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Insert Data into Database
    $sql = "INSERT INTO blogs (title, content, image, category, author) 
            VALUES ('$title', '$content', '$image', '$category', '$author')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to manage_blogs.php with success message
        $_SESSION['success_message'] = "Blog Added Successfully!";
        header("Location: manage_blogs.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Add New Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #f72585;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #ef233c;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            --card-hover: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f8ff;
            color: var(--dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        
        .app-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .app-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-2px);
        }
        
        .content {
            padding: 20px;
            padding-bottom: 30px;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 25px;
            margin: 0 auto;
            max-width: 700px;
            transition: all 0.3s;
        }
        
        .form-card:hover {
            box-shadow: var(--card-hover);
        }
        
        .form-title {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary-dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        textarea.form-control {
            min-height: 150px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            gap: 8px;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 8px;
            padding: 15px;
        }
        
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            background-color: #f9fafc;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .file-upload-text {
            text-align: center;
            color: var(--gray);
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-name {
            margin-top: 10px;
            font-size: 0.85rem;
            color: var(--primary-dark);
            display: none;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }
            
            .form-card {
                padding: 20px;
            }
            
            .form-title {
                font-size: 1.2rem;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</head>

<body>
    <!-- App-like header -->
    <header class="app-header">
        <button class="back-btn" onclick="window.location.href='manage_blogs.php'">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="app-title">
            <i class="fas fa-blog"></i> Add New Blog
        </h1>
        <div style="width: 40px;"></div> <!-- Spacer for balance -->
    </header>

    <main class="content">
        <div class="form-card animate">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger animate">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <h2 class="form-title">
                <i class="fas fa-pen-fancy"></i> Create New Blog Post
            </h2>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter blog title" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="6" placeholder="Write your blog content here..." required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g., Technology, Health" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" placeholder="Author name" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Featured Image</label>
                    <div class="file-upload-wrapper">
                        <label class="file-upload-label" id="file-upload-label">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <span class="file-upload-text">Click to upload or drag and drop</span>
                            <span class="file-upload-text">PNG, JPG, JPEG (Max. 5MB)</span>
                            <span class="file-name" id="file-name"></span>
                            <input type="file" name="image" class="file-upload-input" id="file-upload-input" accept="image/*" required>
                        </label>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Publish Blog
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show file name when selected
        document.getElementById('file-upload-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file selected';
            const fileLabel = document.getElementById('file-name');
            const uploadLabel = document.getElementById('file-upload-label');
            
            fileLabel.textContent = fileName;
            fileLabel.style.display = 'block';
            uploadLabel.style.borderColor = '#4361ee';
            uploadLabel.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
        });

        // Add animation class to elements as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.form-card, .alert').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>

</html>
