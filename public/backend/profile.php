<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$provider = getProviderDetails($pdo, $user['id']);

?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="container py-5" style="max-width: 800px;">
                    <h2 class="text-center mb-4">Your Profile</h2>

                    <form action="/servicehub/api/process-profile.php" method="POST">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['error']);
                                                                        unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['success']);
                                                                            unset($_SESSION['success']); ?></div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="input-field form-control" name="name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="input-field form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="input-field form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="input-field form-control" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="input-field form-control" name="city" value="<?= htmlspecialchars($user['city']) ?>" required>
                            </div>
                        </div>

                        <?php if (!empty($user['role'])): ?>
                            <hr class="my-4">
                            <h5 class="mb-3 fw-bold text-center">Service Provider Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Service Title</label>
                                    <input type="text" class="input-field form-control" name="service_title" value="<?= htmlspecialchars($provider['service_title'] ?? '') ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Service Price (₦)</label>
                                    <input type="number" class="input-field form-control" name="service_price" value="<?= htmlspecialchars($provider['service_price'] ?? 0) ?>" min="0">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Service Description</label>
                                    <textarea class="input-field form-control" name="service_description" rows="3"><?= htmlspecialchars($provider['service_description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="input-field form-control" name="password" placeholder="Leave blank to keep current password">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="input-field form-control" name="confirm_password" placeholder="Leave blank to keep current password">
                            </div>
                        </div>

                        <button type="submit" class="btn primary-btn w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>