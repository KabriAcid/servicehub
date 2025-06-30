<!-- filepath: c:\xampp\htdocs\servicehub\public\backend\services.php -->
<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';

// Fetch all available services
$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid mb-4">
                <h2 class="text-center mb-4">Available Services</h2>
                <div class="row g-4">
                    <?php foreach ($services as $service) { ?>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4" style="min-height: 300px;">
                                <div class="card-icon primary mb-2">
                                    <i class="fa <?php echo htmlspecialchars($service['icon']); ?> fa-3x"></i>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                                <a href="/servicehub/public/backend/service_providers.php?service_id=<?php echo $service['id']; ?>" class="btn primary-btn">
                                    View Providers
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>