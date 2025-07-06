<!-- filepath: c:\xampp\htdocs\servicehub\api\process-deposit.php -->
<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? null;

    if (!$amount || $amount < 100) {
        $_SESSION['error'] = "Invalid deposit amount.";
        header("Location: ../public/backend/deposit.php");
        exit;
    }

    try {
        // Flutterwave API credentials
        $secret_key = $_ENV('FLUTTERWAVE_SECRET_KEY'); // Replace with your Flutterwave secret key
        $redirect_url = 'https://yourdomain.com/servicehub/api/verify-deposit.php'; // Replace with your redirect URL

        // Prepare the payload
        $payload = [
            'tx_ref' => uniqid('TX_'), // Unique transaction reference
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => $redirect_url,
            'customer' => [
                'email' => $current_user['email'], // Assuming the logged-in user's email is stored in `$current_user`
                'name' => $current_user['name'] // Assuming the logged-in user's name is stored in `$current_user`
            ],
            'payment_options' => 'card,banktransfer'
        ];

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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
            // Redirect to the payment link
            header("Location: " . $response_data['data']['link']);
            exit;
        } else {
            $_SESSION['error'] = "Failed to initialize payment: " . ($response_data['message'] ?? 'Unknown error');
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
