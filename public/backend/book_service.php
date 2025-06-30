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
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Book Service: <?php echo htmlspecialchars($provider['title']); ?></h2>
                <div class="card shadow p-4">
                    <form action="/servicehub/api/process-booking.php" method="POST">
                        <input type="hidden" name="provider_id" value="<?php echo $provider['id']; ?>">
                        <input type="hidden" name="service_id" value="<?php echo $provider['service_id']; ?>">
                        <div class="mb-3">
                            <label for="scheduled_date" class="form-label">Schedule Date</label>
                            <input type="datetime-local" class="input-field" id="scheduled_date" name="scheduled_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="additional_notes" class="form-label">Additional Notes</label>
                            <textarea class="input-field" id="additional_notes" name="additional_notes" rows="4" placeholder="Enter any additional notes or requirements"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="" disabled selected>Select a payment method</option>
                                <option value="wallet">Wallet</option>
                                <option value="card">Card</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <button type="submit" class="btn primary-btn w-100">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>