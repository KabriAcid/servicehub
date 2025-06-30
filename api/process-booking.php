<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../functions/utilities.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $user['id'];
    $provider_id = $_POST['provider_id'] ?? null;
    $service_id = $_POST['service_id'] ?? null;
    $scheduled_date = $_POST['scheduled_date'] ?? null;
    $additional_notes = $_POST['additional_notes'] ?? null;

    if (!$provider_id || !$service_id || !$scheduled_date) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    try {
        // Fetch provider details
        $provider = $pdo->prepare("SELECT * FROM providers WHERE id = ?");
        $provider->execute([$provider_id]);
        $provider = $provider->fetch();

        if (!$provider) {
            echo json_encode(['success' => false, 'message' => 'Provider not found.']);
            exit;
        }

        // Check wallet balance
        $wallet_balance = getWalletBalance($pdo, $customer_id);
        $amount = $provider['price'];

        if ($wallet_balance < $amount) {
            echo json_encode(['success' => false, 'message' => 'Insufficient wallet balance.']);
            exit;
        }

        // Deduct wallet balance
        $stmt = $pdo->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ?");
        $stmt->execute([$amount, $customer_id]);

        // Insert booking into database
        $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, provider_id, service_id, scheduled_date, additional_notes, payment_method, amount, status) VALUES (?, ?, ?, ?, ?, 'wallet', ?, 'pending')");
        $stmt->execute([$customer_id, $provider_id, $service_id, $scheduled_date, $additional_notes, $amount]);

        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        error_log("Booking error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred while processing your booking.']);
        exit;
    }
}
