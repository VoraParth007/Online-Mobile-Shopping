<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// [Previous PHP code remains exactly the same...]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Categories</title>
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
        
        /* Mobile-first container */
        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }
        
        /* App-like header */
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
        
        .app-title i {
            font-size: 1.1em;
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
        
        /* Content area */
        .content {
            padding: 20px;
            padding-bottom: 80px;
        }
        
        /* Floating action button */
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
            z-index: 90;
            transition: all 0.3s;
            border: none;
        }
        
        .fab:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }
        
        /* Cards */
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
            gap: 10px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Form elements */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background-color: white;
        }
        
        /* Buttons */
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
        }
        
        .btn i {
            font-size: 1em;
        }
        
        .btn-block {
            display: flex;
            width: 100%;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        /* Category list */
        .category-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .category-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }
        
        .category-item:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-2px);
        }
        
        .category-info {
            flex: 1;
        }
        
        .category-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .category-id {
            font-size: 0.8rem;
            color: var(--gray);
        }
        
        .category-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .edit-btn {
            background: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }
        
        .delete-btn {
            background: rgba(239, 35, 60, 0.1);
            color: var(--danger);
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        /* Modal */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 15px 20px;
        }
        
        .modal-title {
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Alert notifications */
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }
        
        /* Swal2 customizations */
        .swal2-popup {
            font-family: 'Poppins', sans-serif;
            border-radius: 12px !important;
        }
        
        /* Responsive adjustments */
        @media (min-width: 768px) {
            .container {
                max-width: 750px;
                margin: 0 auto;
                padding: 20px;
            }
            
            .content {
                padding: 30px;
                padding-bottom: 100px;
            }
            
            .category-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }
            
            .category-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .category-actions {
                width: 100%;
                justify-content: flex-end;
                margin-top: 15px;
            }
            
            .fab {
                bottom: 30px;
                right: 30px;
            }
        }
        
        /* Animation */
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
            <i class="fas fa-list"></i> Manage Categories
        </h1>
        <div style="width: 40px;"></div> <!-- Spacer for balance -->
    </header>

    <main class="content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show animate" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add Category Card (hidden by default, shown by FAB) -->
        <div class="card animate" id="addCategoryCard" style="display: none;">
            <div class="card-header">
                <i class="fas fa-plus-circle"></i> Add New Category
            </div>
            <div class="card-body">
                <form method="post" id="addCategoryForm">
                    <div class="form-group">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter category name" required>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Add Category
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="category-list">
            <?php
            $result = $conn->query("SELECT * FROM categories");
            while ($cat = $result->fetch_assoc()): ?>
                <div class="category-item animate">
                    <div class="category-info">
                        <div class="category-name"><?php echo htmlspecialchars($cat['category_name']); ?></div>
                        <div class="category-id">ID: <?php echo $cat['id']; ?></div>
                    </div>
                    <div class="category-actions">
                        <button class="action-btn edit-btn" onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars(addslashes($cat['category_name'])); ?>')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" onclick="confirmDelete(<?php echo $cat['id']; ?>)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Floating Action Button -->
        <button class="fab" id="fabButton">
            <i class="fas fa-plus"></i>
        </button>
    </main>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit"></i> Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="editCategoryForm">
                        <input type="hidden" name="category_id" id="category_id">
                        <div class="form-group">
                            <label for="edit_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        <button type="submit" name="edit_category" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // FAB toggle for add category form
        const fabButton = document.getElementById('fabButton');
        const addCategoryCard = document.getElementById('addCategoryCard');
        
        fabButton.addEventListener('click', () => {
            if (addCategoryCard.style.display === 'none') {
                addCategoryCard.style.display = 'block';
                fabButton.innerHTML = '<i class="fas fa-times"></i>';
                fabButton.style.transform = 'rotate(135deg)';
                fabButton.style.background = 'var(--danger)';
            } else {
                addCategoryCard.style.display = 'none';
                fabButton.innerHTML = '<i class="fas fa-plus"></i>';
                fabButton.style.transform = 'rotate(0)';
                fabButton.style.background = 'var(--primary)';
            }
        });

        // Delete confirmation with SweetAlert2
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Category?',
                text: "This will permanently delete the category",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--gray)',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                background: 'white',
                backdrop: `
                    rgba(0,0,0,0.4)
                    url("/images/trash-animation.gif")
                    center top
                    no-repeat
                `,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "manage_categories.php?delete=" + id;
                }
            });
        }

        // Edit category function
        function editCategory(id, name) {
            document.getElementById('category_id').value = id;
            document.getElementById('edit_name').value = name;
            
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
            
            // Focus on input when modal opens
            setTimeout(() => {
                document.getElementById('edit_name').focus();
            }, 500);
        }

        // Form validation
        document.getElementById('addCategoryForm').addEventListener('submit', (e) => {
            const input = document.getElementById('categoryName');
            if (input.value.trim() === '') {
                e.preventDefault();
                input.focus();
                Swal.fire({
                    title: 'Oops!',
                    text: 'Category name cannot be empty',
                    icon: 'error',
                    confirmButtonColor: 'var(--primary)'
                });
            }
        });

        // Add animation class to elements as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.category-item, .alert').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
