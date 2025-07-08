<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    $_SESSION['error'] = "Invalid booking selected.";
    header("Location: /servicehub/public/backend/bookings.php");
    exit;
}

// Fetch booking details
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    $_SESSION['error'] = "Booking not found.";
    header("Location: /servicehub/public/backend/bookings.php");
    exit;
}

// Fetch service details
$service_stmt = $pdo->prepare("SELECT title, description, price FROM services WHERE id = ?");
$service_stmt->execute([$booking['service_id']]);
$service = $service_stmt->fetch(PDO::FETCH_ASSOC);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container py-5" style="max-width: 700px;">
                <h2 class="text-center mb-4">Booking Details</h2>

                <div class="card shadow p-4">
                    <h5>Service: <?= htmlspecialchars($service['title'] ?? 'Unknown') ?></h5>
                    <p><?= htmlspecialchars($service['description'] ?? '') ?></p>

                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Scheduled Date:</strong> <?= date('F j, Y, g:i a', strtotime($booking['scheduled_date'])) ?></li>
                        <li class="list-group-item"><strong>Status:</strong> <?= ucfirst($booking['status']) ?></li>
                        <li class="list-group-item"><strong>Payment Method:</strong> <?= ucfirst($booking['payment_method']) ?></li>
                        <li class="list-group-item"><strong>Amount Paid:</strong> ₦<?= number_format($booking['amount'], 2) ?></li>
                        <li class="list-group-item"><strong>Notes:</strong> <?= htmlspecialchars($booking['additional_notes'] ?: 'None') ?></li>
                    </ul>

                    <?php if ($booking['status'] === 'pending') { ?>
                        <form action="/servicehub/api/process-booking-action.php" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            <button type="submit" name="action" value="accept" class="btn btn-success w-50">Accept Booking</button>
                            <button type="submit" name="action" value="cancel" class="btn btn-danger w-50">Cancel Booking</button>
                        </form>
                    <?php } else { ?>
                        <div class="alert alert-info text-center">
                            Booking is already <strong><?= ucfirst($booking['status']) ?></strong>.
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>