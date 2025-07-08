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
    // Fetch service details
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();

    if (!$service) {
        echo json_encode(['success' => false, 'message' => 'Service not found']);
        exit;
    }

    $service_price = (float) $service['price'];

    if ($payment_method === 'wallet') {
        // Fetch user's current wallet balance
        $walletStmt = $pdo->prepare("SELECT balance FROM wallets WHERE user_id = ?");
        $walletStmt->execute([$user_id]);
        $wallet = $walletStmt->fetch();

        if (!$wallet) {
            echo json_encode(['success' => false, 'message' => 'Wallet not found']);
            exit;
        }

        $current_balance = (float) $wallet['balance'];

        if ($current_balance < $service_price) {
            echo json_encode(['success' => false, 'message' => 'Insufficient wallet balance']);
            exit;
        }

        // Deduct amount from wallet
        $new_balance = $current_balance - $service_price;
        $updateWallet = $pdo->prepare("UPDATE wallets SET balance = ?, last_updated = NOW() WHERE user_id = ?");
        $updateWallet->execute([$new_balance, $user_id]);
    }

    // Insert booking
    $insert = $pdo->prepare("INSERT INTO bookings (user_id, service_id, scheduled_date, additional_notes, payment_method, status, created_at)
                             VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $insert->execute([$user_id, $service_id, $scheduled_date, $additional_notes, $payment_method]);

    echo json_encode(['success' => true, 'message' => 'Booking created and payment processed successfully']);
} catch (PDOException $e) {
    error_log("Booking failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
exit;
