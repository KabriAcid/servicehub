<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../components/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$serviceId = $_GET['id'] ?? null;

if (!$serviceId) {
    header("Location: manage_app.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    $_SESSION['error'] = "Service not found.";
    header("Location: manage_app.php");
    exit;
}
?>

<body>
    <div class="main-container">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container py-5" style="max-width: 800px;">
                <h2 class="text-center mb-4">Edit Service</h2>

                <form action="/servicehub/api/process-edit-service.php" method="POST">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($_SESSION['error']);
                            unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success text-center">
                            <?= htmlspecialchars($_SESSION['success']);
                            unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <input type="hidden" name="service_id" value="<?= $service['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($service['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Service Price (₦)</label>
                        <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($service['price']); ?>" required min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Service Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($service['description']); ?></textarea>
                    </div>

                    <button type="submit" class="btn primary-btn w-100">Update Service</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>