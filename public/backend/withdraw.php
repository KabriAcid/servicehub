<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';

require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

// Ensure only providers can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'provider') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch wallet balance
$stmt = $pdo->prepare("SELECT balance FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallet = $stmt->fetch();
$current_balance = $wallet ? $wallet['balance'] : 0.00;

// Handle withdrawal request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);

    if ($amount <= 0) {
        $_SESSION['error'] = "Invalid withdrawal amount.";
    } elseif ($amount > $current_balance) {
        $_SESSION['error'] = "Insufficient wallet balance.";
    } else {
        $new_balance = $current_balance - $amount;

        // Update wallet
        $update = $pdo->prepare("UPDATE wallets SET balance = ?, last_updated = NOW() WHERE user_id = ?");
        $update->execute([$new_balance, $user_id]);

        // Log withdrawal (optional table: withdrawals)
        $log = $pdo->prepare("INSERT INTO withdrawals (user_id, amount, status, created_at) VALUES (?, ?, 'pending', NOW())");
        $log->execute([$user_id, $amount]);

        $_SESSION['success'] = "₦" . number_format($amount, 2) . " withdrawal submitted successfully.";
        header("Location: withdraw.php");
        exit;
    }

    header("Location: withdraw.php");
    exit;
}
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="container py-5" style="max-width: 600px;">
                    <h2 class="text-center mb-4">Withdraw Funds</h2>
                    <p class="text-center text-muted mb-4">
                        Securely request withdrawal of your earnings. Withdrawals are virtual and processed manually.
                    </p>

                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger text-white text-center alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <div class="text-center mb-3">
                        <h5>Current Balance: ₦<?= number_format($current_balance, 2); ?></h5>
                    </div>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Withdrawal Amount (₦)</label>
                            <input type="number" class="input-field" id="amount" name="amount" placeholder="Enter amount" required min="100" step="0.01">
                        </div>
                        <button type="submit" class="btn primary-btn w-100">Withdraw</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>