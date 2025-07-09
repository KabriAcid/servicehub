<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$service_id = $_POST['service_id'] ?? null;
$scheduled_date = $_POST['scheduled_date'] ?? null;
$additional_notes = $_POST['additional_notes'] ?? '';
$payment_method = $_POST['payment_method'] ?? 'wallet';
$user_id = $_SESSION['user_id'] ?? null;

if (!$service_id || !$scheduled_date || !$user_id) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Fetch service and provider details
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();

    if (!$service) {
        echo json_encode(['success' => false, 'message' => 'Service not found']);
        exit;
    }

    $amount = (float) $service['price'];
    $provider_id = (int) $service['user_id'];

    // Fetch user's current wallet balance
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || $user['wallet_balance'] < $amount) {
        echo json_encode(['success' => false, 'message' => 'Insufficient wallet balance']);
        exit;
    }

    // Begin Transaction
    $pdo->beginTransaction();

    // 1. Debit User
    $new_user_balance = $user['wallet_balance'] - $amount;
    $stmt = $pdo->prepare("UPDATE users SET wallet_balance = ? WHERE id = ?");
    $stmt->execute([$new_user_balance, $user_id]);

    // 2. Credit Provider
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$provider_id]);
    $provider = $stmt->fetch();
    $new_provider_balance = ($provider['wallet_balance'] ?? 0) + $amount;

    $stmt = $pdo->prepare("UPDATE users SET wallet_balance = ? WHERE id = ?");
    $stmt->execute([$new_provider_balance, $provider_id]);

    // 3. Create Booking
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, service_id, scheduled_date, additional_notes, payment_method, amount, status, created_at)
                           VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $service_id, $scheduled_date, $additional_notes, $payment_method, $amount]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Booking created and payment processed successfully']);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Booking failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
exit;
