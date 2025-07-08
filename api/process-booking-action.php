<?php
require __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request.';
    header("Location: /servicehub/public/backend/bookings.php");
    exit;
}

$booking_id = $_POST['booking_id'] ?? null;
$action = $_POST['action'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$booking_id || !$action || !$user_id) {
    $_SESSION['error'] = 'Missing data.';
    header("Location: /servicehub/public/backend/bookings.php");
    exit;
}

// Validate booking ownership
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch();

if (!$booking) {
    $_SESSION['error'] = 'Booking not found.';
    header("Location: /servicehub/public/backend/bookings.php");
    exit;
}

// Process action
$status = ($action === 'accept') ? 'accepted' : (($action === 'cancel') ? 'cancelled' : null);

if ($status) {
    $update = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $update->execute([$status, $booking_id]);
    $_SESSION['success'] = "Booking has been " . ucfirst($status) . ".";
} else {
    $_SESSION['error'] = "Invalid action.";
}

header("Location: /servicehub/public/backend/bookings.php");
exit;
