<!-- filepath: c:\xampp\htdocs\servicehub\api\process-deposit.php -->
<?php
require_once __DIR__ . '/../config/config.php';

if (isset($_GET['status']) && $_GET['status'] === 'completed') {
    $transaction_id = $_GET['transaction_id'] ?? null;

    // Verify transaction with Flutterwave API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $_ENV['FLUTTERWAVE_SECRET_KEY']
        ]
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        error_log("Flutterwave verification error: " . $error);
        $_SESSION['error'] = "Failed to verify payment.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }

    $response_data = json_decode($response, true);

    if (isset($response_data['status']) && $response_data['status'] === 'completed') {
        $amount = $response_data['data']['amount'];
        $user_id = $current_user['id'];

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Update wallet balance
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
            $stmt->execute([$amount, $user_id]);

            // Record transaction
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status, transaction_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, 'deposit', $amount, 'completed', $transaction_id]);

            // Commit transaction
            $pdo->commit();

            $_SESSION['success'] = "Wallet funded successfully.";
            header("Location: ../public/backend/deposit.php");
            exit;
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            error_log("Transaction error: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while updating your wallet.";
            header("Location: ../public/backend/deposit.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Payment verification failed.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Payment failed or canceled.";
    header("Location: ../public/backend/deposit.php");
    exit;
}
