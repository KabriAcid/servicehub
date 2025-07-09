<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../components/header.php';

// Check if the logged-in user is admin
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: /servicehub/public/backend/dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $status = 'active';

    if (empty($title) || empty($description) || empty($price) || empty($icon)) {
        $_SESSION['error'] = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, price, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $description, $icon, $price, $status]);
            $_SESSION['success'] = "Service added successfully.";
            header('Location: /servicehub/public/backend/services.php');
            exit;
        } catch (PDOException $e) {
            error_log("Error adding service: " . $e->getMessage());
            $_SESSION['error'] = "Failed to add service.";
        }
    }
}
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="container py-5" style="max-width: 600px;">
                    <h2 class="text-center mb-4">Add New Service</h2>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($_SESSION['error']);
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success text-center">
                            <?= htmlspecialchars($_SESSION['success']);
                            unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Service Title</label>
                                <input type="text" class="input-field" name="title" required placeholder="e.g. Plumber, Mechanic, Doctor">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Service Description</label>
                                <textarea class="input-field" name="description" rows="4" required placeholder="Brief description of the service"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Service Price</label>
                                <input type="number" class="input-field" name="price" required placeholder="&#8358;">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Icon Class <small class="text-muted">(Font Awesome)</small></label>
                                <input type="text" class="input-field" name="icon" required placeholder="e.g. fa-solid fa-wrench">
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn primary-btn w-100">Add Service</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>