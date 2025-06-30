<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? 0;

    // Validate deposit amount
    if ($amount <= 0) {
        $_SESSION['error'] = "Invalid deposit amount.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }

    // Generate transaction reference
    $tx_ref = uniqid('TX_');

    // Prepare payment data
    $payment_data = [
        'tx_ref' => $tx_ref,
        'amount' => $amount,
        'currency' => 'NGN',
        'customer' => [
            'email' => $user['email'],
            'name' => $user['name']
        ],
        'customizations' => [
            'title' => 'ServiceHub Wallet Deposit',
            'description' => 'Deposit funds into your ServiceHub wallet.'
        ]
    ];

    // Initialize cURL
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payment_data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $_ENV['FLUTTERWAVE_SECRET_KEY'],
            "Content-Type: application/json"
        ]
    ]);

    // Execute cURL request
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        error_log("Flutterwave API error: " . $error);
        $_SESSION['error'] = "An error occurred while processing your payment.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }

    $response_data = json_decode($response, true);

    // Check if payment initialization was successful
    if (isset($response_data['status']) && $response_data['status'] === 'success') {
        $payment_link = $response_data['data']['link'];

        // Redirect to payment link
        header("Location: " . $payment_link);
        exit;
    } else {
        $_SESSION['error'] = "Payment initialization failed.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }
}

// Verify payment success (manual verification or webhook simulation)
if (isset($_GET['status']) && $_GET['status'] === 'successful') {
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

    if (isset($response_data['status']) && $response_data['status'] === 'success') {
        $amount = $response_data['data']['amount'];
        $reference = $response_data['data']['tx_ref'];
        $user_id = $user['id'];

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Update wallet balance
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance + ? WHERE user_id = ?");
            $stmt->execute([$amount, $user_id]);

            // Record transaction
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status, reference, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, 'deposit', $amount, $reference, 'completed']);

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
}
