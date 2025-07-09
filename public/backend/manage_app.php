<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../functions/utilities.php';
require_once __DIR__ . '/../components/header.php';

// Ensure only admin can access
if ($_SESSION['role'] !== 'admin') {
    header("Location: /servicehub/public/backend/dashboard.php");
    exit;
}

// Fetch all services
$stmt = $pdo->query("SELECT * FROM services ORDER BY created_at DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <h2 class="text-center mb-4">Manage Services</h2>

                <?php if (count($services) > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="bg-accent-color text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Provider</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $index => $service) { ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($service['title']); ?></td>
                                        <td><?= htmlspecialchars($service['provider_name'] ?? 'Admin'); ?></td>
                                        <td>₦<?= number_format($service['price'], 2); ?></td>
                                        <td><?= htmlspecialchars(shortenText($service['description'], 50)); ?></td>
                                        <td>
                                            <a href="edit_service.php?id=<?= $service['id']; ?>" class="badge bg-warning text-dark">Edit</a>
                                            <a href="delete_service.php?id=<?= $service['id']; ?>" class="badge bg-danger text-white"
                                                onclick="return confirm('Are you sure you want to delete this service?');">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-center">No services available.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>