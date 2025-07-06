<?php
require_once __DIR__ . '/../config/config.php';

if (isset($_GET['transaction_id'])) {
    try {
        // Flutterwave API credentials
        $secret_key = $_ENV('FLUTTERWAVE_SECRET_KEY');
        $transaction_id = $_GET['transaction_id'];

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/transactions/' . $transaction_id . '/verify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $secret_key,
            'Content-Type: application/json'
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL
        curl_close($ch);

        // Decode the response
        $response_data = json_decode($response, true);

        // Handle the response
        if (isset($response_data['status']) && $response_data['status'] === 'success') {
            $amount = $response_data['data']['amount'];
            $tx_ref = $response_data['data']['tx_ref'];
            $user_id = $current_user['id']; // Assuming the logged-in user's ID is stored in `$current_user`

            // Update wallet balance
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
            $stmt->execute([$amount, $user_id]);

            // Log the transaction
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, tx_ref, amount, status, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, $tx_ref, $amount, 'completed']);

            $_SESSION['success'] = "Deposit successful!";
            header("Location: ../public/backend/deposit.php");
            exit;
        } else {
            $_SESSION['error'] = "Payment verification failed: " . ($response_data['message'] ?? 'Unknown error');
            header("Location: ../public/backend/deposit.php");
            exit;
        }
    } catch (Exception $e) {
        error_log("Verification error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while verifying your payment.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid transaction.";
    header("Location: ../public/backend/deposit.php");
    exit;
}
