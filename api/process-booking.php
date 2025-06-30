<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $current_user['id']; // Assuming the logged-in user's ID is stored in `$current_user`
    $provider_id = $_POST['provider_id'] ?? null;
    $service_id = $_POST['service_id'] ?? null;
    $scheduled_date = $_POST['scheduled_date'] ?? null;
    $additional_notes = $_POST['additional_notes'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;

    // Validate input
    if (!$provider_id || !$service_id || !$scheduled_date || !$payment_method) {
        $_SESSION['error'] = "Please fill all required fields.";
        header("Location: /servicehub/public/backend/book_service.php?provider_id=$provider_id");
        exit;
    }

    try {
        // Fetch provider details
        $provider = $pdo->prepare("SELECT * FROM providers WHERE id = ?");
        $provider->execute([$provider_id]);
        $provider = $provider->fetch();

        if (!$provider) {
            $_SESSION['error'] = "Provider not found.";
            header("Location: /servicehub/public/backend/book_service.php?provider_id=$provider_id");
            exit;
        }

        // Retrieve necessary payloads
        $amount = $provider['price'];

        // Insert booking into database
        $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, provider_id, service_id, scheduled_date, additional_notes, payment_method, amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$customer_id, $provider_id, $service_id, $scheduled_date, $additional_notes, $payment_method, $amount]);

        $_SESSION['success'] = "Service booked successfully.";
        header("Location: /servicehub/public/backend/book_service.php?provider_id=$provider_id");
        exit;
    } catch (Exception $e) {
        error_log("Booking error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while booking the service.";
        header("Location: /servicehub/public/backend/book_service.php?provider_id=$provider_id");
        exit;
    }
}
