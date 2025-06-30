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

// Fetch user details (optional, for contact information)
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$provider['user_id']]);
$user = $user->fetch();
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Book Service: <?php echo htmlspecialchars($provider['title']); ?></h2>
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
                </div>
                <div class="mt-4">
                    <form action="/servicehub/api/process-booking.php" method="POST">
                        <input type="hidden" name="provider_id" value="<?php echo $provider['id']; ?>">
                        <div class="mb-3">
                            <label for="scheduled_date" class="form-label">Schedule Date</label>
                            <input type="datetime-local" class="form-control" id="scheduled_date" name="scheduled_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>