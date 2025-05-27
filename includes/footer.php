<footer class="bg-light text-dark pt-5 pb-4">
    <div class="container text-center text-md-start">
        <div class="row">
            <!-- Logo & Social Media -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
                <h4 class="fw-bold display-6">
                    <span class="text-warning">Mobile Store</span>
                </h4>
                <p class="fs-5">Best place to buy latest mobile phones and accessories.</p>
                <div>
                    <a href="#" class="btn btn-outline-dark btn-lg rounded-circle me-2">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-lg rounded-circle me-2">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-lg rounded-circle me-2">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-lg rounded-circle">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Information Section -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                <h5 class="fw-bold fs-4">Company</h5>
                <ul class="list-unstyled fs-5">
                    <li><a href="#" class="text-dark fw-semibold">Home</a></li>
                    <li><a href="#" class="text-dark fw-semibold">About Us</a></li>
                    <li><a href="blog.php" class="text-dark fw-semibold">Blog</a></li>
                    <li><a href="inquiry.php" class="text-dark fw-semibold">Inquiry</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                <h5 class="fw-bold fs-4">Customer Service</h5>
                <ul class="list-unstyled fs-5">
                    <li><a href="#" class="text-dark fw-semibold">FAQ</a></li>
                    <li><a href="#" class="text-dark fw-semibold">Contact</a></li>
                    <li><a href="#" class="text-dark fw-semibold">Privacy Policy</a></li>
                    <li><a href="#" class="text-dark fw-semibold">Returns & Refunds</a></li>
                    <li><a href="#" class="text-dark fw-semibold">Shipping Info</a></li>
                </ul>
            </div>

            <!-- Subscription -->
            <div class="col-md-4 col-lg-3 col-xl-6 mx-auto mb-md-0 mb-4">
                <h5 class="fw-bold fs-4">Subscribe Us</h5>
                <p class="fs-5">Subscribe to get latest offers and updates.</p>
                <?php
                // Fetch email from session (assuming it's stored on login)
                $userEmail = isset($_SESSION['user_id']) ? '' : ''; // Default value

                if (isset($_SESSION['user_id'])) {
                    include('includes/config.php');
                    $userId = $_SESSION['user_id'];
                    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $stmt->bind_result($fetchedEmail);
                    if ($stmt->fetch()) {
                        $userEmail = $fetchedEmail;
                    }
                    $stmt->close();
                    $conn->close();
                }
                ?>
                <form class="d-flex" method="POST" action="subscribe.php">
                    <input type="email" name="email" class="form-control form-control-lg"
                        placeholder="Email Address" required
                        value="<?= htmlspecialchars($userEmail) ?>">
                    <button type="submit" class="btn btn-dark ms-2 btn-lg">Subscribe</button>
                </form>
            </div>

        </div>
    </div>
</footer>

<!-- ✅ SweetAlert2 Script + PHP Session Message Handling -->
<?php if (isset($_SESSION['subscribe_status'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['subscribe_status']['type'] ?>',
            title: '<?= ucfirst($_SESSION['subscribe_status']['type']) ?>',
            text: '<?= $_SESSION['subscribe_status']['message'] ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['subscribe_status']); ?>
<?php endif; ?>