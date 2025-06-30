<!-- filepath: c:\xampp\htdocs\servicehub\public\backend\provider_profile.php -->
<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$provider_id = $_GET['provider_id'] ?? null;

if (!$provider_id) {
    $_SESSION['error'] = "Invalid provider selected.";
    header("Location: /servicehub/public/backend/service_providers.php");
    exit;
}

// Fetch provider details
$provider = $pdo->prepare("SELECT * FROM providers WHERE id = ? AND status = 'active'");
$provider->execute([$provider_id]);
$provider = $provider->fetch();

if (!$provider) {
    $_SESSION['error'] = "Provider not found.";
    header("Location: /servicehub/public/backend/service_providers.php");
    exit;
}

// Fetch user details (optional, if you want to show user-specific information)
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$provider['user_id']]);
$user = $user->fetch();
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <h2 class="text-center mb-4"><?php echo htmlspecialchars($provider['title']); ?> Profile</h2>
                <div class="card shadow p-4">
                    <h3 class="card-title"><?php echo htmlspecialchars($provider['title']); ?></h3>
                    <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($provider['description']); ?></p>
                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($provider['location']); ?></p>
                    <p class="card-text"><strong>Price:</strong> ₦<?php echo number_format($provider['price'], 2); ?></p>
                    <p class="card-text"><strong>Rating:</strong> <?php echo htmlspecialchars($provider['rating']); ?> ★</p>
                    <?php if ($user) { ?>
                        <p class="card-text"><strong>Contact:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                    <?php } ?>
                    <a href="/servicehub/public/backend/book-service.php?provider_id=<?php echo $provider['id']; ?>" class="btn btn-primary">
                        Book Service
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>