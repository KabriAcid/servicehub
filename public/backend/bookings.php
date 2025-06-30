<!-- filepath: c:\xampp\htdocs\servicehub\public\backend\bookings.php -->
<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$current_user_id = $user['id']; // Assuming the logged-in user's ID is stored in `$current_user`

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
                                    <th>Provider</th>
                                    <th>Scheduled Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $index => $booking) { ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($booking['service_title']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['provider_title']); ?></td>
                                        <td><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($booking['scheduled_date']))); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($booking['status'])); ?></td>
                                        <td>₦<?php echo number_format($booking['amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($booking['additional_notes'] ?? 'N/A'); ?></td>
                                        <td>
                                            <a href="/servicehub/public/backend/booking_details.php?booking_id=<?php echo $booking['id']; ?>" class="badge bg-accent-color btn-sm">
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