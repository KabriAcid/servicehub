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
                    <form id="booking-form">
                        <input type="hidden" name="provider_id" value="<?php echo $provider['id']; ?>">
                        <input type="hidden" name="service_id" value="<?php echo $provider['service_id']; ?>">
                        <div class="mb-3">
                            <label for="scheduled_date" class="form-label">Schedule Date</label>
                            <input type="datetime-local" class="input-field" id="scheduled_date" name="scheduled_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="additional_notes" class="form-label">Additional Notes <i class="text-secondary small">(Optional)</i></label>
                            <textarea class="input-field" id="additional_notes" name="additional_notes" rows="4" placeholder="Enter any additional notes or requirements"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="wallet" selected>Wallet</option>
                                <option value="card">Card</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <button type="button" id="confirm-booking-btn" class="btn primary-btn w-100">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Dialog -->
    <div class="modal" id="wallet-confirm-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Wallet Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to proceed with wallet payment?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirm-wallet-btn" class="btn primary-btn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loader Animation -->
    <div id="loader" style="display: none;">
        <div class="spinner-border accent-color" role="status">
            <span class="visually-hidden">Processing...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('confirm-booking-btn').addEventListener('click', function() {
            const paymentMethod = document.getElementById('payment_method').value;

            if (paymentMethod === 'wallet') {
                const modal = new bootstrap.Modal(document.getElementById('wallet-confirm-modal'));
                modal.show();
            } else {
                document.getElementById('booking-form').submit();
            }
        });

        document.getElementById('confirm-wallet-btn').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('booking-form'));
            const loader = document.getElementById('loader');
            loader.style.display = 'flex';

            // Timeout to hide loader after 10 seconds if the request takes too long
            const loaderTimeout = setTimeout(() => {
                loader.style.display = 'none';
                alert('The request is taking longer than expected. Please try again later.');
            }, 10000); // 10 seconds

            fetch('/servicehub/api/process-booking.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    clearTimeout(loaderTimeout); 
                    loader.style.display = 'none';
                    if (data.success) {
                        alert('Booking successful!');
                        window.location.href = '/servicehub/public/backend/dashboard.php';
                    } else {
                        alert('Booking failed: ' + data.message);
                    }
                })
                .catch(error => {
                    clearTimeout(loaderTimeout); // Clear timeout if request fails
                    loader.style.display = 'none';
                    console.error('Error:', error);
                    alert('An error occurred while processing your booking.');
                });
        });
    </script>
</body>

</html>