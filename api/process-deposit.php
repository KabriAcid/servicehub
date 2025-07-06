<!-- filepath: c:\xampp\htdocs\servicehub\api\process-deposit.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Flutterwave\Rave;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? null;

    if (!$amount || $amount < 100) {
        $_SESSION['error'] = "Invalid deposit amount.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }

    try {
        $rave = new Rave(getenv('FLUTTERWAVE_SECRET_KEY')); // Replace with your Flutterwave secret key

        $transaction = $rave->initializePayment([
            'amount' => $amount,
            'email' => $current_user['email'], // Assuming the logged-in user's email is stored in `$current_user`
            'tx_ref' => uniqid('TX_'), // Unique transaction reference
            'currency' => 'NGN',
            'redirect_url' => 'https://yourdomain.com/servicehub/api/verify-deposit.php'
        ]);

        if ($transaction['status'] === 'success') {
            header("Location: " . $transaction['data']['link']);
            exit;
        } else {
            $_SESSION['error'] = "Failed to initialize payment.";
            header("Location: ../public/backend/deposit.php");
            exit;
        }
    } catch (Exception $e) {
        error_log("Flutterwave error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while processing your deposit.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }
}
