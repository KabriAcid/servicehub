<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$current_user_id = $user['id'];

// Fetch bookings using the utility function
$bookings = getUserBookings($pdo, $current_user_id);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <h2 class="text-center mb-4">My Bookings</h2>

                <?php if (count($bookings) > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-accent-color">
                                <tr>
                                    <th>#</th>
                                    <th>Service</th>
                                    <th>Scheduled Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $index => $booking) {
                                    // Fetch the service title for each booking
                                    $stmt = $pdo->prepare("SELECT title FROM services WHERE id = ?");
                                    $stmt->execute([$booking['service_id']]);
                                    $service = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $service_title = $service['title'] ?? 'Unknown Service';
                                ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($service_title); ?></td>
                                        <td><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($booking['scheduled_date']))); ?></td>
                                        <td><?= htmlspecialchars(ucfirst($booking['status'])); ?></td>
                                        <td>₦<?= number_format($booking['amount'], 2); ?></td>
                                        <td><?= htmlspecialchars($booking['additional_notes'] ?? 'N/A'); ?></td>
                                        <td>
                                            <a href="/servicehub/public/backend/booking_details.php?booking_id=<?= $booking['id']; ?>" class="badge badge-secondary">
                                                View Details
                                            </a>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-center">You have no bookings yet.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>