<!-- filepath: c:\xampp\htdocs\servicehub\public\backend\bookings.php -->
<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

$current_user_id = $user['id']; // Assuming the logged-in user's ID is stored in `$current_user`

// Fetch bookings for the current user
$bookings = $pdo->prepare("
    SELECT 
        bookings.id, 
        bookings.scheduled_date, 
        bookings.additional_notes, 
        bookings.status, 
        bookings.amount, 
        providers.title AS provider_title, 
        services.title AS service_title 
    FROM bookings
    INNER JOIN providers ON bookings.provider_id = providers.id
    INNER JOIN services ON bookings.service_id = services.id
    WHERE bookings.customer_id = ?
    ORDER BY bookings.scheduled_date DESC
");
$bookings->execute([$current_user_id]);
$bookings = $bookings->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <h2 class="text-center mb-4">My Bookings</h2>
                <div class="row g-4">
                    <?php if (count($bookings) > 0) { ?>
                        <?php foreach ($bookings as $booking) { ?>
                            <div class="col-md-6">
                                <div class="card shadow p-4">
                                    <h5 class="card-title"><?php echo htmlspecialchars($booking['service_title']); ?></h5>
                                    <p class="card-text"><strong>Provider:</strong> <?php echo htmlspecialchars($booking['provider_title']); ?></p>
                                    <p class="card-text"><strong>Scheduled Date:</strong> <?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($booking['scheduled_date']))); ?></p>
                                    <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($booking['status'])); ?></p>
                                    <p class="card-text"><strong>Amount:</strong> ₦<?php echo number_format($booking['amount'], 2); ?></p>
                                    <?php if (!empty($booking['additional_notes'])) { ?>
                                        <p class="card-text"><strong>Notes:</strong> <?php echo htmlspecialchars($booking['additional_notes']); ?></p>
                                    <?php } ?>
                                    <a href="/servicehub/public/backend/booking_details.php?booking_id=<?php echo $booking['id']; ?>" class="btn primary-btn">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-center">You have no bookings yet.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>