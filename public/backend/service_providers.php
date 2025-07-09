<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

// Validate service_id
$service_id = $_GET['service_id'] ?? null;

if (!$service_id) {
    $_SESSION['error'] = "Invalid service selected.";
    header("Location: /servicehub/public/backend/services.php");
    exit;
}

// Get service with provider using util function
$serviceData = getServiceWithProvider($pdo, $service_id);

if (!$serviceData) {
    echo "Service ID:" . $service_id . "<br>";
    echo "Provider not found.";
    exit;
}

$service = $serviceData['service'];
$provider = $serviceData['provider'];
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <h2 class="text-center mb-4"><?php echo htmlspecialchars($service['name']); ?></h2>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow text-center p-4">
                            <h5 class="card-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                            <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($provider['city']); ?></p>
                            <p class="card-text"><strong>Price:</strong> ₦<?php echo number_format($service['price'], 2); ?></p>
                            <p class="card-text"><strong>Provider:</strong> <?php echo htmlspecialchars($provider['full_name']); ?></p>
                            <p class="card-text"><strong>Contact:</strong> <?php echo htmlspecialchars($provider['phone']); ?></p>
                            <a href="/servicehub/public/backend/provider_profile.php?provider_id=<?php echo $provider['id']; ?>" class="btn primary-btn mt-3">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>