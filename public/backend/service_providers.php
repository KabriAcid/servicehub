<!-- filepath: c:\xampp\htdocs\servicehub\public\backend\service_providers.php -->
<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$service_id = $_GET['service_id'] ?? null;

if (!$service_id) {
    $_SESSION['error'] = "Invalid service selected.";
    header("Location: /servicehub/public/backend/services.php");
    exit;
}

// Fetch service details
$service = getServiceDetails($pdo, $service_id);

if (!$service) {
    $_SESSION['error'] = "Service not found.";
    header("Location: /servicehub/public/backend/services.php");
    exit;
}

// Fetch service providers
$providers = $pdo->prepare("SELECT * FROM providers WHERE service_id = ? AND status = 'active'");
$providers->execute([$service_id]);
$providers = $providers->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Service Providers for <?php echo htmlspecialchars($service['title']); ?></h2>
                <div class="row g-4">
                    <?php if (count($providers) > 0) { ?>
                        <?php foreach ($providers as $provider) { ?>
                            <div class="col-md-4">
                                <div class="card shadow text-center p-4">
                                    <h5 class="card-title"><?php echo htmlspecialchars($provider['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($provider['description']); ?></p>
                                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($provider['location']); ?></p>
                                    <p class="card-text"><strong>Price:</strong> ₦<?php echo number_format($provider['price'], 2); ?></p>
                                    <p class="card-text"><strong>Rating:</strong> <?php echo htmlspecialchars($provider['rating']); ?> ★</p>
                                    <a href="/servicehub/public/backend/provider_profile.php?provider_id=<?php echo $provider['id']; ?>" class="btn btn-primary">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-center">No providers available for this service.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>