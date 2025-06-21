<?php
session_name("admin_session");
session_start();
include('../includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manage Brands</title>
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
        
        .form-control, .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }
        
        .form-control:focus, .form-select:focus {
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
        
        /* Brand list */
        .brand-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .brand-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }
        
        .brand-item:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-2px);
        }
        
        .brand-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .brand-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 8px;
            background-color: #f1f5f9;
            padding: 5px;
        }
        
        .brand-text {
            flex: 1;
        }
        
        .brand-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .brand-category {
            font-size: 0.85rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .brand-id {
            font-size: 0.8rem;
            color: var(--gray);
        }
        
        .brand-actions {
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
            
            .brand-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }
            
            .brand-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .brand-info {
                width: 100%;
            }
            
            .brand-actions {
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
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #e2e8f0;
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
            <i class="fas fa-tags"></i> Manage Brands
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

        <!-- Add Brand Card (hidden by default, shown by FAB) -->
        <div class="card animate" id="addBrandCard" style="display: none;">
            <div class="card-header">
                <i class="fas fa-plus-circle"></i> Add New Brand
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="addBrandForm">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php
                            $categories = mysqli_query($conn, "SELECT * FROM categories");
                            while ($category = mysqli_fetch_assoc($categories)) {
                                echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Brand Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter brand name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Brand Logo</label>
                        <input type="file" class="form-control" name="logo">
                        <small class="text-muted">Recommended size: 200x200 pixels</small>
                    </div>
                    <button type="submit" name="add_brand" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Add Brand
                    </button>
                </form>
            </div>
        </div>

        <!-- Brands List -->
        <div class="brand-list">
            <?php
            $result = $conn->query("SELECT brands.*, categories.category_name FROM brands JOIN categories ON brands.category_id = categories.id");
            $brand_count = $result->num_rows;
            
            if ($brand_count > 0):
                while ($brand = $result->fetch_assoc()): ?>
                    <div class="brand-item animate">
                        <div class="brand-info">
                            <?php if (!empty($brand['logo_image'])): ?>
                                <img src="<?= $brand['logo_image']; ?>" class="brand-logo" alt="<?= $brand['brand_name']; ?>">
                            <?php else: ?>
                                <div class="brand-logo">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="brand-text">
                                <div class="brand-name"><?= htmlspecialchars($brand['brand_name']); ?></div>
                                <div class="brand-category">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($brand['category_name']); ?>
                                </div>
                                <div class="brand-id">ID: <?= $brand['id']; ?></div>
                            </div>
                        </div>
                        
                        <div class="brand-actions">
                            <button class="action-btn edit-btn" onclick="editBrand(<?= $brand['id']; ?>, '<?= htmlspecialchars(addslashes($brand['brand_name'])); ?>', '<?= $brand['category_id']; ?>', '<?= $brand['logo_image']; ?>')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn" onclick="confirmDelete(<?= $brand['id']; ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <div class="empty-state animate">
                    <i class="fas fa-tags"></i>
                    <h4>No Brands Found</h4>
                    <p>Add your first brand to get started</p>
                </div>
            <?php endif; ?>
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
                        <i class="fas fa-edit"></i> Edit Brand
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="editBrandForm">
                        <input type="hidden" name="brand_id" id="brand_id">
                        <input type="hidden" name="existing_logo" id="existing_logo">

                        <div class="form-group">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="edit_category_id" id="edit_category_id" class="form-select" required>
                                <?php
                                $categories = mysqli_query($conn, "SELECT * FROM categories");
                                while ($category = mysqli_fetch_assoc($categories)) {
                                    echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Brand Logo</label>
                            <input type="file" class="form-control" name="edit_logo">
                            <small class="text-muted">Leave blank to keep current logo</small>
                        </div>
                        <button type="submit" name="edit_brand" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Brand
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // FAB toggle for add brand form
        const fabButton = document.getElementById('fabButton');
        const addBrandCard = document.getElementById('addBrandCard');
        
        fabButton.addEventListener('click', () => {
            if (addBrandCard.style.display === 'none') {
                addBrandCard.style.display = 'block';
                fabButton.innerHTML = '<i class="fas fa-times"></i>';
                fabButton.style.transform = 'rotate(135deg)';
                fabButton.style.background = 'var(--danger)';
            } else {
                addBrandCard.style.display = 'none';
                fabButton.innerHTML = '<i class="fas fa-plus"></i>';
                fabButton.style.transform = 'rotate(0)';
                fabButton.style.background = 'var(--primary)';
            }
        });

        // Delete confirmation with SweetAlert2
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Brand?',
                text: "This will permanently delete the brand and its products",
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
                    window.location.href = "manage_brands.php?delete_brand=" + id;
                }
            });
        }

        // Edit brand function
        function editBrand(id, name, category, logo) {
            document.getElementById('brand_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = category;
            document.getElementById('existing_logo').value = logo;
            
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
            
            // Focus on input when modal opens
            setTimeout(() => {
                document.getElementById('edit_name').focus();
            }, 500);
        }

        // Form validation
        document.getElementById('addBrandForm').addEventListener('submit', (e) => {
            const input = document.querySelector('#addBrandForm input[name="name"]');
            if (input.value.trim() === '') {
                e.preventDefault();
                input.focus();
                Swal.fire({
                    title: 'Oops!',
                    text: 'Brand name cannot be empty',
                    icon: 'error',
                    confirmButtonColor: 'var(--primary)'
                });
            }
        });

        document.getElementById('editBrandForm').addEventListener('submit', (e) => {
            const input = document.getElementById('edit_name');
            if (input.value.trim() === '') {
                e.preventDefault();
                input.focus();
                Swal.fire({
                    title: 'Oops!',
                    text: 'Brand name cannot be empty',
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

        document.querySelectorAll('.brand-item, .alert, .empty-state').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
