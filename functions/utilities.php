<?php

/**
 * Get user information by ID.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return array|null
 */
function getUserInfo($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$user_id]);
        return $query->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user info: " . $e->getMessage());
        return null;
    }
}

/**
 * Get wallet balance for a user.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return float
 */
function getWalletBalance($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT balance FROM wallets WHERE user_id = ?");
        $query->execute([$user_id]);
        $result = $query->fetch();
        return $result ? (float)$result['balance'] : 0.00;
    } catch (PDOException $e) {
        error_log("Error fetching wallet balance: " . $e->getMessage());
        return 0.00;
    }
}

/**
 * Get total bookings for a customer.
 *
 * @param PDO $pdo
 * @param int $customer_id
 * @return int
 */
function getTotalBookings($pdo, $customer_id)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE customer_id = ?");
        $query->execute([$customer_id]);
        $result = $query->fetch();
        return $result ? (int)$result['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error fetching total bookings: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get total jobs completed by a provider.
 *
 * @param PDO $pdo
 * @param int $provider_id
 * @return int
 */
function getTotalJobsCompleted($pdo, $provider_id)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM bookings WHERE provider_id = ? AND status = 'completed'");
        $query->execute([$provider_id]);
        $result = $query->fetch();
        return $result ? (int)$result['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error fetching total jobs completed: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get average rating for a provider.
 *
 * @param PDO $pdo
 * @param int $provider_id
 * @return float
 */
function getAverageRating($pdo, $provider_id)
{
    try {
        $query = $pdo->prepare("SELECT AVG(stars) AS average FROM ratings WHERE provider_id = ?");
        $query->execute([$provider_id]);
        $result = $query->fetch();
        return $result ? round((float)$result['average'], 2) : 0.00;
    } catch (PDOException $e) {
        error_log("Error fetching average rating: " . $e->getMessage());
        return 0.00;
    }
}

/**
 * Get total revenue for a provider.
 *
 * @param PDO $pdo
 * @param int $provider_id
 * @return float
 */
function getTotalRevenue($pdo, $provider_id)
{
    try {
        $query = $pdo->prepare("SELECT SUM(amount) AS total FROM transactions WHERE user_id = ? AND type = 'release'");
        $query->execute([$provider_id]);
        $result = $query->fetch();
        return $result ? (float)$result['total'] : 0.00;
    } catch (PDOException $e) {
        error_log("Error fetching total revenue: " . $e->getMessage());
        return 0.00;
    }
}

/**
 * Check if a user is an admin.
 *
 * @param PDO $pdo
 * @param int $user_id
 * @return bool
 */
function isAdmin($pdo, $user_id)
{
    try {
        $query = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $query->execute([$user_id]);
        $result = $query->fetch();
        return $result && $result['role'] === 'admin';
    } catch (PDOException $e) {
        error_log("Error checking admin status: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if a user exists by email.
 *
 * @param PDO $pdo
 * @param string $email
 * @return bool
 */
function userExists($pdo, $email)
{
    try {
        $query = $pdo->prepare("SELECT COUNT(*) AS total FROM users WHERE email = ?");
        $query->execute([$email]);
        $result = $query->fetch();
        return $result && $result['total'] > 0;
    } catch (PDOException $e) {
        error_log("Error checking user existence: " . $e->getMessage());
        return false;
    }
}

/**
 * Get service providers by service ID.
 *
 * @param PDO $pdo
 * @param int $service_id
 * @return array
 */
function getServiceProvidersByService($pdo, $service_id)
{
    try {
        $query = $pdo->prepare("SELECT * FROM users WHERE role = 'provider' AND service_id = ?");
        $query->execute([$service_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching service providers: " . $e->getMessage());
        return [];
    }
}

/**
 * Get service details by ID.
 *
 * @param PDO $pdo
 * @param int $service_id
 * @return array|null
 */
function getServiceDetails($pdo, $service_id)
{
    try {
        $query = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $query->execute([$service_id]);
        return $query->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching service details: " . $e->getMessage());
        return null;
    }
}

?>

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