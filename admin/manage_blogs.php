<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch all blogs
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");

// Delete Blog
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM blogs WHERE id=$id");
    $_SESSION['success_message'] = "Blog deleted successfully!";
    header("Location: manage_blogs.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Blogs</title>
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
            padding-bottom: 80px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s;
            border: none;
        }
        
        .card:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-3px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        
        .blog-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .blog-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        
        .blog-item:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-2px);
        }
        
        .blog-image-wrapper {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #f1f3f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .blog-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .blog-item:hover .blog-image {
            transform: scale(1.05);
        }
        
        .blog-info {
            flex: 1;
        }
        
        .blog-title {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }
        
        .blog-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        .blog-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .blog-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .btn-sm {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #d90429;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #3a86ff;
            transform: translateY(-2px);
        }
        
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: var(--success);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(76, 201, 240, 0.3);
            z-index: 90;
            transition: all 0.3s;
            border: none;
        }
        
        .fab:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 6px 15px rgba(76, 201, 240, 0.4);
        }
        
        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: 0.9rem;
            height: 100%;
        }
        
        @media (min-width: 768px) {
            .content {
                padding: 30px;
                padding-bottom: 30px;
            }
            
            .blog-item {
                flex-direction: row;
                align-items: flex-start;
            }
            
            .blog-image-wrapper {
                width: 200px;
                height: 140px;
                min-width: 200px;
                margin-bottom: 0;
                margin-right: 20px;
            }
            
            .blog-actions {
                margin-top: 0;
                margin-left: auto;
                align-self: center;
            }
            
            .fab {
                display: none;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</head>

<body>
    <!-- App-like header -->
    <header class="app-header">
        <button class="back-btn" onclick="window.location.href='dashboard.php'">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="app-title">
            <i class="fas fa-blog"></i> Manage Blogs
        </h1>
        <div style="width: 40px;"></div> <!-- Spacer for balance -->
    </header>

    <main class="content">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show animate" role="alert">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div>
                    <i class="fas fa-newspaper"></i> All Blog Posts
                </div>
                <a href="add_blog.php" class="btn btn-success btn-sm d-none d-md-inline-flex">
                    <i class="fas fa-plus"></i> Add New Blog
                </a>
            </div>
            <div class="card-body">
                <div class="blog-list">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="blog-item animate">
                            <div class="blog-image-wrapper">
                                <?php 
                                $imagePath = '../uploads/' . htmlspecialchars($row['image']);
                                if (!empty($row['image']) && file_exists($imagePath)): 
                                ?>
                                    <img src="<?= $imagePath ?>" class="blog-image" alt="<?= htmlspecialchars($row['title']) ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i> No Image
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="blog-info">
                                <h3 class="blog-title"><?= htmlspecialchars($row['title']) ?></h3>
                                <div class="blog-meta">
                                    <span><i class="fas fa-hashtag"></i> <?= htmlspecialchars($row['category']) ?></span>
                                    <span><i class="fas fa-user"></i> <?= htmlspecialchars($row['author']) ?></span>
                                    <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($row['created_at'])) ?></span>
                                </div>
                                <div class="blog-actions">
                                    <a href="edit_blog.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="manage_blogs.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Floating Action Button (Mobile Only) -->
        <a href="add_blog.php" class="fab d-md-none">
            <i class="fas fa-plus"></i>
        </a>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete() {
            return Swal.fire({
                title: 'Delete Blog?',
                text: "This will permanently delete the blog post",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--gray)',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                background: 'white',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                return result.isConfirmed;
            });
        }
        
        // Add animation class to elements as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.blog-item, .alert').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>

</html>
